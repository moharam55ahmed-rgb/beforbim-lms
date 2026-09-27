<?php

namespace App\Modules\CourseDiscussion\Models;

use App\Models\User;
use App\Modules\Course\Models\Course;
use App\Modules\Lesson\Models\Lesson;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseDiscussion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id',
        'user_id',
        'lesson_id',
        'parent_id',
        'message',
        'status',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CourseDiscussion::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(CourseDiscussion::class, 'parent_id')->orderBy('created_at');
    }

    public function scopeRootQuestions(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }
}
