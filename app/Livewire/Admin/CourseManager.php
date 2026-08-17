<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class CourseManager extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $courseId): void
    {
        $course = Course::with('lessons')->findOrFail($courseId);
        $course->lessons->each->delete();
        $course->delete();
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
            'courses' => Course::withCount('lessons')
                ->when($this->search, function ($query) {
                    $query->where(function ($query) {
                        $query->where('title', 'like', '%'.$this->search.'%')
                            ->orWhere('slug', 'like', '%'.$this->search.'%');
                    });
                })
                ->latest()
                ->paginate(15),
        ]);
    }
}
