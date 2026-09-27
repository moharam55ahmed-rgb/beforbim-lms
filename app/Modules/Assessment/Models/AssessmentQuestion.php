<?php

namespace App\Modules\Assessment\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentQuestion extends Model
{
    protected $fillable = [
        'assessment_id',
        'question_text_ar',
        'question_text_en',
        'question_type',
        'category',
        'difficulty_level', // easy, medium, hard
        'tags',
        'points',
        'options',
        'explanation_ar',
        'order_index',
    ];

    protected function casts(): array
    {
        return [
            'points' => 'decimal:2',
            'options' => 'array',
            'tags' => 'array',
            'order_index' => 'integer',
        ];
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function scopeCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    public function scopeDifficulty(Builder $query, string $difficulty): Builder
    {
        return $query->where('difficulty_level', $difficulty);
    }

    public function scopeTagged(Builder $query, string $tag): Builder
    {
        return $query->whereJsonContains('tags', $tag);
    }
}
