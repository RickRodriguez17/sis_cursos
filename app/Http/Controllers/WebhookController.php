<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function handle(Request $request, string $gateway)
    {
        $ref = $request->input('external_id', $request->input('reference'));
        $order = Order::where('external_reference', $ref)->firstOrFail();
        if ($request->input('status') === 'paid' || $request->input('approved') === true) {
            $this->confirm($order);
        }

return response()->json(['ok' => true]);
    }

    public function confirm(Order $order)
    {
        DB::transaction(function () use ($order) {
            $order->refresh();
            if ($order->status === 'paid') {
                return;
            }$order->update(['status' => 'paid']);
            $order->payment?->update(['status' => 'paid']);
            foreach ($order->items as $item) {
                Enrollment::firstOrCreate(['user_id' => $order->user_id, 'course_id' => $item->course_id], ['order_id' => $order->id, 'enrolled_at' => now()]);
            }
        });
    }
}
