@extends('layouts.admin')

@section('content')
    <x-pagetitle title="Panel de administración" icon="bi-grid-1x2" section="Panel" subtitle="Gestiona tu catálogo y revisa la actividad de Aula Viva.">
        <x-slot:actions><a class="erp-button-primary" href="{{ route('admin.courses.create') }}"><i class="bi bi-plus-lg"></i>Nuevo curso</a></x-slot:actions>
    </x-pagetitle>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="erp-card p-5"><span class="erp-icon"><i class="bi bi-journal-richtext"></i></span><p class="mt-4 text-sm text-slate-500">Catálogo</p><strong class="mt-1 block text-2xl">Cursos</strong><a class="mt-3 inline-flex text-sm font-bold text-indigo-600" href="{{ route('admin.courses.index') }}">Administrar <i class="bi bi-arrow-right ml-2"></i></a></div>
        <div class="erp-card p-5"><span class="erp-icon bg-emerald-100 text-emerald-700"><i class="bi bi-receipt"></i></span><p class="mt-4 text-sm text-slate-500">Operaciones</p><strong class="mt-1 block text-2xl">Órdenes y pagos</strong><a class="mt-3 inline-flex text-sm font-bold text-indigo-600" href="#ordenes">Consultar <i class="bi bi-arrow-right ml-2"></i></a></div>
        <div class="erp-card p-5"><span class="erp-icon bg-violet-100 text-violet-700"><i class="bi bi-people"></i></span><p class="mt-4 text-sm text-slate-500">Comunidad</p><strong class="mt-1 block text-2xl">Inscripciones</strong><a class="mt-3 inline-flex text-sm font-bold text-indigo-600" href="#inscripciones">Ver resumen <i class="bi bi-arrow-right ml-2"></i></a></div>
        <div class="erp-card bg-indigo-600 p-5 text-white"><span class="erp-icon bg-white/15 text-white"><i class="bi bi-lightning-charge"></i></span><p class="mt-4 text-sm text-indigo-100">Atajo</p><strong class="mt-1 block text-2xl">Publica contenido</strong><a class="mt-3 inline-flex text-sm font-bold text-white" href="{{ route('admin.courses.create') }}">Comenzar <i class="bi bi-arrow-right ml-2"></i></a></div>
    </div>
    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div id="ordenes" class="erp-card p-6"><h2 class="text-lg font-bold"><i class="bi bi-receipt mr-2 text-indigo-600"></i>Órdenes y pagos</h2><p class="mt-2 text-sm text-slate-500">Las confirmaciones de pago se procesan automáticamente mediante el webhook configurado.</p></div>
        <div id="inscripciones" class="erp-card p-6"><h2 class="text-lg font-bold"><i class="bi bi-people mr-2 text-indigo-600"></i>Inscripciones</h2><p class="mt-2 text-sm text-slate-500">Cada pago confirmado habilita el acceso a los cursos de forma idempotente.</p></div>
    </div>
@endsection
