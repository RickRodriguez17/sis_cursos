@extends('layout')

@section('content')
    <section class="rounded-3xl bg-indigo-100 p-12">
        <p class="font-semibold text-indigo-700">Aprende a tu ritmo</p>
        <h1 class="mt-3 text-5xl font-black">Cursos prácticos para transformar tus ideas.</h1>
        <p class="mt-5 max-w-2xl text-lg">
            Accede a video-lecciones cuidadosamente creadas y aprende desde cualquier lugar.
        </p>
        <a class="mt-8 inline-block rounded-lg bg-indigo-700 px-6 py-3 font-semibold text-white" href="{{ route('courses.index') }}">
            Explorar cursos
        </a>
    </section>
@endsection
