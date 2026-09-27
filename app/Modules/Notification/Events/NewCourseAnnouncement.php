<?php

namespace App\Modules\Notification\Events;

use App\Modules\CourseAnnouncement\Models\CourseAnnouncement;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewCourseAnnouncement
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public CourseAnnouncement $announcement
    ) {}
}
