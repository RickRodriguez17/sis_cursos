@extends('layout')

@section('content')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-black">Administración</h1>
            <p class="mt-2 text-slate-500">Gestiona cursos, videos y órdenes.</p>
        </div>
        <a class="rounded-lg bg-indigo-700 px-5 py-3 font-semibold text-white" href="{{ route('admin.courses.index') }}">
            Gestionar cursos
        </a>
    </div>
@endsection
