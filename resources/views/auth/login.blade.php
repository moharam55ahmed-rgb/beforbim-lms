<x-layouts.guest title="Engineer Sign In — Beforbim Academy">
    <div class="mb-6 text-center space-y-1">
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white font-['Outfit']">Welcome Back</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400">Sign in to access your BIM masterclasses and engineering datasets</p>
    </div>

    @if (session('status'))
        <x-alert type="success" class="mb-4">
            {{ session('status') }}
        </x-alert>
    @endif

    @if ($errors->has('device') || $errors->has('status'))
        <x-alert type="danger" class="mb-4">
            {{ $errors->first('device') ?: $errors->first('status') }}
        </x-alert>
    @endif

    <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
        @csrf

        <x-input
            label="Work / Professional Email"
            name="email"
            type="email"
            value="{{ old('email') }}"
            placeholder="engineer@example.com"
            required
            autofocus
        />

        <x-input
            label="Password"
            name="password"
            type="password"
            placeholder="••••••••"
            required
        />

        <div class="flex items-center justify-between text-xs pt-1">
            <label class="flex items-center gap-2 cursor-pointer select-none text-slate-600 dark:text-slate-300">
                <input type="checkbox" name="remember" class="rounded border-slate-300 dark:border-white/20 text-[#D4AF37] focus:ring-[#D4AF37]">
                <span>Remember on this workstation</span>
            </label>

            <a href="{{ route('password.request') }}" class="text-[#B38F24] dark:text-[#F3D98B] hover:underline font-medium">
                Forgot password?
            </a>
        </div>

        <div class="pt-2">
            <x-button type="submit" variant="gold" class="w-full py-3 shadow-lg shadow-[#D4AF37]/20">
                <span>Sign In to Platform</span>
                <span class="sr-only">دخول إلى المنصة</span>
            </x-button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-slate-100 dark:border-white/10 text-center text-xs text-slate-600 dark:text-slate-400">
        New engineer to Beforbim?
        <a href="{{ route('register') }}" class="text-[#B38F24] dark:text-[#F3D98B] font-bold hover:underline ms-1">
            Create Student Account &rarr;
        </a>
    </div>
</x-layouts.guest>
