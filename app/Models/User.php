<?php

namespace App\Models;

use App\Modules\AccessControl\Traits\HasRolesAndPermissions;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Models\Wishlist;
use App\Modules\CourseAnnouncement\Models\CourseAnnouncement;
use App\Modules\CourseDiscussion\Models\CourseDiscussion;
use App\Modules\CourseReview\Models\CourseReview;
use App\Modules\DeviceSession\Models\DeviceSession;
use App\Modules\Enrollment\Models\Enrollment;
use App\Modules\Media\Traits\HasMedia;
use App\Modules\Order\Models\Order;
use App\Modules\SupportTicket\Models\SupportTicket;
use App\Modules\User\Models\InstructorProfile;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasMedia, HasRolesAndPermissions, Notifiable, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'phone',
        'phone_country_code',
        'phone_number',
        'avatar',
        'avatar_url',
        'engineering_title',
        'bio',
        'status',
        'max_allowed_devices',
        'phone_verified_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
            if (empty($user->status)) {
                $user->status = 'active';
            }
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'max_allowed_devices' => 'integer',
        ];
    }

    public function instructorProfile(): HasOne
    {
        return $this->hasOne(InstructorProfile::class);
    }

    public function deviceSessions(): HasMany
    {
        return $this->hasMany(DeviceSession::class);
    }

    public function activeDeviceSession()
    {
        return $this->hasOne(DeviceSession::class)->where('is_active', true)->latestOfMany();
    }

    public function authoredCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    public function courses(): HasMany
    {
        return $this->authoredCourses();
    }

    public function coAuthoredCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_instructors')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CourseReview::class, 'student_id');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(CourseAnnouncement::class, 'instructor_id');
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(CourseDiscussion::class);
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'actor_id');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'wishlists')->withTimestamps();
    }

    public function isEnrolledIn(Course|int $course): bool
    {
        $courseId = $course instanceof Course ? $course->id : $course;

        return $this->enrollments()
            ->where('course_id', $courseId)
            ->where('status', 'ACTIVE')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    public function isApprovedInstructor(): bool
    {
        return $this->hasRole('instructor') &&
            $this->instructorProfile !== null &&
            $this->instructorProfile->isApproved();
    }

    public function suspend(?string $reason = null): void
    {
        $this->update(['status' => 'suspended']);
        AuditLog::log('User', 'ACCOUNT_SUSPENDED', auth()->user(), $this, null, ['status' => 'suspended'], $reason);
    }

    public function unblock(): void
    {
        $this->update(['status' => 'active']);
        AuditLog::log('User', 'ACCOUNT_UNBLOCKED', auth()->user(), $this, null, ['status' => 'active'], 'Account unblocked');
    }

    public function recordLogin(): void
    {
        $this->update(['last_login_at' => now()]);
        AuditLog::log('Auth', 'USER_LOGIN', $this, $this, null, ['last_login_at' => now()]);
    }
}
