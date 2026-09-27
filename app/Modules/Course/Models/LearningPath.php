<?php

namespace App\Modules\Course\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class LearningPath extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'description',
        'image',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (LearningPath $path) {
            if (empty($path->slug)) {
                $path->slug = Str::slug($path->title) . '-' . Str::random(5);
            }
        });
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'learning_path_courses')
            ->withPivot('order')
            ->orderByPivot('order')
            ->withTimestamps();
    }

    public function pathCourses(): HasMany
    {
        return $this->hasMany(LearningPathCourse::class)->orderBy('order');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }
}
