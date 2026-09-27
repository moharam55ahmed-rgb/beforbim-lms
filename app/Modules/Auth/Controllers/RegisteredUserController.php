<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\Auth\Services\DeviceSessionManager;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(
        protected DeviceSessionManager $deviceManager
    ) {}

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:191', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'engineering_title' => ['nullable', 'string', 'max:120'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'engineering_title' => $validated['engineering_title'] ?? null,
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        // Default role is student
        $user->assignRole('student');

        event(new Registered($user));

        AuditLog::log('Auth', 'USER_REGISTERED', $user, $user);

        Auth::login($user);
        $request->session()->regenerate();

        $this->deviceManager->registerSession($user, $request);

        return redirect()->route('student.dashboard');
    }
}
