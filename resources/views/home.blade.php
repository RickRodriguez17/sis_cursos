@extends('layout')

@section('content')
    <section class="relative overflow-hidden rounded-3xl bg-slate-900 px-6 py-14 text-white shadow-xl sm:px-12 lg:px-16">
        <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-indigo-500/30 blur-3xl"></div>
        <div class="absolute -bottom-32 left-1/2 h-72 w-72 rounded-full bg-violet-500/20 blur-3xl"></div>
        <div class="relative max-w-3xl">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-sm font-semibold text-indigo-200"><i class="bi bi-stars"></i> Aprende algo nuevo hoy</span>
            <h1 class="mt-6 text-4xl font-black tracking-tight sm:text-6xl">Habilidades prácticas para transformar tus ideas.</h1>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-300">Accede a video-lecciones cuidadosamente creadas, estudia a tu ritmo y conviértete en la persona que quieres ser.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a class="erp-button-primary bg-indigo-500 hover:bg-indigo-400" href="{{ route('courses.index') }}"><i class="bi bi-compass"></i>Explorar cursos</a>
                <a class="erp-button-secondary border-white/20 bg-white/10 text-white hover:bg-white/20" href="{{ route('courses.index') }}">Ver nuestro catálogo <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <section class="mt-12 grid gap-4 sm:grid-cols-3">
        <div class="erp-card p-5"><span class="erp-icon"><i class="bi bi-play-circle"></i></span><h2 class="mt-4 font-bold">Clases en video</h2><p class="mt-1 text-sm text-slate-500">Contenido claro y directo para avanzar.</p></div>
        <div class="erp-card p-5"><span class="erp-icon"><i class="bi bi-phone"></i></span><h2 class="mt-4 font-bold">Aprende donde quieras</h2><p class="mt-1 text-sm text-slate-500">Accede desde cualquier dispositivo.</p></div>
        <div class="erp-card p-5"><span class="erp-icon"><i class="bi bi-award"></i></span><h2 class="mt-4 font-bold">A tu propio ritmo</h2><p class="mt-1 text-sm text-slate-500">Repite las lecciones cuando lo necesites.</p></div>
    </section>
@endsection
