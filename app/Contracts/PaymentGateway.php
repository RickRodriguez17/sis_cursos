<?php

namespace App\Contracts;

use App\Models\Order;

interface PaymentGateway
{
    public function createCharge(Order $order): array;

    public function verify(string $externalId): array;
}
