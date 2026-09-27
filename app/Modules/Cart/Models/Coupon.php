<?php

namespace App\Modules\Cart\Models;

use App\Modules\Course\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type', // percentage, fixed
        'value',
        'max_discount',
        'start_date',
        'end_date',
        'usage_limit',
        'used_count',
        'status', // active, inactive
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
        ];
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_coupons')->withTimestamps();
    }

    public function isValid(?Course $course = null): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->start_date && $this->start_date->isFuture()) {
            return false;
        }

        if ($this->end_date && $this->end_date->isPast()) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        // If coupon is tied to specific courses
        if ($course && $this->courses()->exists() && ! $this->courses()->where('courses.id', $course->id)->exists()) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $amount): float
    {
        if ($this->type === 'percentage') {
            $discount = ($amount * (float) $this->value) / 100;
        } else {
            $discount = (float) $this->value;
        }

        if ($this->max_discount !== null && $discount > (float) $this->max_discount) {
            $discount = (float) $this->max_discount;
        }

        return min($amount, round($discount, 2));
    }
}
