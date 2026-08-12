@extends('layout')

@section('content')
    <h1 class="text-4xl font-black">Mis órdenes</h1>
    <div class="mt-8 space-y-4">
        @forelse ($orders as $order)
            <div class="rounded-xl bg-white p-5 shadow">
                <div class="flex justify-between">
                    <strong>Orden #{{ $order->id }}</strong>
                    <span class="font-semibold">
                        {{ ['pending' => 'Pendiente', 'paid' => 'Pagada', 'expired' => 'Expirada', 'cancelled' => 'Anulada'][$order->status] }}
                    </span>
                </div>
                <p class="mt-2">
                    Bs {{ number_format($order->total, 2, ',', '.') }} · {{ $order->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
        @empty
            <p>No tienes órdenes.</p>
        @endforelse
    </div>
@endsection
