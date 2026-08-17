@extends('layout')

@section('content')
    <div class="overflow-hidden rounded-3xl bg-slate-900 text-white shadow-xl">
        <div class="relative flex min-h-64 items-end overflow-hidden bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 p-6 sm:p-10">
            @if ($course->image_path)
                <img
                    class="absolute inset-0 h-full w-full object-cover opacity-40"
                    src="{{ Storage::disk('public')->url($course->image_path) }}"
                    alt=""
                >
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 to-transparent"></div>
            <div class="relative">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1.5 text-sm font-semibold text-indigo-100">
                    <i class="bi bi-play-circle"></i>
                    {{ $course->lessons->count() }}
                    {{ $course->lessons->count() === 1 ? 'lección' : 'lecciones' }}
                </span>
                <h1 class="mt-4 text-3xl font-black tracking-tight sm:text-5xl">
                    {{ $course->title }}
                </h1>
                <p class="mt-3 max-w-2xl text-slate-200">
                    {{ $course->short_description }}
                </p>
            </div>
        </div>
    </div>

    <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_340px]">
        <div>
            <div class="erp-card p-6 sm:p-8">
                <h2 class="text-2xl font-bold">Sobre este curso</h2>
                <p class="mt-4 whitespace-pre-line leading-8 text-slate-600">{{ $course->description }}</p>
            </div>
            <div class="mt-6">
                <h2 class="text-2xl font-bold">Contenido del curso</h2>
                <div class="mt-4 space-y-3">
                    @foreach ($course->lessons as $lesson)
                        <div class="erp-card flex items-center gap-4 p-4 transition hover:border-indigo-200">
                            <span
                                @class([
                                    'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl',
                                    'bg-emerald-100 text-emerald-700' => $lesson->video_path && ($enrolled || $lesson->is_preview),
                                    'bg-amber-100 text-amber-700' => ! $lesson->video_path,
                                    'bg-slate-100 text-slate-400' => ! $enrolled && ! $lesson->is_preview,
                                ])
                            >
                                <i class="bi {{
                                    ! $lesson->video_path
                                        ? 'bi-clock-fill'
                                        : (($enrolled || $lesson->is_preview) ? 'bi-play-fill' : 'bi-lock-fill')
                                }}"></i>
                            </span>
                            <div class="min-w-0 flex-1">
                                <strong class="block truncate">
                                    {{ $loop->iteration }}. {{ $lesson->title }}
                                </strong>
                                <p class="mt-1 truncate text-sm text-slate-500">
                                    {{ $lesson->description ?: 'Lección en video' }}
                                </p>
                            </div>
                            @if (($enrolled || $lesson->is_preview) && $lesson->video_path)
                                <a
                                    class="shrink-0 text-sm font-bold text-indigo-600 hover:text-indigo-800"
                                    href="{{ route('lessons.video', [$course, $lesson]) }}"
                                >
                                    Ver <i class="bi bi-arrow-right"></i>
                                </a>
                            @elseif (! $lesson->video_path)
                                <span class="shrink-0 text-xs font-semibold text-amber-600">
                                    Video pendiente
                                </span>
                            @else
                                <span class="shrink-0 text-xs font-semibold text-slate-400">
                                    Bloqueado
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <aside class="erp-card h-fit p-6 lg:sticky lg:top-28">
            <p class="text-sm font-semibold uppercase tracking-wider text-slate-500">Inversión</p>
            <p class="mt-2 text-4xl font-black text-slate-900">Bs {{ number_format($course->price, 2, ',', '.') }}</p>
            <div class="my-6 space-y-3 border-y border-slate-100 py-5 text-sm text-slate-600">
                <p><i class="bi bi-check-circle-fill mr-2 text-emerald-500"></i>Acceso a todas las lecciones</p>
                <p><i class="bi bi-check-circle-fill mr-2 text-emerald-500"></i>Pago seguro con QR</p>
                <p><i class="bi bi-check-circle-fill mr-2 text-emerald-500"></i>Aprende a tu ritmo</p>
            </div>
            @auth
                @if (! $enrolled)
                    <form class="mt-5" method="post" action="{{ route('cart.add', $course) }}">
                        @csrf
                        <button class="erp-button-primary w-full"><i class="bi bi-bag-plus"></i>Agregar al carrito</button>
                    </form>
                @else
                    <p class="rounded-xl bg-emerald-50 p-4 text-sm font-semibold text-emerald-700"><i class="bi bi-patch-check-fill mr-2"></i>Ya tienes acceso a este curso.</p>
                @endif
            @else
                <a class="erp-button-primary w-full" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i>Ingresa para comprar</a>
            @endauth
        </aside>
    </div>
@endsection
