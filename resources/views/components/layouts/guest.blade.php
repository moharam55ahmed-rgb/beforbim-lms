<x-layouts.base :title="$title ?? 'Account Access — Beforbim'">
    <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50 dark:bg-[#070F1E] relative overflow-hidden transition-colors duration-200">
        <!-- Blueprint Grid Background -->
        <div class="absolute inset-0 bg-blueprint-navy opacity-10 dark:opacity-40 pointer-events-none"></div>

        <!-- Ambient Luxury Gold & Navy Glow -->
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-[#D4AF37]/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-blue-500/10 dark:bg-[#123B68]/40 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Top Controls: Home & Theme Toggle -->
        <div class="absolute top-6 right-6 z-20 flex items-center gap-3">
            <!-- Theme Toggle Button -->
            <button 
                type="button" 
                @click="toggleTheme()" 
                class="p-2 rounded-xl border border-slate-200 dark:border-white/10 bg-white/80 dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10 text-slate-700 dark:text-slate-200 shadow-sm transition"
                title="Toggle Light / Dark Mode"
                aria-label="Toggle Theme"
            >
                <template x-if="theme === 'dark'">
                    <svg class="w-4 h-4 text-[#F3D98B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </template>
                <template x-if="theme === 'light'">
                    <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                </template>
            </button>

            <a href="{{ route('home') }}" class="px-3.5 py-1.5 rounded-xl border border-slate-200 dark:border-white/10 bg-white/80 dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10 text-xs font-semibold text-slate-700 dark:text-slate-200 transition">
                &larr; Back to Home
            </a>
        </div>

        <!-- Brand Identity -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center z-10 mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3.5 group">
                <div class="p-1 rounded-2xl bg-[#071A36] border border-[#D4AF37]/50 shadow-xl shadow-[#D4AF37]/15 group-hover:scale-105 transition-transform duration-300">
                    <img src="{{ asset('images/branding/logo.png') }}" alt="Beforbim Logo" class="h-10 w-auto object-contain">
                </div>
                <div class="text-left">
                    <span class="block text-2xl font-black tracking-wider text-slate-900 dark:text-white font-['Outfit']">
                        BEFOR<span class="text-[#D4AF37]">BIM</span>
                    </span>
                    <span class="block text-[10px] font-semibold text-[#D4AF37] tracking-widest font-mono uppercase">
                        BIM Engineering Academy
                    </span>
                </div>
            </a>
        </div>

        <!-- Central Card Slot -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md z-10 w-full">
            <div class="bg-white dark:bg-[#071A36]/90 py-8 px-6 shadow-2xl rounded-3xl sm:px-10 border border-slate-200 dark:border-white/10 backdrop-blur-xl transition-colors duration-200">
                {{ $slot }}
            </div>

            <!-- Footer Meta -->
            <p class="mt-6 text-center text-xs text-slate-500 dark:text-slate-400">
                &copy; {{ date('Y') }} Beforbim Engineering Education Ltd. Based in Cairo, Egypt.
            </p>
        </div>
    </div>
</x-layouts.base>
