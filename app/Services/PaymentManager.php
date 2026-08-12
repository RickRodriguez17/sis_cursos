<?php

namespace App\Services;

use App\Contracts\PaymentGateway;
use App\Models\Order;

class PaymentManager
{
    public function driver(): PaymentGateway
    {
        return config('services.payment_gateway') === 'libelula' ? app(LibelulaGateway::class) : app(FakeGateway::class);
    }

    public function charge(Order $order): array
    {
        return $this->driver()->createCharge($order);
    }
}
