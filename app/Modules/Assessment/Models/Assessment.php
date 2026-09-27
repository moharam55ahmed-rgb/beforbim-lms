<?php

namespace App\Modules\Assessment\Models;

use App\Modules\Course\Models\Course;
use App\Modules\Lesson\Models\Lesson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    protected $fillable = [
        'course_id',
        'lesson_id',
        'title_ar',
        'title_en',
        'description_ar',
        'type',
        'time_limit_minutes',
        'passing_score_percentage',
        'max_attempts',
        'shuffle_questions',
        'shuffle_options',
        'is_proctored_mode',
        'monitor_tab_switch',
        'monitor_fullscreen_exit',
        'max_violations_allowed',
        'requires_manual_audit',
    ];

    protected function casts(): array
    {
        return [
            'time_limit_minutes' => 'integer',
            'passing_score_percentage' => 'decimal:2',
            'max_attempts' => 'integer',
            'shuffle_questions' => 'boolean',
            'shuffle_options' => 'boolean',
            'is_proctored_mode' => 'boolean',
            'monitor_tab_switch' => 'boolean',
            'monitor_fullscreen_exit' => 'boolean',
            'max_violations_allowed' => 'integer',
            'requires_manual_audit' => 'boolean',
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

    public function questions(): HasMany
    {
        return $this->hasMany(AssessmentQuestion::class)->orderBy('order_index');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(AssessmentAttempt::class);
    }
}
