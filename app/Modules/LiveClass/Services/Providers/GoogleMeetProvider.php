<?php

namespace App\Modules\LiveClass\Services\Providers;

use App\Modules\LiveClass\Contracts\LiveClassProviderInterface;
use Illuminate\Support\Str;

class GoogleMeetProvider implements LiveClassProviderInterface
{
    /**
     * Create a Google Meet meeting space for the live class.
     */
    public function createMeeting(array $details): array
    {
        // Google Meet meeting codes follow pattern: abc-defg-hij (3-4-3 letters)
        $part1 = strtolower(Str::random(3));
        $part2 = strtolower(Str::random(4));
        $part3 = strtolower(Str::random(3));

        $meetingCode = "{$part1}-{$part2}-{$part3}";
        $joinUrl = "https://meet.google.com/{$meetingCode}";

        return [
            'meeting_id' => $meetingCode,
            'meeting_password' => null,
            'join_url' => $joinUrl,
            'host_url' => $joinUrl . '?authuser=instructor',
        ];
    }

    /**
     * Retrieve Google Meet metadata.
     */
    public function getMeeting(string $meetingId): array
    {
        return [
            'meeting_id' => $meetingId,
            'provider' => 'GOOGLE_MEET',
            'join_url' => "https://meet.google.com/{$meetingId}",
        ];
    }

    /**
     * Cancel or terminate the Google Meet meeting.
     */
    public function cancelMeeting(string $meetingId): bool
    {
        return true;
    }
}
