<?php

namespace App\Modules\LiveClass\Models;

use App\Models\User;
use App\Modules\Course\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LiveClass extends Model
{
    protected $fillable = [
        'course_id',
        'instructor_id',
        'title_ar',
        'title_en',
        'description_ar',
        'provider',
        'meeting_id',
        'meeting_password',
        'join_url_student',
        'host_url_instructor',
        'scheduled_start_time',
        'duration_minutes',
        'status',
        'recording_url',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_start_time' => 'datetime',
            'duration_minutes' => 'integer',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'live_class_attendances')
            ->withPivot('joined_at', 'left_at', 'attended_minutes')
            ->withTimestamps();
    }
}
