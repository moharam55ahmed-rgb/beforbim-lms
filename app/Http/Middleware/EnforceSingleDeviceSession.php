<?php

namespace App\Http\Middleware;

use App\Modules\DeviceSession\Models\DeviceSession;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforceSingleDeviceSession
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        // Super admins can bypass single device limits for administrative continuity
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        $sessionToken = $request->session()->get('beforbim_device_token');

        if (! $sessionToken) {
            return $next($request);
        }

        $sessionHash = hash('sha256', $sessionToken);

        $deviceSession = DeviceSession::where('user_id', $user->id)
            ->where('session_token_hash', $sessionHash)
            ->first();

        // If session was revoked by a newer login or manual admin kick
        if (! $deviceSession || ! $deviceSession->is_active || $deviceSession->revoked_at !== null) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'device' => 'تم إنهاء جلستك الحالية نظراً لتسجيل الدخول من جهاز آخر أو انتهاء صلاحية الجلسة لحماية المحتوى.',
            ]);
        }

        // Throttle activity updates to once every 60 seconds
        if ($deviceSession->last_activity_at === null || $deviceSession->last_activity_at->diffInSeconds(now()) > 60) {
            $deviceSession->update(['last_activity_at' => now()]);
        }

        return $next($request);
    }
}
