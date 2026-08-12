<?php

namespace App\Services;

use App\Contracts\PaymentGateway;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

class LibelulaGateway implements PaymentGateway
{
    public function createCharge(Order $order): array
    {
        // TODO: confirmar contrato/endpoints oficiales de Libélula con sus credenciales.
        $response = Http::withToken(config('services.libelula.token'))->post(config('services.libelula.endpoint'), [
            'amount' => (float) $order->total, 'currency' => 'BOB', 'reference' => (string) $order->id,
            'callback_url' => config('app.url').'/pagos/webhook/libelula',
        ]);
        $response->throw();
        $data = $response->json();

        return ['external_id' => $data['id'] ?? $data['reference'] ?? (string) $order->id, 'qr_url' => $data['qr_url'] ?? $data['qr'] ?? null, 'payload' => $data];
    }

    public function verify(string $externalId): array
    {
        // TODO: ajustar ruta y mapeo cuando se confirme la documentación de Libélula.
        $data = Http::withToken(config('services.libelula.token'))->get(rtrim(config('services.libelula.endpoint'), '/').'/'.$externalId)->throw()->json();

        return ['status' => $data['status'] ?? 'pending', 'external_id' => $externalId, 'payload' => $data];
    }
}
