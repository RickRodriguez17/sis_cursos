@extends('layouts.admin')

@section('content')
    <x-pagetitle
        title="Inscripciones"
        icon="bi-people"
        section="Operaciones"
        subtitle="Revisa los accesos habilitados para cada estudiante."
    />

    <div class="erp-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left">
                <thead class="border-b border-slate-100 bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Estudiante</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Curso</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Orden</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($enrollments as $enrollment)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-6 py-5">
                                <strong class="block">{{ $enrollment->user->name }}</strong>
                                <small class="text-xs text-slate-500">{{ $enrollment->user->email }}</small>
                            </td>
                            <td class="px-6 py-5 font-semibold">{{ $enrollment->course->title }}</td>
                            <td class="px-6 py-5 text-sm text-slate-500">
                                {{ $enrollment->order ? '#'.$enrollment->order_id : '—' }}
                            </td>
                            <td class="px-6 py-5 text-sm text-slate-500">{{ $enrollment->enrolled_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-10 text-center text-slate-500" colspan="4">No hay inscripciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($enrollments->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">{{ $enrollments->links() }}</div>
        @endif
    </div>
@endsection
