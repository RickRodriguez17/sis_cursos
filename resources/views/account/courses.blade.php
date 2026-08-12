@extends('layout')

@section('content')
    <h1 class="text-4xl font-black">Mis cursos</h1>
    <div class="mt-8 grid gap-6 md:grid-cols-2">
        @forelse ($courses as $course)
            <article class="rounded-2xl bg-white p-6 shadow">
                <h2 class="text-xl font-bold">{{ $course->title }}</h2>
                <p class="mt-2 text-slate-500">{{ $course->lessons->count() }} videos disponibles</p>
                <a class="mt-5 inline-block font-semibold text-indigo-700" href="{{ route('courses.show', $course) }}">
                    Continuar aprendiendo →
                </a>
            </article>
        @empty
            <p>
                Aún no tienes cursos.
                <a class="text-indigo-700" href="{{ route('courses.index') }}">Explorar catálogo</a>
            </p>
        @endforelse
    </div>
@endsection
