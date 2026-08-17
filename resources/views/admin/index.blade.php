@extends('layouts.admin')

@section('content')
    <x-pagetitle
        title="Panel de administración"
        icon="bi-grid-1x2"
        section="Panel"
        subtitle="Gestiona tu catálogo y revisa la actividad de Aula Viva."
    >
        <x-slot:actions>
            <a class="erp-button-primary" href="{{ route('admin.courses.create') }}">
                <i class="bi bi-plus-lg"></i>
                Nuevo curso
            </a>
        </x-slot:actions>
    </x-pagetitle>

    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <div class="erp-card p-5">
            <span class="erp-icon"><i class="bi bi-journal-check"></i></span>
            <p class="mt-4 text-sm text-slate-500">Cursos publicados</p>
            <strong class="mt-1 block text-3xl">{{ $publishedCourses }}</strong>
            <a class="mt-3 inline-flex text-sm font-bold text-indigo-600" href="{{ route('admin.courses.index') }}">
                Administrar <i class="bi bi-arrow-right ml-2"></i>
            </a>
        </div>
        <div class="erp-card p-5">
            <span class="erp-icon bg-slate-100 text-slate-600"><i class="bi bi-pencil-square"></i></span>
            <p class="mt-4 text-sm text-slate-500">Cursos en borrador</p>
            <strong class="mt-1 block text-3xl">{{ $draftCourses }}</strong>
            <a class="mt-3 inline-flex text-sm font-bold text-indigo-600" href="{{ route('admin.courses.index') }}">
                Revisar catálogo <i class="bi bi-arrow-right ml-2"></i>
            </a>
        </div>
        <div class="erp-card p-5">
            <span class="erp-icon bg-emerald-100 text-emerald-700"><i class="bi bi-receipt"></i></span>
            <p class="mt-4 text-sm text-slate-500">Órdenes pagadas</p>
            <strong class="mt-1 block text-3xl">{{ $paidOrders }}</strong>
            <p class="mt-3 text-sm text-slate-500">{{ $pendingOrders }} pendientes</p>
        </div>
        <div class="erp-card bg-indigo-600 p-5 text-white">
            <span class="erp-icon bg-white/15 text-white"><i class="bi bi-cash-coin"></i></span>
            <p class="mt-4 text-sm text-indigo-100">Ingresos confirmados</p>
            <strong class="mt-1 block text-3xl">Bs {{ number_format($confirmedRevenue, 2, ',', '.') }}</strong>
            <p class="mt-3 text-sm text-indigo-100">
                {{ $enrollmentCount }}
                {{ $enrollmentCount === 1 ? 'inscripción' : 'inscripciones' }}
            </p>
        </div>
    </div>

    <div class="mt-8 grid gap-6 xl:grid-cols-2">
        <section class="erp-card overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="text-lg font-bold">Últimas órdenes</h2>
                    <p class="mt-1 text-sm text-slate-500">Las compras más recientes de la tienda.</p>
                </div>
                <a class="text-sm font-bold text-indigo-600 hover:text-indigo-800" href="{{ route('admin.orders') }}">
                    Ver todas
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($latestOrders as $order)
                    <div class="flex items-center gap-3 px-6 py-4">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-sm font-bold text-indigo-700">
                            #{{ $order->id }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <strong class="block truncate text-sm">{{ $order->user->name }}</strong>
                            <span class="text-xs text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="text-right">
                            <strong class="block text-sm">Bs {{ number_format($order->total, 2, ',', '.') }}</strong>
                            <span @class([
                                'text-xs font-bold',
                                'text-emerald-600' => $order->status === 'paid',
                                'text-amber-600' => $order->status === 'pending',
                                'text-rose-600' => in_array($order->status, ['expired', 'cancelled']),
                            ])>
                                {{ ['pending' => 'Pendiente', 'paid' => 'Pagada', 'expired' => 'Expirada', 'cancelled' => 'Anulada'][$order->status] ?? ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-10 text-center text-sm text-slate-500">Todavía no hay órdenes.</p>
                @endforelse
            </div>
        </section>

        <section class="erp-card overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <h2 class="text-lg font-bold">Últimas inscripciones</h2>
                    <p class="mt-1 text-sm text-slate-500">Accesos habilitados automáticamente.</p>
                </div>
                <a class="text-sm font-bold text-indigo-600 hover:text-indigo-800" href="{{ route('admin.enrollments') }}">
                    Ver todas
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($latestEnrollments as $enrollment)
                    <div class="flex items-center gap-3 px-6 py-4">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-violet-50 text-violet-700">
                            <i class="bi bi-person-check"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <strong class="block truncate text-sm">{{ $enrollment->user->name }}</strong>
                            <span class="block truncate text-xs text-slate-500">{{ $enrollment->course->title }}</span>
                        </div>
                        <span class="shrink-0 text-xs text-slate-500">{{ $enrollment->enrolled_at->format('d/m/Y') }}</span>
                    </div>
                @empty
                    <p class="px-6 py-10 text-center text-sm text-slate-500">Todavía no hay inscripciones.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
