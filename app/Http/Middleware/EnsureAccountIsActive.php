<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            if ($user->isSuspended()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'status' => 'تم تعليق حسابك مؤقتاً لمراجعة معايير الأمان. يرجى التواصل مع إدارة المنصة.',
                ]);
            }

            if ($user->isBlocked()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'status' => 'تم حظر هذا الحساب لمخالفة سياسات المنصة وحماية حقوق المحتوى.',
                ]);
            }
        }

        return $next($request);
    }
}
