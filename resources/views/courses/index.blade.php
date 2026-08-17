@extends('layout')

@section('content')
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Catálogo de aprendizaje</p>
            <h1 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">Todos los cursos</h1>
            <p class="mt-2 text-slate-500">Elige una ruta de aprendizaje y comienza hoy.</p>
        </div>
        <span class="text-sm text-slate-500"><i class="bi bi-collection-play mr-1 text-indigo-600"></i>{{ $courses->count() }} cursos disponibles</span>
    </div>

    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($courses as $course)
            <article class="group erp-card flex h-full flex-col overflow-hidden transition hover:-translate-y-1 hover:shadow-lg">
                <a
                    href="{{ route('courses.show', $course) }}"
                    class="relative block h-48 shrink-0 overflow-hidden bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600"
                >
                    @if ($course->image_path)
                        <img class="h-full w-full object-cover transition duration-500 group-hover:scale-105" src="{{ Storage::disk('public')->url($course->image_path) }}" alt="{{ $course->title }}">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-7xl text-white/20"><i class="bi bi-mortarboard-fill"></i></div>
                    @endif
                    <span class="absolute right-3 top-3 rounded-full bg-white px-3 py-1 text-sm font-bold text-indigo-700 shadow">Bs {{ number_format($course->price, 2, ',', '.') }}</span>
                </a>
                <div class="flex flex-1 flex-col p-5">
                    <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-indigo-600">
                        <i class="bi bi-play-btn"></i>
                        {{ $course->lessons_count }}
                        {{ $course->lessons_count === 1 ? 'lección' : 'lecciones' }}
                    </div>
                    <h2 class="mt-3 text-xl font-bold text-slate-900">
                        {{ $course->title }}
                    </h2>
                    <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">
                        {{ $course->short_description }}
                    </p>
                    <a
                        class="mt-auto inline-flex items-center gap-2 pt-5 text-sm font-bold text-indigo-600 hover:text-indigo-800"
                        href="{{ route('courses.show', $course) }}"
                    >
                        Ver curso
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </article>
        @endforeach
    </div>

    @if ($courses->isEmpty())
        <div class="erp-card mt-8 p-12 text-center">
            <i class="bi bi-journal-x text-4xl text-slate-300"></i>
            <p class="mt-4 font-semibold">Todavía no hay cursos publicados.</p>
            <p class="mt-1 text-sm text-slate-500">Vuelve pronto para descubrir nuevas clases.</p>
        </div>
    @endif
@endsection
