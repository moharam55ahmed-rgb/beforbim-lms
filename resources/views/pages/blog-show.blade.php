<x-layouts.base 
    :title="$article['title'] . ' — ' . $cms->get('site_name_en', 'Beforbim')"
    :description="$article['excerpt'] ?? $article['title']"
>
    <x-public-header />

    <!-- Article Header Hero -->
    <section class="relative bg-gradient-to-b from-slate-950 via-[#071A36] to-[#040E1E] text-white py-14 lg:py-18 overflow-hidden border-b border-slate-200 dark:border-white/10">
        <div class="absolute inset-0 bg-blueprint-navy opacity-30 pointer-events-none"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#D4AF37]/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-4">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-400">
                <a href="{{ route('home') }}" class="hover:text-[#F3D98B] transition">Home</a>
                <span class="text-slate-600">/</span>
                <a href="{{ route('blog.index') }}" class="hover:text-[#F3D98B] transition">Engineering Insights</a>
                <span class="text-slate-600">/</span>
                <span class="text-[#D4AF37]">{{ $article['category'] }}</span>
            </nav>

            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-[#D4AF37]/20 text-[#F3D98B] border border-[#D4AF37]/30">
                <span>{{ $article['category'] }}</span>
                <span class="text-slate-400">•</span>
                <span>⏱ {{ $article['read_time'] }}</span>
            </div>

            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black font-['Outfit'] text-white leading-tight tracking-tight">
                {{ $article['title'] }}
            </h1>

            <div class="flex items-center gap-4 text-xs text-slate-300 pt-4 border-t border-white/10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-[#071A36] border border-[#D4AF37]/50 text-white flex items-center justify-center font-bold text-xs">
                        {{ mb_substr($article['author'] ?? 'B', 0, 1) }}
                    </div>
                    <span class="font-bold text-white">{{ $article['author'] }}</span>
                </div>
                <span>•</span>
                <span class="font-mono text-slate-400">{{ $article['date'] }}</span>
                <span>•</span>
                <span class="text-emerald-400">ISO 19650 Engineering Series</span>
            </div>
        </div>
    </section>

    <!-- Article Content (Light & Dark Responsive) -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1 w-full">
        <div class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-8 sm:p-12 shadow-sm dark:shadow-2xl backdrop-blur-md space-y-8 text-slate-800 dark:text-white transition-colors duration-200">
            
            @if(!empty($article['featured_image']))
                <div class="h-64 sm:h-96 w-full rounded-2xl overflow-hidden relative bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-white/10">
                    <img 
                        src="{{ $article['featured_image'] }}" 
                        alt="{{ $article['title'] }}" 
                        class="w-full h-full object-cover" 
                        onerror="this.onerror=null; this.parentElement.style.display='none';"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                </div>
            @endif

            <div class="prose dark:prose-invert max-w-none text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed space-y-5 font-light">
                <p class="text-base sm:text-lg font-normal text-slate-900 dark:text-white leading-relaxed border-l-4 border-[#D4AF37] pl-4">
                    {{ $article['content'] }}
                </p>

                <p>
                    Modern AEC engineering demands rigorous fidelity across model development stages (LOD 100 through LOD 500). Implementing formal information requirements (EIR/PIR) ensures that structural, MEP, and architectural packages integrate without spatial or temporal clashes.
                </p>

                <!-- Professional Callout Box -->
                <div class="p-6 bg-slate-50 dark:bg-[#040E1E] rounded-2xl border-l-4 border-[#D4AF37] my-6 space-y-2 border border-slate-200 dark:border-white/5">
                    <h4 class="font-bold text-[#96720D] dark:text-[#F3D98B] text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Strategic Recommendation for Engineering Leads:</span>
                    </h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Executing project deliverables under an audited BIM Execution Plan (BEP) typically reduces on-site contractor rework by at least 15% and guarantees digital twin handover readiness.
                    </p>
                </div>

                <p>
                    Ready to elevate your engineering team's BIM maturity level? Explore our accredited ISO 19650 diplomas and practical Revit/Navisworks certifications.
                </p>
            </div>

            <!-- Footer Actions & Navigation -->
            <div class="pt-8 border-t border-slate-100 dark:border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="{{ route('blog.index') }}">
                    <button type="button" class="px-5 py-2.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-white/10 transition flex items-center gap-2 cursor-pointer">
                        <span>&larr; Back to All Articles</span>
                    </button>
                </a>
                <a href="{{ route('courses.index') }}">
                    <button type="button" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#C59B27] text-[#040E1E] shadow-sm transition flex items-center gap-2 cursor-pointer">
                        <span>Explore Relevant BIM Programs</span>
                        <span>&rarr;</span>
                    </button>
                </a>
            </div>
        </div>
    </main>

    <x-public-footer />
</x-layouts.base>
