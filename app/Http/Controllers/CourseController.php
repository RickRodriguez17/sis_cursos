<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CourseController extends Controller
{
    public function index()
    {
        return view('courses.index', [
            'courses' => Course::where('is_published', true)
                ->withCount('lessons')
                ->latest()
                ->get(),
        ]);
    }

    public function show(Course $course)
    {
        abort_unless($course->is_published, 404);
        $enrolled = auth()->check() && $course->users()->whereKey(auth()->id())->exists();

        return view('courses.show', compact('course', 'enrolled'));
    }

    public function video(Course $course, Lesson $lesson)
    {
        $this->authorizeLesson($course, $lesson);
        abort_unless($lesson->video_path, 404);

        return view('courses.lesson', [
            'course' => $course,
            'lesson' => $lesson,
            'lessons' => $course->lessons()->orderBy('sort_order')->get(),
        ]);
    }

    public function stream(Request $request, Course $course, Lesson $lesson): StreamedResponse
    {
        $this->authorizeLesson($course, $lesson);
        abort_unless($lesson->video_path, 404);

        $disk = Storage::disk('local');
        $path = $lesson->video_path;
        $size = $disk->size($path);
        $mime = $disk->mimeType($path) ?: 'application/octet-stream';
        $range = $request->header('Range');

        if (! $range) {
            return $this->streamFile($disk->path($path), $mime, $size, 200);
        }

        if (! preg_match('/bytes=(\d*)-(\d*)/', $range, $matches)) {
            return $this->rangeNotSatisfiable($size);
        }

        $start = $matches[1] === '' ? null : (int) $matches[1];
        $end = $matches[2] === '' ? null : (int) $matches[2];

        if ($start === null) {
            $suffixLength = $end ?? 0;
            if ($suffixLength < 1) {
                return $this->rangeNotSatisfiable($size);
            }
            $start = max(0, $size - $suffixLength);
            $end = $size - 1;
        } else {
            $end ??= $size - 1;
        }

        if ($start < 0 || $start >= $size || $end < $start || $end >= $size) {
            return $this->rangeNotSatisfiable($size);
        }

        return $this->streamFile(
            $disk->path($path),
            $mime,
            $end - $start + 1,
            206,
            $start,
            $end,
            $size,
        );
    }

    private function authorizeLesson(Course $course, Lesson $lesson): void
    {
        abort_unless($lesson->course_id === $course->id, 404);
        $isAdmin = auth()->user()?->is_admin === true;
        abort_unless($course->is_published || $isAdmin, 403);

        $allowed = $isAdmin
            || $lesson->is_preview
            || (auth()->check() && $course->users()->whereKey(auth()->id())->exists());

        abort_unless($allowed, 403);
    }

    private function streamFile(
        string $path,
        string $mime,
        int $length,
        int $status,
        ?int $start = null,
        ?int $end = null,
        ?int $total = null,
    ): StreamedResponse {
        $headers = [
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'private, no-store',
            'Content-Length' => (string) $length,
            'Content-Type' => $mime,
        ];

        if ($status === 206) {
            $headers['Content-Range'] = "bytes {$start}-{$end}/{$total}";
        }

        return response()->stream(function () use ($path, $length, $start): void {
            $handle = fopen($path, 'rb');
            if ($handle === false) {
                return;
            }

            if ($start !== null) {
                fseek($handle, $start);
            }

            $remaining = $length;
            while ($remaining > 0 && ! feof($handle)) {
                $chunk = fread($handle, min(1024 * 1024, $remaining));
                if ($chunk === false || $chunk === '') {
                    break;
                }
                echo $chunk;
                $remaining -= strlen($chunk);
            }

            fclose($handle);
        }, $status, $headers);
    }

    private function rangeNotSatisfiable(int $size): StreamedResponse
    {
        return response()->stream(static function (): void {}, 416, [
            'Accept-Ranges' => 'bytes',
            'Content-Range' => "bytes */{$size}",
            'Content-Length' => '0',
        ]);
    }
}
