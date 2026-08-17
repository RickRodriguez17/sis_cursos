<div>
    <x-pagetitle title="Cursos y videos" icon="bi-collection-play" section="Catálogo" subtitle="Crea, publica y organiza el contenido de tu academia.">
        <x-slot:actions><a class="erp-button-primary" href="{{ route('admin.courses.create') }}"><i class="bi bi-plus-lg"></i>Nuevo curso</a></x-slot:actions>
    </x-pagetitle>
    <div class="erp-card overflow-hidden">
        <div class="border-b border-slate-100 p-5">
            <x-table-toolbar />
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-left">
                <thead class="border-b border-slate-100 bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Curso</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Videos</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Estado</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse ($courses as $course)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-6 py-5"><div class="flex items-center gap-3"><span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-lg text-white"><i class="bi bi-play-btn"></i></span><div><strong class="block">{{ $course->title }}</strong><small class="text-xs text-slate-500">{{ $course->slug }}</small></div></div></td>
                        <td class="px-6 py-5 text-sm text-slate-600"><i class="bi bi-collection-play mr-1 text-indigo-500"></i>{{ $course->lessons_count }}</td>
                        <td class="px-6 py-5"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-bold {{ $course->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $course->is_published ? 'Publicado' : 'Borrador' }}</span></td>
                        <td class="px-6 py-5 text-right">
                            <a class="mr-3 text-sm font-bold text-indigo-600 hover:text-indigo-800" href="{{ route('admin.courses.edit', $course) }}">Editar</a>
                            <button class="mr-3 text-sm font-semibold text-slate-500 hover:text-indigo-600" wire:click="toggle({{ $course->id }})" wire:confirm="{{ $course->is_published ? '¿Despublicar este curso?' : '¿Publicar este curso?' }}">
                                <i class="bi {{ $course->is_published ? 'bi-eye-slash' : 'bi-eye' }} mr-1"></i>{{ $course->is_published ? 'Despublicar' : 'Publicar' }}
                            </button>
                            <button class="text-sm font-semibold text-rose-600 hover:text-rose-800" wire:click="delete({{ $course->id }})" wire:confirm="¿Eliminar este curso? Esta acción no se puede deshacer.">
                                <i class="bi bi-trash3 mr-1"></i>Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-10 text-center text-slate-500" colspan="4"><i class="bi bi-journal-x mr-2"></i>Todavía no hay cursos.</td>
                    </tr>
                @endforelse
            </tbody>
            </table>
        </div>
    </div>
</div>
