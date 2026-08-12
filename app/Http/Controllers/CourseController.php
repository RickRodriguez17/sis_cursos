<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        return view('courses.index', ['courses' => Course::where('is_published', true)->withCount('lessons')->latest()->get()]);
    }

    public function show(Course $course)
    {
        abort_unless($course->is_published, 404);
        $enrolled = auth()->check() && $course->users()->whereKey(auth()->id())->exists();

        return view('courses.show', compact('course', 'enrolled'));
    }

    public function video(Course $course, Lesson $lesson)
    {
        abort_unless($lesson->course_id === $course->id, 404);
        $allowed = $lesson->is_preview || (auth()->check() && $course->users()->whereKey(auth()->id())->exists());
        abort_unless($allowed, 403);
        if ($lesson->video_type === 'file') {
            abort_unless($lesson->video_path, 404);

            return Storage::disk('local')->response($lesson->video_path);
        }

        return redirect($lesson->video_url);
    }
}
