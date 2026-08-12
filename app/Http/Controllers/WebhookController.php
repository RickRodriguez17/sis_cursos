<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request, string $gateway, OrderPaymentService $paymentService)
    {
        $secret = config('services.payment_webhook_secret');
        $provided = $request->header('X-Webhook-Secret');

        if ($secret && ! hash_equals($secret, (string) $provided)) {
            abort(401, 'Firma de webhook inválida.');
        }

        if (! $secret) {
            Log::warning('Webhook de pago recibido sin secreto configurado.', ['gateway' => $gateway]);
        }

        $ref = $request->input('external_id', $request->input('reference'));
        $order = Order::where('external_reference', $ref)->firstOrFail();
        if ($request->input('status') === 'paid' || $request->input('approved') === true) {
            $paymentService->confirm($order);
        }

        return response()->json(['ok' => true]);
    }
}
