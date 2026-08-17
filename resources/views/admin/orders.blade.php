@extends('layouts.admin')

@section('content')
    <x-pagetitle
        title="Órdenes y pagos"
        icon="bi-receipt"
        section="Operaciones"
        subtitle="Consulta el historial de compras y el estado de cada pago."
    />

    <div class="erp-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left">
                <thead class="border-b border-slate-100 bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Orden</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Cliente</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Total</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Estado</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($orders as $order)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-6 py-5 font-semibold">#{{ $order->id }}</td>
                            <td class="px-6 py-5">
                                <strong class="block">{{ $order->user->name }}</strong>
                                <small class="text-xs text-slate-500">{{ $order->user->email }}</small>
                            </td>
                            <td class="px-6 py-5 font-bold">Bs {{ number_format($order->total, 2, ',', '.') }}</td>
                            <td class="px-6 py-5">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                    'bg-emerald-100 text-emerald-700' => $order->status === 'paid',
                                    'bg-amber-100 text-amber-700' => $order->status === 'pending',
                                    'bg-rose-100 text-rose-700' => in_array($order->status, ['expired', 'cancelled']),
                                ])>
                                    {{ ['pending' => 'Pendiente', 'paid' => 'Pagada', 'expired' => 'Expirada', 'cancelled' => 'Anulada'][$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-sm text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-10 text-center text-slate-500" colspan="5">No hay órdenes registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($orders->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">{{ $orders->links() }}</div>
        @endif
    </div>
@endsection
