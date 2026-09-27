<x-layouts.base :title="$title ?? 'تسجيل الدخول'">
    <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 bg-[#071A36] relative overflow-hidden">
        <!-- Blueprint Navy Grid Background -->
        <div class="absolute inset-0 bg-blueprint-navy opacity-40 pointer-events-none"></div>

        <!-- Ambient Luxury Gold & Navy Glow -->
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-[#D4AF37]/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-[#123B68]/40 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Brand Identity -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center z-10 mb-8">
            <a href="/" class="inline-flex items-center gap-3.5 group">
                <!-- Geometric Gold & Navy Logo Emblem -->
                <div class="w-13 h-13 rounded-2xl bg-gradient-to-br from-[#123B68] via-[#071A36] to-[#040E1E] p-0.5 shadow-xl shadow-[#040E1E]/50 group-hover:scale-105 transition-transform duration-300 border border-[#D4AF37]/40">
                    <div class="w-full h-full rounded-[14px] bg-[#071A36] flex items-center justify-center">
                        <svg class="w-7 h-7 text-[#D4AF37]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                        </svg>
                    </div>
                </div>
                <div class="text-start">
                    <span class="block text-2xl font-bold tracking-tight text-white font-['Tajawal']">Beforbim</span>
                    <span class="block text-xs font-semibold text-[#D4AF37] tracking-wider">منصة هندسة الـ BIM وإدارة المشروعات</span>
                </div>
            </a>
        </div>

        <!-- Central Card Slot -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md z-10">
            <div class="bg-white py-8 px-6 shadow-2xl rounded-2xl sm:px-10 border border-[#123B68]/30">
                {{ $slot }}
            </div>

            <!-- Footer Meta -->
            <p class="mt-6 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} Beforbim. المنصة الهندسية المعتمدة لمهندسي نمذجة معلومات البناء.
            </p>
        </div>
    </div>
</x-layouts.base>
