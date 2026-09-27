<?php

namespace App\Modules\Auth\Services;

use App\Models\User;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\DeviceSession\Models\DeviceSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeviceSessionManager
{
    /**
     * Register a new active device session for the user and revoke existing ones.
     */
    public function registerSession(User $user, Request $request): string
    {
        // 1. Terminate/Revoke existing active sessions for this user (unless super_admin with unlimited devices)
        if (! $user->hasRole('super_admin')) {
            $activeSessions = DeviceSession::where('user_id', $user->id)
                ->where('is_active', true)
                ->get();

            foreach ($activeSessions as $activeSession) {
                $activeSession->update([
                    'is_active' => false,
                    'revoked_at' => now(),
                    'revocation_reason' => 'CONCURRENT_LOGIN_KICK',
                ]);

                AuditLog::log(
                    'DeviceSession',
                    'DEVICE_SESSION_KICKED',
                    $user,
                    $activeSession,
                    ['is_active' => true],
                    ['is_active' => false, 'reason' => 'CONCURRENT_LOGIN_KICK'],
                    'Terminated prior device session due to new login'
                );
            }
        }

        // 2. Generate new unique cryptographic session token
        $rawToken = Str::random(64);
        $tokenHash = hash('sha256', $rawToken);

        $userAgent = $request->userAgent() ?? 'Unknown Browser';
        $ip = $request->ip() ?? '127.0.0.1';

        // 3. Create active device session record
        $session = DeviceSession::create([
            'user_id' => $user->id,
            'session_token_hash' => $tokenHash,
            'ip_address' => $ip,
            'user_agent' => substr($userAgent, 0, 500),
            'device_type' => $this->detectDeviceType($userAgent),
            'browser' => $this->detectBrowser($userAgent),
            'operating_system' => $this->detectOS($userAgent),
            'is_active' => true,
            'last_activity_at' => now(),
        ]);

        // 4. Store raw token in user's Laravel session
        $request->session()->put('beforbim_device_token', $rawToken);

        AuditLog::log('DeviceSession', 'DEVICE_SESSION_CREATED', $user, $session);

        return $rawToken;
    }

    /**
     * Revoke the current active session upon user logout.
     */
    public function revokeCurrentSession(User $user, Request $request): void
    {
        $rawToken = $request->session()->get('beforbim_device_token');

        if ($rawToken) {
            $tokenHash = hash('sha256', $rawToken);

            DeviceSession::where('user_id', $user->id)
                ->where('session_token_hash', $tokenHash)
                ->update([
                    'is_active' => false,
                    'revoked_at' => now(),
                    'revocation_reason' => 'USER_LOGOUT',
                ]);
        }
    }

    private function detectDeviceType(string $userAgent): string
    {
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $userAgent)) {
            return 'TABLET';
        }
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $userAgent)) {
            return 'MOBILE';
        }

        return 'DESKTOP';
    }

    private function detectBrowser(string $userAgent): string
    {
        if (str_contains($userAgent, 'Chrome')) {
            return 'Chrome';
        }
        if (str_contains($userAgent, 'Firefox')) {
            return 'Firefox';
        }
        if (str_contains($userAgent, 'Safari')) {
            return 'Safari';
        }
        if (str_contains($userAgent, 'Edge')) {
            return 'Edge';
        }

        return 'Web Browser';
    }

    private function detectOS(string $userAgent): string
    {
        if (str_contains($userAgent, 'Windows')) {
            return 'Windows';
        }
        if (str_contains($userAgent, 'Mac')) {
            return 'macOS';
        }
        if (str_contains($userAgent, 'Linux')) {
            return 'Linux';
        }
        if (str_contains($userAgent, 'Android')) {
            return 'Android';
        }
        if (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) {
            return 'iOS';
        }

        return 'Unknown OS';
    }
}
