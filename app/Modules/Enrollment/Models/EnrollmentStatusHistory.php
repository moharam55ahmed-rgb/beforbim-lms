<?php

namespace App\Modules\Enrollment\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnrollmentStatusHistory extends Model
{
    protected $table = 'enrollment_status_history';

    protected $fillable = [
        'enrollment_id',
        'status',
        'reason',
        'changed_by',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
