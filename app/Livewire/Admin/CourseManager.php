<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use Livewire\Component;

class CourseManager extends Component
{
    public function delete(int $courseId): void
    {
        Course::findOrFail($courseId)->delete();
        $this->dispatch('toast', message: 'Curso eliminado.', type: 'success');
    }

    public function toggle(int $courseId): void
    {
        $course = Course::findOrFail($courseId);
        $course->update(['is_published' => ! $course->is_published]);
        $this->dispatch('toast', message: $course->is_published ? 'Curso publicado.' : 'Curso despublicado.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin.course-manager', [
            'courses' => Course::withCount('lessons')->latest()->get(),
        ]);
    }
}
