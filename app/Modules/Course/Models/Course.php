<?php

namespace App\Modules\Course\Models;

use App\Models\User;
use App\Modules\Assessment\Models\Assessment;
use App\Modules\Assignment\Models\Assignment;
use App\Modules\Cart\Models\Coupon;
use App\Modules\Category\Models\Category;
use App\Modules\Certificate\Models\Certificate;
use App\Modules\CourseAnnouncement\Models\CourseAnnouncement;
use App\Modules\CourseDiscussion\Models\CourseDiscussion;
use App\Modules\CourseReview\Models\CourseReview;
use App\Modules\Curriculum\Models\CourseSection;
use App\Modules\Enrollment\Models\Enrollment;
use App\Modules\Lesson\Models\Lesson;
use App\Modules\LiveClass\Models\LiveClass;
use App\Modules\Media\Traits\HasMedia;
use App\Modules\User\Models\InstructorProfile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasMedia, SoftDeletes;

    protected $fillable = [
        'uuid',
        'instructor_id',
        'category_id',
        'title_ar',
        'title_en',
        'slug',
        'short_description_ar',
        'description_ar',
        'level',
        'price',
        'sale_price',
        'currency',
        'thumbnail_url',
        'promo_video_url',
        'software_requirements',
        'prerequisites',
        'learning_outcomes',
        'status',
        'preview_enabled',
        'preview_description',
        'meta_title_ar',
        'meta_title_en',
        'meta_description_ar',
        'meta_description_en',
        'meta_keywords',
        'rejection_feedback',
        'submitted_at',
        'approved_at',
        'approved_by_user_id',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'preview_enabled' => 'boolean',
            'meta_keywords' => 'array',
            'software_requirements' => 'array',
            'prerequisites' => 'array',
            'learning_outcomes' => 'array',
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            if (empty($course->uuid)) {
                $course->uuid = (string) Str::uuid();
            }
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title_en ?: $course->title_ar).'-'.Str::random(5);
            }
        });
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function courseInstructors(): HasMany
    {
        return $this->hasMany(CourseInstructor::class);
    }

    public function instructors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_instructors')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function assistantInstructors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_instructors')
            ->wherePivot('role', 'assistant_instructor')
            ->withTimestamps();
    }

    public function hasInstructor(User|int $user): bool
    {
        $userId = $user instanceof User ? $user->id : $user;

        if ($this->instructor_id === $userId) {
            return true;
        }

        return $this->courseInstructors()->where('user_id', $userId)->exists();
    }

    public function instructorProfile(): HasOneThrough
    {
        return $this->hasOneThrough(InstructorProfile::class, User::class, 'id', 'user_id', 'instructor_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(CourseSection::class)->orderBy('order_index');
    }

    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(Lesson::class, CourseSection::class, 'course_id', 'section_id')
            ->orderBy('course_sections.order_index')
            ->orderBy('lessons.order_index');
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(CourseRequirement::class)->orderBy('order');
    }

    public function learningPaths(): BelongsToMany
    {
        return $this->belongsToMany(LearningPath::class, 'learning_path_courses')
            ->withPivot('order')
            ->orderByPivot('order')
            ->withTimestamps();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function liveClasses(): HasMany
    {
        return $this->hasMany(LiveClass::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CourseReview::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(CourseReview::class)->where('status', 'approved');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(CourseAnnouncement::class)->orderByDesc('published_at');
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(CourseDiscussion::class);
    }

    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, 'course_coupons')->withTimestamps();
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'APPROVED')->whereNotNull('published_at');
    }

    public function scopeForInstructor(Builder $query, int $instructorId): Builder
    {
        return $query->where('instructor_id', $instructorId);
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->sale_price !== null && $this->sale_price < $this->price ? $this->sale_price : $this->price);
    }

    public function getAverageRatingAttribute(): float
    {
        return (float) round($this->approvedReviews()->avg('rating') ?? 0.0, 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return (int) $this->approvedReviews()->count();
    }
}
