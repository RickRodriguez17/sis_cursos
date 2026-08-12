@extends('layout')

@section('content')
    <div class="grid gap-10 md:grid-cols-3">
        <div class="md:col-span-2">
            <div class="h-56 rounded-3xl bg-gradient-to-br from-indigo-500 to-purple-600"></div>
            <h1 class="mt-8 text-4xl font-black">{{ $course->title }}</h1>
            <p class="mt-4 text-lg text-slate-600">{{ $course->description }}</p>

            <h2 class="mt-10 text-2xl font-bold">Contenido del curso</h2>
            <div class="mt-4 space-y-3">
                @foreach ($course->lessons as $lesson)
                    <div class="flex justify-between rounded-xl bg-white p-4 shadow-sm">
                        <div>
                            <strong>{{ $loop->iteration }}. {{ $lesson->title }}</strong>
                            <p class="text-sm text-slate-500">{{ $lesson->description }}</p>
                        </div>
                        @if ($enrolled || $lesson->is_preview)
                            <a class="text-indigo-700" href="{{ route('lessons.video', [$course, $lesson]) }}">
                                Ver video
                            </a>
                        @else
                            <span class="text-slate-400">🔒 Bloqueado</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <aside class="h-fit rounded-2xl bg-white p-6 shadow">
            <p class="text-3xl font-black">Bs {{ number_format($course->price, 2, ',', '.') }}</p>
            @auth
                @if (! $enrolled)
                    <form class="mt-5" method="post" action="{{ route('cart.add', $course) }}">
                        @csrf
                        <button class="w-full rounded-lg bg-indigo-700 px-5 py-3 font-semibold text-white">
                            Agregar al carrito
                        </button>
                    </form>
                @else
                    <p class="mt-5 rounded bg-emerald-100 p-3 text-emerald-800">Ya tienes acceso</p>
                @endif
            @else
                <a class="mt-5 block rounded-lg bg-indigo-700 px-5 py-3 text-center font-semibold text-white" href="{{ route('login') }}">
                    Ingresa para comprar
                </a>
            @endauth
        </aside>
    </div>
@endsection
