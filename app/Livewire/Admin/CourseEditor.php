<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class CourseEditor extends Component
{
    use WithFileUploads;

    public ?Course $course = null;

    public string $title = '';

    public string $slug = '';

    public string $shortDescription = '';

    public string $description = '';

    public string $price = '';

    public bool $isPublished = false;

    public $image;

    public ?int $editingLessonId = null;

    public string $lessonTitle = '';

    public string $lessonDescription = '';

    public $videoFile;

    public string $duration = '';

    public bool $isPreview = false;

    public function mount(?Course $course = null): void
    {
        if (! $course?->exists) {
            return;
        }

        $this->course = $course;
        $this->title = $course->title;
        $this->slug = $course->slug;
        $this->shortDescription = $course->short_description;
        $this->description = $course->description;
        $this->price = (string) $course->price;
        $this->isPublished = $course->is_published;
    }

    public function saveCourse(): void
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash', 'max:255', Rule::unique('courses', 'slug')->ignore($this->course?->id)],
            'shortDescription' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'isPublished' => ['boolean'],
        ]);

        $course = $this->course ?: new Course;
        $course->fill([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'short_description' => $data['shortDescription'],
            'description' => $data['description'],
            'price' => $data['price'],
            'is_published' => $data['isPublished'],
        ]);

        if ($this->image) {
            $course->image_path = $this->image->store('course-images', 'public');
        }

        $course->save();
        $this->course = $course;
        $this->dispatch('toast', message: 'Curso guardado correctamente.', type: 'success');
    }

    public function editLesson(int $lessonId): void
    {
        $lesson = $this->course?->lessons()->findOrFail($lessonId);
        $this->editingLessonId = $lesson->id;
        $this->lessonTitle = $lesson->title;
        $this->lessonDescription = (string) $lesson->description;
        $this->duration = (string) $lesson->duration;
        $this->isPreview = $lesson->is_preview;
    }

    public function saveLesson(): void
    {
        abort_unless($this->course?->exists, 422);

        $rules = [
            'lessonTitle' => ['required', 'string', 'max:255'],
            'lessonDescription' => ['nullable', 'string'],
            'videoFile' => [
                $this->editingLessonId ? 'nullable' : 'required',
                'file',
                'mimes:mp4,webm,mov',
                'mimetypes:video/mp4,video/webm,video/quicktime',
                'max:512000',
            ],
            'duration' => ['nullable', 'integer', 'min:0'],
            'isPreview' => ['boolean'],
        ];
        $data = $this->validate($rules, [
            'videoFile.required' => 'Debes subir un archivo de video para crear la lección.',
            'videoFile.max' => 'El video no puede superar los 512 MB.',
            'videoFile.mimetypes' => 'El archivo debe ser un video MP4, WebM o MOV válido.',
        ]);

        $lesson = $this->editingLessonId
            ? $this->course->lessons()->findOrFail($this->editingLessonId)
            : new Lesson(['course_id' => $this->course->id, 'sort_order' => $this->course->lessons()->max('sort_order') + 1]);
        $previousPath = $lesson->video_path;

        $lesson->fill([
            'title' => $data['lessonTitle'],
            'description' => $data['lessonDescription'] ?? null,
            'duration' => $data['duration'] ?? null,
            'is_preview' => $data['isPreview'],
        ]);

        if ($this->videoFile) {
            $lesson->video_path = $this->videoFile->store('course-videos', 'local');
        }

        $lesson->save();
        if ($this->videoFile && $previousPath && $previousPath !== $lesson->video_path) {
            Storage::disk('local')->delete($previousPath);
        }
        $this->resetLessonForm();
        $this->dispatch('toast', message: 'Video guardado correctamente.', type: 'success');
    }

    public function deleteLesson(int $lessonId): void
    {
        $this->course?->lessons()->findOrFail($lessonId)->delete();
        $this->dispatch('toast', message: 'Video eliminado.', type: 'success');
    }

    public function moveLesson(int $lessonId, string $direction): void
    {
        $lessons = $this->course?->lessons()->orderBy('sort_order')->get() ?? collect();
        $index = $lessons->search(fn (Lesson $lesson) => $lesson->id === $lessonId);
        $swap = $direction === 'up' ? $index - 1 : $index + 1;

        if ($index === false || ! isset($lessons[$swap])) {
            return;
        }

        [$lessons[$index]->sort_order, $lessons[$swap]->sort_order] = [$lessons[$swap]->sort_order, $lessons[$index]->sort_order];
        $lessons[$index]->save();
        $lessons[$swap]->save();
    }

    private function resetLessonForm(): void
    {
        $this->reset(['editingLessonId', 'lessonTitle', 'lessonDescription', 'videoFile', 'duration', 'isPreview']);
    }

    public function render()
    {
        return view('livewire.admin.course-editor', [
            'lessons' => $this->course?->lessons()->get() ?? collect(),
        ]);
    }
}
