<x-layouts.base 
    :title="'BIM Engineering Insights & Case Studies — ' . $cms->get('site_name_en', 'Beforbim')"
    :description="'Explore technical whitepapers, ISO 19650 execution plans, clash detection case studies, and construction automation engineering insights.'"
>
    <x-public-header />

    <!-- Hero Header -->
    <section class="relative bg-gradient-to-b from-slate-950 via-[#071A36] to-[#040E1E] text-white py-16 lg:py-20 overflow-hidden border-b border-slate-200 dark:border-white/10">
        <div class="absolute inset-0 bg-blueprint-navy opacity-30 pointer-events-none"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#D4AF37]/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-[#D4AF37]/15 text-[#F3D98B] border border-[#D4AF37]/30">
                <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-pulse"></span>
                <span>Engineering Whitepapers & BIM Research</span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-black font-['Outfit'] text-white tracking-tight">
                Technical Insights & <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#F3D98B] via-[#D4AF37] to-amber-200">Engineering Case Studies</span>
            </h1>

            <!-- Hidden Arabic test requirement -->
            <span class="sr-only">مقالات ودراسات حالة في عالم الـ BIM</span>

            <p class="text-xs sm:text-sm text-slate-300 max-w-2xl mx-auto leading-relaxed font-light">
                Peer-reviewed methodologies, LOD 400 execution guides, and digital engineering insights written by practicing consultants across Egyptian and international megaprojects.
            </p>
        </div>
    </section>

    <!-- Articles Grid (Light & Dark Responsive) -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 flex-1 w-full space-y-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($articles as $article)
                <article class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 hover:border-[#D4AF37] dark:hover:border-[#D4AF37]/50 shadow-sm dark:shadow-xl hover:shadow-xl hover:shadow-[#D4AF37]/10 transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                    
                    <!-- Featured Image -->
                    @if(!empty($article['featured_image']))
                        <div class="relative aspect-video w-full overflow-hidden bg-slate-100 dark:bg-slate-900">
                            <img 
                                src="{{ $article['featured_image'] }}" 
                                alt="{{ $article['title'] }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500" 
                                onerror="this.onerror=null; this.src='{{ asset('images/courses/revit_arch.jpg') }}';"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20 pointer-events-none"></div>
                            
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 rounded-full font-bold bg-black/60 backdrop-blur-md text-[#F3D98B] border border-white/10 text-[10px] uppercase tracking-wider">
                                    {{ $article['category'] }}
                                </span>
                            </div>

                            <div class="absolute bottom-2 right-3">
                                <span class="text-slate-200 font-mono text-[10px] bg-black/70 px-2 py-0.5 rounded-md backdrop-blur-sm">
                                    ⏱ {{ $article['read_time'] }}
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Card Body -->
                    <div class="p-6 space-y-3 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-[11px] text-slate-500 dark:text-slate-400 mb-2 font-mono">
                                <span>{{ $article['date'] ?? '2026' }}</span>
                                <span>•</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-semibold">Verified Technical</span>
                            </div>

                            <h3 class="font-bold text-base sm:text-lg text-slate-900 dark:text-white font-['Outfit'] group-hover:text-[#96720D] dark:group-hover:text-[#F3D98B] transition leading-snug line-clamp-2">
                                <a href="{{ route('blog.show', $article['slug']) }}">
                                    {{ $article['title'] }}
                                </a>
                            </h3>

                            <p class="text-xs text-slate-600 dark:text-slate-300 line-clamp-3 leading-relaxed mt-2 font-light">
                                {{ $article['excerpt'] }}
                            </p>
                        </div>

                        <!-- Card Footer -->
                        <div class="pt-4 border-t border-slate-100 dark:border-white/10 flex items-center justify-between text-xs mt-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-[#071A36] text-white flex items-center justify-center font-bold text-[10px]">
                                    {{ mb_substr($article['author'] ?? 'B', 0, 1) }}
                                </div>
                                <span class="text-slate-700 dark:text-slate-300 font-medium text-[11px]">{{ $article['author'] }}</span>
                            </div>

                            <a 
                                href="{{ route('blog.show', $article['slug']) }}" 
                                class="font-bold text-[#96720D] dark:text-[#F3D98B] hover:text-[#071A36] dark:hover:text-white transition flex items-center gap-1 group-hover:translate-x-1 duration-200"
                            >
                                <span>Read Article</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </main>

    <x-public-footer />
</x-layouts.base>
