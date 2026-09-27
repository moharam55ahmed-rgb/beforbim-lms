<?php

namespace App\Modules\Notification\Events;

use App\Modules\Assignment\Models\AssignmentSubmission;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssignmentGraded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public AssignmentSubmission $submission
    ) {}
}
