<?php

namespace App\Modules\Notification\Events;

use App\Modules\Course\Models\Course;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CoursePublished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Course $course
    ) {}
}
