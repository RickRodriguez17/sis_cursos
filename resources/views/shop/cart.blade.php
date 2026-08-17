@extends('layout')

@section('content')
    <div class="flex items-end justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Tu selección</p>
            <h1 class="mt-2 text-3xl font-black tracking-tight">Carrito de compra</h1>
        </div>
        <a class="hidden text-sm font-semibold text-indigo-600 hover:text-indigo-800 sm:inline" href="{{ route('courses.index') }}"><i class="bi bi-arrow-left mr-1"></i>Seguir explorando</a>
    </div>

    @if ($courses->isEmpty())
        <div class="erp-card mt-8 p-12 text-center">
            <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-50 text-3xl text-indigo-500"><i class="bi bi-bag"></i></span>
            <h2 class="mt-5 text-xl font-bold">Tu carrito está vacío</h2>
            <p class="mt-2 text-slate-500">Encuentra un curso que te inspire y empieza a aprender.</p>
            <a class="erp-button-primary mt-6" href="{{ route('courses.index') }}">Explorar cursos <i class="bi bi-arrow-right"></i></a>
        </div>
    @else
        <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_340px]">
            <div class="space-y-3">
            @foreach ($courses as $course)
                <div class="erp-card flex items-center gap-4 p-4 sm:p-5">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-2xl text-white"><i class="bi bi-play-btn"></i></span>
                    <div class="min-w-0 flex-1">
                        <a class="font-bold hover:text-indigo-600" href="{{ route('courses.show', $course) }}">{{ $course->title }}</a>
                        <p class="mt-1 text-sm text-slate-500">{{ $course->lessons()->count() }} lecciones en video</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="font-bold text-slate-900">Bs {{ number_format($course->price, 2, ',', '.') }}</span>
                        <form method="post" action="{{ route('cart.remove', $course) }}">
                            @csrf
                            @method('delete')
                            <button class="rounded-lg p-2 text-slate-400 hover:bg-rose-50 hover:text-rose-600" title="Quitar del carrito"><i class="bi bi-trash3"></i></button>
                        </form>
                    </div>
                </div>
            @endforeach
            </div>
            <aside class="erp-card h-fit p-6 lg:sticky lg:top-28">
                <h2 class="text-lg font-bold">Resumen de compra</h2>
                <div class="mt-5 flex justify-between border-b border-slate-100 pb-4 text-sm text-slate-500">
                    <span>
                        {{ $courses->count() }}
                        {{ $courses->count() === 1 ? 'curso' : 'cursos' }}
                    </span>
                    <span>Pago único</span>
                </div>
                <div class="mt-5 flex items-center justify-between"><span class="font-semibold">Total</span><strong class="text-2xl text-indigo-700">Bs {{ number_format($courses->sum('price'), 2, ',', '.') }}</strong></div>
                <form class="mt-6" method="post" action="{{ route('checkout') }}">
                    @csrf
                    <button class="erp-button-primary w-full"><i class="bi bi-qr-code"></i>Generar QR de pago</button>
                </form>
                <p class="mt-4 text-center text-xs leading-5 text-slate-500"><i class="bi bi-shield-check mr-1 text-emerald-500"></i>Tu pago se procesa de forma segura.</p>
            </aside>
        </div>
    @endif
@endsection
