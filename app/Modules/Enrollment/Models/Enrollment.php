<?php

namespace App\Modules\Enrollment\Models;

use App\Models\User;
use App\Modules\Certificate\Models\Certificate;
use App\Modules\Course\Models\Course;
use App\Modules\Order\Models\Order;
use App\Modules\Progress\Models\LessonProgress;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'enrollable_type',
        'enrollable_id',
        'course_id',
        'order_id',
        'source',
        'status',
        'progress_percentage',
        'enrolled_at',
        'completed_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'progress_percentage' => 'decimal:2',
            'enrolled_at' => 'datetime',
            'completed_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enrollable(): MorphTo
    {
        return $this->morphTo();
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(EnrollmentStatusHistory::class)->orderByDesc('created_at');
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'ACTIVE')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isActive(): bool
    {
        return $this->status === 'ACTIVE' && ! $this->isExpired();
    }

    /**
     * Record a lifecycle status change with reason and actor tracking.
     */
    public function recordStatusChange(string $status, ?string $reason = null, ?User $changedBy = null): EnrollmentStatusHistory
    {
        return $this->statusHistories()->create([
            'status' => $status,
            'reason' => $reason,
            'changed_by' => $changedBy?->id ?? auth()->id(),
        ]);
    }
}
