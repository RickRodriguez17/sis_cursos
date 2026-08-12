<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-black">Cursos</h1>
            <p class="mt-2 text-slate-500">Crea y administra tu catálogo.</p>
        </div>
        <a class="rounded-lg bg-indigo-700 px-5 py-3 font-semibold text-white" href="{{ route('admin.courses.create') }}">
            Nuevo curso
        </a>
    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow">
        <table class="w-full text-left">
            <thead class="border-b bg-slate-50">
                <tr>
                    <th class="p-4">Curso</th>
                    <th class="p-4">Videos</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($courses as $course)
                    <tr class="border-b">
                        <td class="p-4 font-semibold">{{ $course->title }}</td>
                        <td class="p-4">{{ $course->lessons_count }}</td>
                        <td class="p-4">{{ $course->is_published ? 'Publicado' : 'Borrador' }}</td>
                        <td class="p-4 text-right">
                            <a class="mr-3 text-indigo-700" href="{{ route('admin.courses.edit', $course) }}">Editar</a>
                            <button class="mr-3 text-slate-600" wire:click="toggle({{ $course->id }})">
                                {{ $course->is_published ? 'Despublicar' : 'Publicar' }}
                            </button>
                            <button class="text-red-600" wire:click="delete({{ $course->id }})" wire:confirm="¿Eliminar este curso?">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-6" colspan="4">Todavía no hay cursos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
