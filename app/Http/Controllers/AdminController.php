<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.index', [
            'publishedCourses' => Course::where('is_published', true)->count(),
            'draftCourses' => Course::where('is_published', false)->count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'paidOrders' => Order::where('status', 'paid')->count(),
            'enrollmentCount' => Enrollment::count(),
            'confirmedRevenue' => Order::where('status', 'paid')->sum('total'),
            'latestOrders' => Order::with('user')->latest()->limit(10)->get(),
            'latestEnrollments' => Enrollment::with(['user', 'course'])->latest('enrolled_at')->limit(10)->get(),
        ]);
    }

    public function orders()
    {
        return view('admin.orders', [
            'orders' => Order::with('user')->latest()->paginate(15),
        ]);
    }

    public function enrollments()
    {
        return view('admin.enrollments', [
            'enrollments' => Enrollment::with(['user', 'course'])
                ->latest('enrolled_at')
                ->paginate(15),
        ]);
    }
}
