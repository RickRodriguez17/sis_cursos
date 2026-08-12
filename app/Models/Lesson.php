<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['course_id', 'title', 'description', 'sort_order', 'video_type', 'video_url', 'video_path', 'duration', 'is_preview'];

    protected $casts = ['is_preview' => 'boolean'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
