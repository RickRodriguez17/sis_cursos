<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'sort_order',
        'video_path',
        'duration',
        'is_preview',
    ];

    protected $casts = ['is_preview' => 'boolean'];

    protected static function booted(): void
    {
        static::deleting(function (Lesson $lesson): void {
            if ($lesson->video_path) {
                Storage::disk('local')->delete($lesson->video_path);
            }
        });
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
