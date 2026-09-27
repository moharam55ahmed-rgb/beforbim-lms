<?php

namespace App\Modules\LiveClass\Services;

use App\Modules\LiveClass\Contracts\LiveClassProviderInterface;
use App\Modules\LiveClass\Services\Providers\GoogleMeetProvider;
use App\Modules\LiveClass\Services\Providers\ZoomMeetingProvider;
use InvalidArgumentException;

class LiveClassProviderManager
{
    /**
     * Resolve provider driver instance.
     */
    public function driver(string $provider = 'ZOOM'): LiveClassProviderInterface
    {
        return match (strtoupper($provider)) {
            'ZOOM' => app(ZoomMeetingProvider::class),
            'GOOGLE_MEET', 'MEET' => app(GoogleMeetProvider::class),
            default => throw new InvalidArgumentException("مزود البث المباشر غير مدعوم: {$provider}"),
        };
    }
}
