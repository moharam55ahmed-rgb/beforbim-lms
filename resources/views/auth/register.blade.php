<x-layouts.guest title="Student Registration — Beforbim Academy">
    <div class="mb-6 text-center space-y-1">
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white font-['Outfit']">Join the BIM Community</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400">Start your certified ISO 19650 learning tracks and computational modeling</p>
    </div>

    <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
        @csrf

        <x-input
            label="Full Name"
            name="name"
            value="{{ old('name') }}"
            placeholder="Eng. Ahmed Mohamed"
            required
            autofocus
        />

        <x-input
            label="Work / Academic Email"
            name="email"
            type="email"
            value="{{ old('email') }}"
            placeholder="engineer@domain.com"
            required
        />

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <x-input
                label="Mobile Phone (Optional)"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="+20 10 0000 0000"
            />

            <x-input
                label="Engineering Discipline"
                name="engineering_title"
                value="{{ old('engineering_title') }}"
                placeholder="Architectural / Structural / MEP"
            />
        </div>

        <x-input
            label="Password"
            name="password"
            type="password"
            placeholder="Minimum 8 characters"
            required
        />

        <x-input
            label="Confirm Password"
            name="password_confirmation"
            type="password"
            placeholder="Re-enter password"
            required
        />

        <div class="pt-2">
            <x-button type="submit" variant="gold" class="w-full py-3 shadow-lg shadow-[#D4AF37]/20">
                Register & Start Learning
            </x-button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-slate-100 dark:border-white/10 text-center text-xs text-slate-600 dark:text-slate-400">
        Already have an account?
        <a href="{{ route('login') }}" class="text-[#B38F24] dark:text-[#F3D98B] font-bold hover:underline ms-1">
            Sign In &rarr;
        </a>
    </div>
</x-layouts.guest>
