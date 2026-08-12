@extends('layout')

@section('content')
    <section class="rounded-3xl bg-indigo-100 p-12">
        <p class="font-semibold text-indigo-700">Aula Viva</p>
        <h1 class="mt-3 text-5xl font-black">Aprende con cursos prácticos en video.</h1>
        <p class="mt-5 max-w-2xl text-lg">
            Explora nuestro catálogo y encuentra tu próxima habilidad.
        </p>
        <a class="mt-8 inline-block rounded-lg bg-indigo-700 px-6 py-3 font-semibold text-white" href="{{ route('courses.index') }}">
            Ver cursos
        </a>
    </section>
@endsection
