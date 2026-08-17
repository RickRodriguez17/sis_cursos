@extends('layout')

@section('content')
    <div>
        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">
            Tu aprendizaje
        </p>
        <h1 class="mt-2 text-3xl font-black tracking-tight">Mis cursos</h1>
        <p class="mt-2 text-slate-500">Continúa donde lo dejaste.</p>
    </div>
    <div class="mt-8 grid gap-6 md:grid-cols-2">
        @forelse ($courses as $course)
            <article class="erp-card group overflow-hidden p-6 transition hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-start gap-4">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 text-2xl text-white">
                        <i class="bi bi-play-fill"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <span class="text-xs font-semibold uppercase tracking-wider text-emerald-600">
                            <i class="bi bi-patch-check-fill mr-1"></i>
                            Inscrito
                        </span>
                        <h2 class="mt-1 truncate text-xl font-bold">
                            {{ $course->title }}
                        </h2>
                        <p class="mt-2 text-sm text-slate-500">
                            {{ $course->lessons->count() }}
                            {{ $course->lessons->count() === 1 ? 'video disponible' : 'videos disponibles' }}
                        </p>
                    </div>
                </div>
                <a
                    class="mt-6 inline-flex items-center gap-2 text-sm font-bold text-indigo-600"
                    href="{{ route('courses.show', $course) }}"
                >
                    Continuar aprendiendo
                    <i class="bi bi-arrow-right transition group-hover:translate-x-1"></i>
                </a>
            </article>
        @empty
            <div class="erp-card p-10 text-center md:col-span-2">
                <i class="bi bi-journal-bookmark text-4xl text-slate-300"></i>
                <p class="mt-4 font-semibold">Aún no tienes cursos.</p>
                <a class="mt-4 inline-flex font-semibold text-indigo-600" href="{{ route('courses.index') }}">
                    Explorar catálogo
                    <i class="bi bi-arrow-right ml-2"></i>
                </a>
            </div>
        @endforelse
    </div>
@endsection
