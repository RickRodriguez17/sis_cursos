<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Services\OrderPaymentService;
use App\Services\PaymentManager;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function cart()
    {
        $ids = session('cart', []);
        $courses = Course::whereIn('id', $ids)->get();

        return view('shop.cart', compact('courses'));
    }

    public function add(Course $course)
    {
        $ids = session('cart', []);

        if (Enrollment::where('user_id', auth()->id())->where('course_id', $course->id)->exists()) {
            return back()->with('status', 'Ya estás inscrito en este curso.');
        }

        if (in_array($course->id, $ids)) {
            return back()->with('status', 'Este curso ya está en tu carrito.');
        }

        $ids[] = $course->id;
        session(['cart' => $ids]);

        return back()->with('success', 'Curso agregado al carrito.');
    }

    public function remove(Course $course)
    {
        session(['cart' => array_values(array_diff(session('cart', []), [$course->id]))]);

        return back()->with('success', 'Curso quitado del carrito.');
    }

    public function checkout(PaymentManager $payments)
    {
        $ids = session('cart', []);
        $courses = Course::whereIn('id', $ids)->get();
        abort_if($courses->isEmpty(), 422, 'El carrito está vacío.');
        $order = DB::transaction(function () use ($courses, $payments) {
            $order = Order::create(['user_id' => auth()->id(), 'total' => $courses->sum('price')]);
            foreach ($courses as $course) {
                $order->items()->create(['course_id' => $course->id, 'price' => $course->price]);
            }

            $charge = $payments->charge($order);
            $order->update(['external_reference' => $charge['external_id']]);
            $order->payment()->create([
                'gateway' => config('services.payment_gateway'),
                'external_id' => $charge['external_id'],
                'amount' => $order->total,
                'payload' => $charge['payload'],
                'qr_url' => $charge['qr_url'],
            ]);

            return $order;
        });
        session()->forget('cart');

        return redirect()->route('orders.waiting', $order);
    }

    public function waiting(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load('payment');

        return view('shop.waiting', compact('order'));
    }

    public function status(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        return response()->json(['status' => $order->fresh()->status]);
    }

    public function fakeConfirm(Order $order, OrderPaymentService $paymentService)
    {
        abort_unless(config('services.payment_gateway') === 'fake' && $order->user_id === auth()->id(), 403);
        $paymentService->confirm($order);

        return redirect('/mis-cursos')->with('success', 'Pago confirmado.');
    }

    public function myCourses()
    {
        return view('account.courses', ['courses' => auth()->user()->courses()->with('lessons')->get()]);
    }

    public function myOrders()
    {
        return view('account.orders', ['orders' => auth()->user()->orders()->with('payment', 'items.course')->latest()->get()]);
    }
}
