<?php

namespace App\Modules\Assignment\Models;

use App\Modules\Course\Models\Course;
use App\Modules\Lesson\Models\Lesson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $fillable = [
        'course_id',
        'lesson_id',
        'title_ar',
        'title_en',
        'instructions_ar',
        'total_points',
        'due_date',
        'allowed_file_types',
        'max_file_size_mb',
    ];

    protected function casts(): array
    {
        return [
            'total_points' => 'decimal:2',
            'due_date' => 'datetime',
            'allowed_file_types' => 'array',
            'max_file_size_mb' => 'integer',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function rubrics(): HasMany
    {
        return $this->hasMany(AssignmentRubric::class);
    }
}
