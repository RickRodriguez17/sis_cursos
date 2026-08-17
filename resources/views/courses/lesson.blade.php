@extends('layout')

@section('content')
    <div class="grid gap-8 lg:grid-cols-[1fr_320px]">
        <div>
            <div class="erp-card overflow-hidden">
                <div class="aspect-video bg-slate-950">
                    <video
                        class="h-full w-full"
                        controls
                        preload="metadata"
                        src="{{ route('lessons.stream', [$course, $lesson]) }}"
                    >
                        Tu navegador no puede reproducir este video.
                    </video>
                </div>
                <div class="p-6 sm:p-8">
                    <a
                        class="text-sm font-semibold text-indigo-600 hover:text-indigo-800"
                        href="{{ route('courses.show', $course) }}"
                    >
                        <i class="bi bi-arrow-left mr-1"></i>
                        Volver al curso
                    </a>
                    <h1 class="mt-4 text-3xl font-black tracking-tight text-slate-900">
                        {{ $lesson->title }}
                    </h1>
                    <p class="mt-4 whitespace-pre-line leading-8 text-slate-600">
                        {{ $lesson->description ?: 'Lección práctica del curso.' }}
                    </p>
                </div>
            </div>
        </div>

        <aside class="erp-card h-fit p-5 lg:sticky lg:top-28">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">
                {{ $course->title }}
            </p>
            <h2 class="mt-2 text-xl font-bold text-slate-900">Contenido del curso</h2>
            <div class="mt-5 space-y-2">
                @foreach ($lessons as $courseLesson)
                    <a
                        @class([
                            'flex items-center gap-3 rounded-xl p-3 text-sm transition',
                            'bg-indigo-50 font-semibold text-indigo-700' => $courseLesson->is($lesson),
                            'hover:bg-slate-50' => ! $courseLesson->is($lesson),
                        ])
                        @if ($courseLesson->video_path)
                            href="{{ route('lessons.video', [$course, $courseLesson]) }}"
                        @endif
                    >
                        <i class="bi {{ $courseLesson->video_path ? 'bi-play-circle' : 'bi-clock text-amber-500' }}"></i>
                        <span class="min-w-0 truncate">
                            {{ $loop->iteration }}. {{ $courseLesson->title }}
                        </span>
                    </a>
                @endforeach
            </div>
        </aside>
    </div>
@endsection
