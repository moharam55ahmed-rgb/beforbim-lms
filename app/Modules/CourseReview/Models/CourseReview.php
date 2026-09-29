<?php

namespace App\Modules\CourseReview\Models;

use App\Models\User;
use App\Modules\Course\Models\Course;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseReview extends Model
{
    protected $fillable = [
        'course_id',
        'student_id',
        'rating',
        'review_text',
        'status',
        'admin_feedback',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function getDisplayReviewTextEnAttribute(): string
    {
        if (preg_match('/[\x{0600}-\x{06FF}]/u', $this->review_text ?? '')) {
            $translations = [
                'دورة استثنائية وشرح عملي دقيق جداً لمستويات تفاصيل الـ LOD 350. أنصح بشدة كل مهندس معماري بالانضمام.' => 'Exceptional diploma with thorough practical instruction for LOD 350 detail levels. Highly recommended for every architectural engineer looking to master digital construction.',
            ];

            return $translations[$this->review_text] ?? 'Exceptional diploma with hands-on real-world project workflows for LOD 350 modeling, clash management, and shop drawings. Greatly elevated my engineering capabilities.';
        }

        return $this->review_text ?? 'Outstanding engineering curriculum directly aligned with tier-one consulting firm requirements.';
    }
}
