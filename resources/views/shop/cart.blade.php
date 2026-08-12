@extends('layout')

@section('content')
    <h1 class="text-4xl font-black">Tu carrito</h1>

    @if ($courses->isEmpty())
        <p class="mt-8">No hay cursos seleccionados.</p>
    @else
        <div class="mt-8 space-y-3">
            @foreach ($courses as $course)
                <div class="flex items-center justify-between rounded-xl bg-white p-5 shadow">
                    <span class="font-semibold">{{ $course->title }}</span>
                    <div class="flex gap-5">
                        <span>Bs {{ number_format($course->price, 2, ',', '.') }}</span>
                        <form method="post" action="{{ route('cart.remove', $course) }}">
                            @csrf
                            @method('delete')
                            <button class="text-red-600">Quitar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex justify-between text-xl font-bold">
            <span>Total</span>
            <span>Bs {{ number_format($courses->sum('price'), 2, ',', '.') }}</span>
        </div>
        <form class="mt-6" method="post" action="{{ route('checkout') }}">
            @csrf
            <button class="rounded-lg bg-indigo-700 px-6 py-3 font-bold text-white">
                Generar QR de pago
            </button>
        </form>
    @endif
@endsection
