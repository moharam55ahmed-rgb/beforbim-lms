<?php

namespace App\Modules\Media\Services;

use App\Models\User;
use App\Modules\Media\Models\LessonContent;
use App\Modules\Media\Models\LessonResource;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class MediaSecurityService
{
    /**
     * Generate a signed temporary streaming URL for video content.
     */
    public function generateSignedStreamUrl(LessonContent $content, User $user, int $validityMinutes = 120): string
    {
        $token = hash_hmac('sha256', "media-{$content->id}-user-{$user->id}-" . now()->format('YmdH'), config('app.key'));

        return route('learn.video_stream', [
            'content' => $content->id,
            'token' => $token,
            'expires' => now()->addMinutes($validityMinutes)->timestamp,
        ]);
    }

    /**
     * Validate video stream token for a user.
     */
    public function validateMediaToken(string $token, int $contentId, int $userId): bool
    {
        $expectedToken = hash_hmac('sha256', "media-{$contentId}-user-{$userId}-" . now()->format('YmdH'), config('app.key'));
        return hash_equals($expectedToken, $token);
    }

    /**
     * Generate a signed temporary download URL for a lesson resource.
     */
    public function generateSignedResourceDownloadUrl(LessonResource $resource, User $user, int $validityMinutes = 60): string
    {
        return URL::temporarySignedRoute(
            'learn.resource_download',
            now()->addMinutes($validityMinutes),
            ['resource' => $resource->id]
        );
    }
}
