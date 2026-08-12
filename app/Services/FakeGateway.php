<?php

namespace App\Services;

use App\Contracts\PaymentGateway;
use App\Models\Order;

class FakeGateway implements PaymentGateway
{
    public function createCharge(Order $order): array
    {
        $id = 'FAKE-'.$order->id.'-'.str()->random(10);
        $payload = "SIS-CURSOS|orden={$order->id}|monto={$order->total}|ref={$id}";

        return ['external_id' => $id, 'qr_url' => null, 'payload' => ['simulated' => true, 'qr_payload' => $payload]];
    }

    public function verify(string $externalId): array
    {
        return ['status' => 'pending', 'external_id' => $externalId];
    }
}
