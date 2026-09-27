<?php

namespace App\Modules\Assessment\Models;

use App\Models\User;
use App\Modules\ExamSecurity\Models\ExamSecurityEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentAttempt extends Model
{
    protected $fillable = [
        'assessment_id',
        'user_id',
        'attempt_number',
        'total_points_possible',
        'total_points_earned',
        'score_percentage',
        'passed',
        'status',
        'anti_cheat_violations_count',
        'audit_notes',
        'audited_by_user_id',
        'started_at',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'attempt_number' => 'integer',
            'total_points_possible' => 'decimal:2',
            'total_points_earned' => 'decimal:2',
            'score_percentage' => 'decimal:2',
            'passed' => 'boolean',
            'anti_cheat_violations_count' => 'integer',
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'audited_by_user_id');
    }

    public function securityEvents(): HasMany
    {
        return $this->hasMany(ExamSecurityEvent::class, 'attempt_id');
    }
}
