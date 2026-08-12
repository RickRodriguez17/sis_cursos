<?php

namespace App\Http\Controllers;

use App\Models\Order;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class PaymentController extends Controller
{
    public function qr(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $payment = $order->payment;
        abort_unless($payment && $payment->gateway === 'fake', 404);

        $payload = data_get($payment->payload, 'qr_payload');
        abort_unless($payload, 404);

        $svg = (new QRCode(new QROptions(['outputType' => QRCode::OUTPUT_MARKUP_SVG])))->render($payload);

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }
}
