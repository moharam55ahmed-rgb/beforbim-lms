<?php

namespace App\Modules\Certificate\Models;

use App\Models\User;
use App\Modules\Course\Models\Course;
use App\Modules\Enrollment\Models\Enrollment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    protected $fillable = [
        'uuid',
        'certificate_number',
        'verification_code',
        'user_id',
        'course_id',
        'template_id',
        'enrollment_id',
        'student_name_snapshot',
        'course_title_snapshot_ar',
        'course_title_snapshot_en',
        'instructor_name_snapshot',
        'grade_percentage',
        'issued_at',
        'status', // active, revoked, expired
        'pdf_storage_path',
        'qr_verification_url',
        'is_revoked',
        'revocation_reason',
    ];

    protected function casts(): array
    {
        return [
            'grade_percentage' => 'decimal:2',
            'issued_at' => 'datetime',
            'is_revoked' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Certificate $cert) {
            if (empty($cert->uuid)) {
                $cert->uuid = (string) Str::uuid();
            }
            if (empty($cert->certificate_number)) {
                $cert->certificate_number = 'BFB-CERT-' . date('Y') . '-' . strtoupper(Str::random(8));
            }
            if (empty($cert->verification_code)) {
                $cert->verification_code = strtoupper(Str::random(12));
            }
            if (empty($cert->status)) {
                $cert->status = 'active';
            }
            if (empty($cert->issued_at)) {
                $cert->issued_at = now();
            }
            if (empty($cert->qr_verification_url)) {
                $cert->qr_verification_url = config('app.url') . '/verify/' . $cert->verification_code;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class, 'template_id');
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && ! $this->is_revoked;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')->where('is_revoked', false);
    }
}
