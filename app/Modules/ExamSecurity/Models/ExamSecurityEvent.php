<?php

namespace App\Modules\ExamSecurity\Models;

use App\Modules\Assessment\Models\AssessmentAttempt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamSecurityEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'attempt_id',
        'event_type',
        'severity',
        'details',
        'ip_address',
        'occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
        ];
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(AssessmentAttempt::class, 'attempt_id');
    }
}
