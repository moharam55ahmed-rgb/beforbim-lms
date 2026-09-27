<?php

namespace App\Modules\Notification\Events;

use App\Modules\Enrollment\Models\Enrollment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EnrollmentApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Enrollment $enrollment
    ) {}
}
