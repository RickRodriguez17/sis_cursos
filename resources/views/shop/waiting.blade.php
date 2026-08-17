@extends('layout')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="text-center">
            <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 text-2xl text-amber-600"><i class="bi bi-hourglass-split"></i></span>
            <p class="mt-5 text-sm font-semibold uppercase tracking-wider text-indigo-600">Orden #{{ $order->id }}</p>
            <h1 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">Esperando tu pago</h1>
            <p class="mt-3 text-slate-500">Escanea el QR para pagar <strong class="text-slate-800">Bs {{ number_format($order->total, 2, ',', '.') }}</strong>.</p>
        </div>
        <div class="erp-card mx-auto mt-8 p-6 text-center sm:p-8">
            <div class="mx-auto flex max-w-sm items-center justify-center rounded-2xl bg-slate-50 p-4">
                <img class="h-64 w-64" src="{{ $order->payment->qr_url ?: route('orders.qr', $order) }}" alt="QR de pago">
            </div>
            <p class="mt-5 text-sm text-slate-500">Referencia de pago</p>
            <p class="mt-1 font-mono text-sm font-semibold text-slate-800">{{ $order->external_reference }}</p>
            <div class="mt-6 flex items-center justify-center gap-2 rounded-xl bg-indigo-50 px-4 py-3 text-sm text-indigo-700"><span class="h-2 w-2 animate-pulse rounded-full bg-indigo-600"></span>Verificando el estado del pago automáticamente…</div>

            @if (config('services.payment_gateway') === 'fake')
                <form class="mt-5" method="post" action="{{ route('orders.fake', $order) }}">
                    @csrf
                    <button class="erp-button-primary bg-emerald-600 hover:bg-emerald-700"><i class="bi bi-check-circle"></i>Simular pago confirmado</button>
                </form>
            @endif
        </div>
        <p class="mt-6 text-center text-xs text-slate-400">No cierres esta ventana. Te llevaremos a tus cursos cuando confirmemos el pago.</p>
        <script>
            setInterval(() => fetch('{{ route('orders.status', $order) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'paid') {
                        location.href = '{{ route('my.courses') }}';
                    }
                }), 5000);
        </script>
    </div>
@endsection
