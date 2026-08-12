<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderPaymentService
{
    public function confirm(Order $order): void
    {
        DB::transaction(function () use ($order): void {
            $order->refresh();

            if ($order->status === 'paid') {
                return;
            }

            $order->update(['status' => 'paid']);
            $order->payment?->update(['status' => 'paid']);

            foreach ($order->items as $item) {
                Enrollment::firstOrCreate(
                    ['user_id' => $order->user_id, 'course_id' => $item->course_id],
                    ['order_id' => $order->id, 'enrolled_at' => now()],
                );
            }
        });
    }
}
