<?php

namespace App\Modules\Lesson\Models;

use App\Modules\Curriculum\Models\CourseSection;
use App\Modules\Media\Models\LessonContent;
use App\Modules\Media\Models\LessonResource;
use App\Modules\Progress\Models\LessonProgress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lesson extends Model
{
    protected $fillable = [
        'section_id',
        'title_ar',
        'title_en',
        'lesson_type',
        'duration_seconds',
        'order_index',
        'is_preview_free',
        'is_preview',
        'is_mandatory',
    ];

    protected function casts(): array
    {
        return [
            'duration_seconds' => 'integer',
            'order_index' => 'integer',
            'is_preview_free' => 'boolean',
            'is_preview' => 'boolean',
            'is_mandatory' => 'boolean',
        ];
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    public function content(): HasOne
    {
        return $this->hasOne(LessonContent::class);
    }

    public function contents(): HasMany
    {
        return $this->hasMany(LessonContent::class)->orderBy('ordering');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(LessonResource::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function canPreview(): bool
    {
        $course = $this->section?->course;
        $courseAllowsPreview = $course ? $course->preview_enabled : true;

        return ($this->is_preview || $this->is_preview_free) && $courseAllowsPreview;
    }
}
