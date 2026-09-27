<x-layouts.base 
    :title="'About Beforbim Academy — Empowering Digital Engineering & BIM Excellence'"
    :description="'Learn about Beforbim Engineering Academy, Cairo-headquartered leader in BIM education, ISO 19650 standards, and construction technology.'"
>
    <x-public-header />

    <!-- Hero Header -->
    <section class="relative bg-gradient-to-b from-slate-950 via-[#071A36] to-[#040E1E] text-white py-16 lg:py-20 overflow-hidden border-b border-slate-200 dark:border-white/10">
        <div class="absolute inset-0 bg-blueprint-navy opacity-30 pointer-events-none"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#D4AF37]/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-[#D4AF37]/15 text-[#F3D98B] border border-[#D4AF37]/30">
                <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-pulse"></span>
                <span>Headquartered in Cairo, Egypt • ISO 19650 Aligned</span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-black font-['Outfit'] text-white tracking-tight">
                Empowering Engineers Through <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#F3D98B] via-[#D4AF37] to-amber-200">Digital Construction</span>
            </h1>

            <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto leading-relaxed font-light">
                {{ $cms->get('about_hero_sub', 'Beforbim is an engineering academy committed to upskilling architects, structural engineers, and MEP specialists into certified BIM Managers and computational designers.') }}
            </p>
        </div>
    </section>

    <!-- Main Content (Light & Dark Responsive) -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 flex-1 w-full space-y-16">
        <!-- Pillars Grid (Mission, Vision, Values) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Mission -->
            <div class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-4 hover:border-[#D4AF37] transition">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-[#040E1E] text-[#D4AF37] flex items-center justify-center font-bold text-xl border border-slate-200 dark:border-white/10 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white font-['Outfit']">Our Academic Mission</h3>
                    <span class="sr-only">رسالتنا الأكاديمية</span>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-light">
                    {{ $cms->get('about_mission', 'Bridging the divide between conceptual engineering studies and field execution through production-grade model drafting, clash resolution, and computational design algorithms.') }}
                </p>
            </div>

            <!-- Vision -->
            <div class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-4 hover:border-[#D4AF37] transition">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-[#040E1E] text-[#96720D] dark:text-[#F3D98B] flex items-center justify-center font-bold text-xl border border-slate-200 dark:border-white/10 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white font-['Outfit']">Our Strategic Vision</h3>
                    <span class="sr-only">رؤيتنا المستقبلية</span>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-light">
                    {{ $cms->get('about_vision', 'To stand as Egypt and the region’s premier accredited destination for certifying BIM Managers, 4D/5D simulation experts, and smart infrastructure engineers.') }}
                </p>
            </div>

            <!-- Values -->
            <div class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-4 hover:border-[#D4AF37] transition">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-[#040E1E] text-[#D4AF37] flex items-center justify-center font-bold text-xl border border-slate-200 dark:border-white/10 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white font-['Outfit']">Engineering Values</h3>
                    <span class="sr-only">قيمنا ومعاييرنا الهندسية</span>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-light">
                    {{ $cms->get('about_values', 'Precision, international compliance (ISO 19650), transparency, and continuous mentorship until every engineer achieves certified practical mastery.') }}
                </p>
            </div>
        </div>

        <!-- Methodology Section -->
        <div class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-8 sm:p-12 shadow-sm dark:shadow-xl backdrop-blur-md space-y-8">
            <div class="max-w-2xl space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-[#96720D] dark:text-[#D4AF37]">Academic Framework</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-['Outfit']">
                    How We Guarantee Professional BIM Mastery
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Our structured 4-step progressive learning framework built around real construction documentation.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 pt-2">
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/5 space-y-2">
                    <span class="text-2xl font-black font-mono text-[#D4AF37]">01</span>
                    <h4 class="font-bold text-sm text-slate-900 dark:text-white font-['Outfit']">ISO 19650 Standards</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-light">Master project lifecycle information management, BEP authoring, and CDE environment setup.</p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/5 space-y-2">
                    <span class="text-2xl font-black font-mono text-[#D4AF37]">02</span>
                    <h4 class="font-bold text-sm text-slate-900 dark:text-white font-['Outfit']">Production Modeling</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-light">Execute LOD 350/400 architectural, rebar, and MEP systems on actual high-rise datasets.</p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/5 space-y-2">
                    <span class="text-2xl font-black font-mono text-[#D4AF37]">03</span>
                    <h4 class="font-bold text-sm text-slate-900 dark:text-white font-['Outfit']">Clash & 4D Simulation</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-light">Detect spatial interference, generate clash matrices, and integrate Primavera timelines into Navisworks.</p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/5 space-y-2">
                    <span class="text-2xl font-black font-mono text-[#D4AF37]">04</span>
                    <h4 class="font-bold text-sm text-slate-900 dark:text-white font-['Outfit']">Certified Credential</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-light">Complete capstone project submission and earn an industry-recognized QR-verified diploma.</p>
                </div>
            </div>
        </div>
    </main>

    <x-public-footer />
</x-layouts.base>
