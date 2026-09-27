<x-layouts.guest title="Reset Password — Beforbim Academy">
    <div class="mb-6 text-center space-y-1">
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white font-['Outfit']">Reset Password</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400">Enter your registered engineer email and we will send you a password reset link</p>
    </div>

    @if (session('status'))
        <x-alert type="success" class="mb-4">
            {{ session('status') }}
        </x-alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <x-input
            label="Work Email Address"
            name="email"
            type="email"
            value="{{ old('email') }}"
            placeholder="engineer@domain.com"
            required
            autofocus
        />

        <div class="pt-2">
            <x-button type="submit" variant="gold" class="w-full py-3 shadow-lg shadow-[#D4AF37]/20">
                Send Password Reset Link
            </x-button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-slate-100 dark:border-white/10 text-center text-xs text-slate-600 dark:text-slate-400">
        Remembered your credentials?
        <a href="{{ route('login') }}" class="text-[#B38F24] dark:text-[#F3D98B] font-bold hover:underline ms-1">
            Back to Sign In &rarr;
        </a>
    </div>
</x-layouts.guest>
