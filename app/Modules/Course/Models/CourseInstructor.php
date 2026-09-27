<?php

namespace App\Modules\Course\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseInstructor extends Model
{
    protected $fillable = [
        'course_id',
        'user_id',
        'role',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isMain(): bool
    {
        return $this->role === 'main_instructor';
    }

    public function isAssistant(): bool
    {
        return $this->role === 'assistant_instructor';
    }
}
