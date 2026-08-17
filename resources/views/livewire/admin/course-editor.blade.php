<div>
    <x-pagetitle
        :title="$course?->exists ? 'Editar curso' : 'Nuevo curso'"
        icon="bi-pencil-square"
        section="Cursos"
        subtitle="Define la información que verán tus estudiantes."
    >
        <x-slot:actions>
            <a class="erp-button-secondary" href="{{ route('admin.courses.index') }}">
                <i class="bi bi-arrow-left"></i>
                Volver a cursos
            </a>
        </x-slot:actions>
    </x-pagetitle>

    <form class="erp-card space-y-6 p-6 sm:p-8" wire:submit="saveCourse">
        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="erp-label" for="course-title">Título</label>
                <input
                    id="course-title"
                    class="erp-input"
                    wire:model="title"
                    placeholder="Ej. Fotografía desde cero"
                >
                @error('title')
                    <x-input-error :messages="$message" />
                @enderror
            </div>
            <div>
                <label class="erp-label" for="course-slug">Slug</label>
                <input
                    id="course-slug"
                    class="erp-input"
                    wire:model="slug"
                    placeholder="fotografia-desde-cero"
                >
                @error('slug')
                    <x-input-error :messages="$message" />
                @enderror
            </div>
            <div>
                <label class="erp-label" for="short-description">Descripción corta</label>
                <textarea
                    id="short-description"
                    class="erp-input"
                    wire:model="shortDescription"
                    rows="3"
                    placeholder="Una frase para tu tarjeta"
                ></textarea>
                @error('shortDescription')
                    <x-input-error :messages="$message" />
                @enderror
            </div>
            <div>
                <label class="erp-label" for="price">Precio en Bs</label>
                <input
                    id="price"
                    class="erp-input"
                    wire:model="price"
                    type="number"
                    step="0.01"
                    placeholder="149.00"
                >
                @error('price')
                    <x-input-error :messages="$message" />
                @enderror
            </div>
        </div>

        <div>
            <label class="erp-label" for="description">Descripción completa</label>
            <textarea
                id="description"
                class="erp-input"
                rows="6"
                wire:model="description"
                placeholder="Cuenta qué aprenderán tus estudiantes..."
            ></textarea>
            @error('description')
                <x-input-error :messages="$message" />
            @enderror
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="erp-label" for="image">Portada</label>
                <input
                    id="image"
                    class="erp-input file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:font-semibold file:text-indigo-700"
                    type="file"
                    wire:model="image"
                >
                @error('image')
                    <x-input-error :messages="$message" />
                @enderror
            </div>
            <label class="flex items-center gap-3 self-end rounded-xl bg-slate-50 p-3 text-sm font-semibold text-slate-700">
                <input class="erp-checkbox" type="checkbox" wire:model="isPublished">
                Publicar curso inmediatamente
            </label>
        </div>

        <div class="flex justify-end border-t border-slate-100 pt-5">
            <button class="erp-button-primary">
                <i class="bi bi-check2-circle"></i>
                Guardar curso
            </button>
        </div>
    </form>

    @if ($course?->exists)
        <section class="erp-card mt-6 p-6 sm:p-8">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-xl font-bold">
                        <i class="bi bi-collection-play mr-2 text-indigo-600"></i>
                        Videos del curso
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Ordena las lecciones y define el contenido de muestra.
                    </p>
                </div>
                <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-700">
                    {{ $lessons->count() }}
                    {{ $lessons->count() === 1 ? 'video' : 'videos' }}
                </span>
            </div>

            <div class="mt-6 space-y-2">
                @foreach ($lessons as $lesson)
                    <div class="flex flex-col gap-3 rounded-xl border border-slate-200 p-4 sm:flex-row sm:items-center">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-sm font-bold text-slate-600">
                            {{ $loop->iteration }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <strong class="block truncate">{{ $lesson->title }}</strong>
                            <span class="text-xs text-slate-500">
                                @if ($lesson->video_path)
                                    Archivo privado
                                @else
                                    <span class="font-semibold text-amber-600">Video pendiente de subir</span>
                                @endif
                                @if ($lesson->is_preview)
                                    · <span class="font-semibold text-emerald-600">Preview</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex gap-1 text-sm">
                            <button
                                class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-indigo-600"
                                wire:click="moveLesson({{ $lesson->id }}, 'up')"
                                title="Subir"
                            >
                                <i class="bi bi-chevron-up"></i>
                            </button>
                            <button
                                class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-indigo-600"
                                wire:click="moveLesson({{ $lesson->id }}, 'down')"
                                title="Bajar"
                            >
                                <i class="bi bi-chevron-down"></i>
                            </button>
                            <button
                                class="rounded-lg p-2 text-indigo-600 hover:bg-indigo-50"
                                wire:click="editLesson({{ $lesson->id }})"
                                title="Editar"
                            >
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button
                                class="rounded-lg p-2 text-rose-600 hover:bg-rose-50"
                                wire:click="deleteLesson({{ $lesson->id }})"
                                wire:confirm="¿Eliminar este video? Esta acción no se puede deshacer."
                                title="Eliminar"
                            >
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 border-t border-slate-100 pt-7">
                <h3 class="text-lg font-bold">
                    {{ $editingLessonId ? 'Editar video' : 'Agregar video' }}
                </h3>
                <form class="mt-4 space-y-4" wire:submit="saveLesson">
                    <div>
                        <label class="erp-label">Título del video</label>
                        <input
                            class="erp-input"
                            wire:model="lessonTitle"
                            placeholder="Ej. Configura tu cámara"
                        >
                        @error('lessonTitle')
                            <x-input-error :messages="$message" />
                        @enderror
                    </div>
                    <div>
                        <label class="erp-label">Descripción</label>
                        <textarea
                            class="erp-input"
                            wire:model="lessonDescription"
                            placeholder="Descripción opcional"
                        ></textarea>
                        @error('lessonDescription')
                            <x-input-error :messages="$message" />
                        @enderror
                    </div>
                    <div>
                        <label class="erp-label">Duración (segundos)</label>
                        <input
                            class="erp-input"
                            wire:model="duration"
                            type="number"
                            placeholder="Ej. 420"
                        >
                        @error('duration')
                            <x-input-error :messages="$message" />
                        @enderror
                    </div>
                    <div
                        x-data="{ uploading: false, progress: 0 }"
                        x-on:livewire-upload-start="uploading = true"
                        x-on:livewire-upload-finish="uploading = false; progress = 100"
                        x-on:livewire-upload-error="uploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress"
                    >
                        <label class="erp-label">Archivo de video</label>
                        <input
                            class="erp-input file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:font-semibold file:text-indigo-700"
                            wire:model="videoFile"
                            type="file"
                            accept=".mp4,.webm,.mov,video/mp4,video/webm,video/quicktime"
                        >
                        <p class="mt-1 text-xs text-slate-500">
                            Formatos permitidos: MP4, WebM o MOV. Máximo 512 MB.
                        </p>
                        @error('videoFile')
                            <x-input-error :messages="$message" />
                        @enderror
                        <div x-show="uploading" x-cloak class="mt-3">
                            <div class="mb-1 flex justify-between text-xs font-semibold text-indigo-700">
                                <span>Subiendo video...</span>
                                <span x-text="`${progress}%`"></span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-indigo-100">
                                <div
                                    class="h-full rounded-full bg-indigo-600 transition-all"
                                    :style="`width: ${progress}%`"
                                ></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <input class="erp-checkbox" type="checkbox" wire:model="isPreview">
                            Video de muestra gratis
                        </label>
                        <button
                            class="erp-button-primary"
                            wire:loading.attr="disabled"
                            wire:target="videoFile,saveLesson"
                        >
                            <i class="bi bi-check2"></i>
                            <span wire:loading.remove wire:target="videoFile,saveLesson">Guardar video</span>
                            <span wire:loading wire:target="videoFile,saveLesson">Procesando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    @endif
</div>
