<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use Livewire\Component;

class CourseManager extends Component
{
    public function delete(int $courseId): void
    {
        Course::findOrFail($courseId)->delete();
        session()->flash('ok', 'Curso eliminado.');
    }

    public function toggle(int $courseId): void
    {
        $course = Course::findOrFail($courseId);
        $course->update(['is_published' => ! $course->is_published]);
    }

    public function render()
    {
        return view('livewire.admin.course-manager', [
            'courses' => Course::withCount('lessons')->latest()->get(),
        ]);
    }
}
