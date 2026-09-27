<?php

namespace App\Modules\LiveClass\Contracts;

interface LiveClassProviderInterface
{
    /**
     * Create a new remote live meeting and return credentials & URLs.
     *
     * @param  array  $details  ['topic', 'start_time', 'duration_minutes', 'agenda', 'password']
     * @return array{meeting_id: string, meeting_password?: string|null, join_url: string, host_url?: string|null}
     */
    public function createMeeting(array $details): array;

    /**
     * Retrieve remote meeting metadata.
     */
    public function getMeeting(string $meetingId): array;

    /**
     * Cancel or terminate the remote meeting.
     */
    public function cancelMeeting(string $meetingId): bool;
}
