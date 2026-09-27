<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\Auth\Services\DeviceSessionManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        protected DeviceSessionManager $deviceManager
    ) {}

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        if ($user->isBlocked()) {
            throw ValidationException::withMessages([
                'email' => 'هذا الحساب محظور نظراً لمخالفة سياسات المنصة. يرجى التواصل مع الدعم الفني.',
            ]);
        }

        if ($user->isSuspended()) {
            throw ValidationException::withMessages([
                'email' => 'تم تعليق هذا الحساب مؤقتاً لمراجعة معايير الأمان. يرجى مراجعة البريد الإلكتروني.',
            ]);
        }

        // Login user
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Register active device session & revoke others
        $this->deviceManager->registerSession($user, $request);

        // Record user login and audit trail
        $user->recordLogin();

        // Redirect based on primary role
        if ($user->hasRole(['super_admin', 'admin'])) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->hasRole('instructor')) {
            return redirect()->intended(route('instructor.dashboard'));
        }

        return redirect()->intended(route('student.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            $this->deviceManager->revokeCurrentSession($user, $request);
            AuditLog::log('Auth', 'USER_LOGOUT', $user, $user);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
