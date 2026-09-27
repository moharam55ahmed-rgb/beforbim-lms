<?php

namespace App\Modules\User\Models;

use App\Models\User;
use App\Modules\Course\Models\Course;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstructorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'specialization',
        'experience_years',
        'education',
        'certifications',
        'linkedin_url',
        'website_url',
        'profile_image',
        'profile_status',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'experience_years' => 'integer',
            'certifications' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id', 'user_id');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('profile_status', 'approved');
    }

    public function isApproved(): bool
    {
        return $this->profile_status === 'approved';
    }
}
