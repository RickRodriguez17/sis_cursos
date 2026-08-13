<div>
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-4xl font-black">{{ $course?->exists ? 'Editar curso' : 'Nuevo curso' }}</h1>
        <a class="text-indigo-700" href="{{ route('admin.courses.index') }}">← Volver a cursos</a>
    </div>

    <form class="space-y-5 rounded-2xl bg-white p-6 shadow" wire:submit="saveCourse">
        <div class="grid gap-5 md:grid-cols-2">
            <label class="block">Título<input class="mt-1 w-full rounded border p-3" wire:model="title"></label>
            <label class="block">Slug<input class="mt-1 w-full rounded border p-3" wire:model="slug"></label>
            <label class="block">Descripción corta<textarea class="mt-1 w-full rounded border p-3" wire:model="shortDescription"></textarea></label>
            <label class="block">Precio en Bs<input class="mt-1 w-full rounded border p-3" type="number" step="0.01" wire:model="price"></label>
        </div>
        <label class="block">Descripción completa<textarea class="mt-1 w-full rounded border p-3" rows="5" wire:model="description"></textarea></label>
        <label class="block">Portada<input class="mt-1 w-full rounded border p-3" type="file" wire:model="image"></label>
        <label class="inline-flex items-center gap-2"><input type="checkbox" wire:model="isPublished"> Publicado</label>
        <button class="rounded-lg bg-indigo-700 px-5 py-3 font-semibold text-white">Guardar curso</button>
    </form>

    @if ($course?->exists)
        <section class="mt-8 rounded-2xl bg-white p-6 shadow">
            <h2 class="text-2xl font-bold">Videos del curso</h2>
            <div class="mt-5 space-y-2">
                @foreach ($lessons as $lesson)
                    <div class="flex items-center justify-between rounded border p-3">
                        <span>{{ $loop->iteration }}. {{ $lesson->title }} @if ($lesson->is_preview)<small class="text-emerald-700">(preview)</small>@endif</span>
                        <div class="flex gap-3 text-sm">
                            <button wire:click="moveLesson({{ $lesson->id }}, 'up')">↑</button>
                            <button wire:click="moveLesson({{ $lesson->id }}, 'down')">↓</button>
                            <button class="text-indigo-700" wire:click="editLesson({{ $lesson->id }})">Editar</button>
                            <button class="text-red-600" wire:click="deleteLesson({{ $lesson->id }})">Eliminar</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <h3 class="mt-8 text-xl font-bold">{{ $editingLessonId ? 'Editar video' : 'Agregar video' }}</h3>
            <form class="mt-4 space-y-4" wire:submit="saveLesson">
                <input class="w-full rounded border p-3" wire:model="lessonTitle" placeholder="Título del video">
                <textarea class="w-full rounded border p-3" wire:model="lessonDescription" placeholder="Descripción"></textarea>
                <div class="grid gap-4 md:grid-cols-2">
                    <select class="rounded border p-3" wire:model.live="videoType">
                        <option value="youtube">URL de YouTube</option>
                        <option value="vimeo">URL de Vimeo</option>
                        <option value="file">Archivo de video</option>
                    </select>
                    <input class="rounded border p-3" wire:model="duration" type="number" placeholder="Duración en segundos">
                </div>
                @if ($videoType === 'file')
                    <input class="w-full rounded border p-3" wire:model="videoFile" type="file" accept="video/*">
                @else
                    <input class="w-full rounded border p-3" wire:model="videoUrl" type="url" placeholder="https://...">
                @endif
                <label class="inline-flex items-center gap-2"><input type="checkbox" wire:model="isPreview"> Video de muestra gratis</label>
                <button class="rounded-lg bg-emerald-600 px-5 py-3 font-semibold text-white">Guardar video</button>
            </form>
        </section>
    @endif
</div>
