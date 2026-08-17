@extends('layout')

@section('content')
    <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">
        Historial
    </p>
    <h1 class="mt-2 text-3xl font-black tracking-tight">Mis órdenes</h1>
    <p class="mt-2 text-slate-500">Consulta tus compras y estados de pago.</p>
    <div class="erp-card mt-8 overflow-hidden">
        <div class="hidden grid-cols-[1fr_1fr_1fr_auto] gap-4 border-b border-slate-100 bg-slate-50 px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 sm:grid">
            <span>Orden</span>
            <span>Fecha</span>
            <span>Estado</span>
            <span>Total</span>
        </div>
        <div class="divide-y divide-slate-100">
        @forelse ($orders as $order)
            <div class="grid gap-2 px-6 py-5 sm:grid-cols-[1fr_1fr_1fr_auto] sm:items-center sm:gap-4">
                <div>
                    <strong class="block">Orden #{{ $order->id }}</strong>
                    <span class="text-xs text-slate-500">
                        {{ $order->items->count() }}
                        {{ $order->items->count() === 1 ? 'curso' : 'cursos' }}
                    </span>
                </div>
                <span class="text-sm text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                <span @class([
                    'inline-flex w-fit items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold',
                    'bg-emerald-100 text-emerald-700' => $order->status === 'paid',
                    'bg-amber-100 text-amber-700' => $order->status !== 'paid',
                ])>
                    <i class="bi {{ $order->status === 'paid' ? 'bi-check-circle' : 'bi-clock' }}"></i>
                    {{ ['pending' => 'Pendiente', 'paid' => 'Pagada', 'expired' => 'Expirada', 'cancelled' => 'Anulada'][$order->status] }}
                </span>
                <strong class="text-lg">Bs {{ number_format($order->total, 2, ',', '.') }}</strong>
            </div>
        @empty
            <div class="p-10 text-center text-slate-500">No tienes órdenes todavía.</div>
        @endforelse
        </div>
    </div>
@endsection
