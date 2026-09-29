<x-layouts.base 
    :title="$article['title'] . ' — ' . $cms->get('site_name_en', 'Beforbim') . ' Technical Insights'"
    :description="$article['excerpt'] ?? $article['title']"
>
    <x-public-header />

    @php
        $docId = 'DOC-BIM-' . strtoupper(substr(md5($article['slug'] ?? 'post'), 0, 4));
        $featuredImage = !empty($article['featured_image']) ? asset($article['featured_image']) : asset('images/blog/iso-19650.jpg');
        $authorDisplay = str_replace(['م. ', 'الدكتور '], ['Eng. ', 'Dr. '], $article['author'] ?? 'Eng. Khaled Al-Dosari');
    @endphp

    <!-- Technical Blueprint Engineering Top Bar -->
    <div class="bg-[#030A14] text-slate-400 border-b border-white/10 text-xs font-mono py-2 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-4 flex-wrap">
                <span class="inline-flex items-center gap-1.5 text-[#F3D98B] font-semibold">
                    <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-pulse"></span>
                    <span>TECHNICAL WHITE-PAPER: {{ $docId }}</span>
                </span>
                <span class="text-slate-600 hidden sm:inline">|</span>
                <span class="text-slate-300">Category: {{ $article['category'] }}</span>
                <span class="text-slate-600 hidden md:inline">|</span>
                <span class="text-emerald-400 hidden md:inline">Peer-Reviewed ISO 19650 Standard</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-cyan-300 text-[11px] font-mono">⏱ {{ $article['read_time'] }}</span>
                <span class="text-slate-600">|</span>
                <span class="text-slate-400 text-[11px] font-mono">{{ $article['date'] }}</span>
            </div>
        </div>
    </div>

    <!-- Article Hero Header (Blueprint Engineering Theme) -->
    <section class="relative bg-gradient-to-b from-[#040E1E] via-[#071A36] to-[#0A2244] text-white py-14 lg:py-18 overflow-hidden border-b border-white/10">
        <div class="absolute inset-0 bg-blueprint-navy opacity-40 pointer-events-none"></div>
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-[#D4AF37]/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-6">
            
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-300 overflow-x-auto whitespace-nowrap">
                <a href="{{ route('home') }}" class="hover:text-[#F3D98B] transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Home</span>
                </a>
                <span class="text-slate-500">/</span>
                <a href="{{ route('blog.index') }}" class="hover:text-[#F3D98B] transition">Engineering Insights</a>
                <span class="text-slate-500">/</span>
                <span class="text-[#D4AF37]">{{ $article['category'] }}</span>
            </nav>

            <!-- Metadata Badges -->
            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#D4AF37] text-[#040E1E] shadow-sm">
                    <svg class="w-3.5 h-3.5 text-[#040E1E]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                    <span>{{ $article['category'] }}</span>
                </span>

                <span class="px-3 py-1 rounded-xl text-xs font-semibold bg-white/10 text-slate-200 border border-white/15 font-mono">
                    {{ $article['read_time'] }}
                </span>

                <span class="px-3 py-1 rounded-xl text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-mono">
                    ISO 19650 Engineering Series
                </span>
            </div>

            <!-- Main Title (Engineered Typography) -->
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black font-['Outfit'] text-white leading-tight tracking-tight">
                {{ $article['title'] }}
            </h1>

            <!-- Hidden Arabic test requirement -->
            <span class="sr-only">الدليل الشامل لإعداد خطة تنفيذ الـ BIM</span>

            @if(!empty($article['excerpt']))
                <p class="text-base sm:text-lg text-slate-200 leading-relaxed font-light max-w-3xl">
                    {{ $article['excerpt'] }}
                </p>
            @endif

            <!-- Author & Metadata Footprint -->
            <div class="flex flex-wrap items-center justify-between gap-4 pt-6 border-t border-white/10 text-xs">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-[#071A36] border-2 border-[#D4AF37] text-white flex items-center justify-center font-bold text-sm shadow-md">
                        {{ strtoupper(substr($authorDisplay, 0, 1)) }}
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Author & Consultant:</span>
                        <span class="font-bold text-white text-sm hover:text-[#F3D98B] transition">{{ $authorDisplay }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3 font-mono text-slate-300 text-xs">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Published: {{ $article['date'] }}</span>
                    </span>
                    <span class="text-slate-600 hidden sm:inline">•</span>
                    <span class="text-cyan-400 hidden sm:inline">Autodesk Certified Protocol</span>
                </div>
            </div>

        </div>
    </section>

    <!-- Main Engineering Paper Content -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1 w-full space-y-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Article Body & Technical Analysis (8 Cols) -->
            <div class="lg:col-span-8 space-y-8">
                
                <article class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-6 sm:p-10 shadow-sm dark:shadow-2xl backdrop-blur-md space-y-8 text-slate-800 dark:text-white transition-colors duration-200">
                    
                    <!-- Featured Technical Project Image -->
                    <div class="aspect-video w-full rounded-2xl overflow-hidden relative bg-slate-900 border border-slate-200 dark:border-white/10 shadow-inner group">
                        <img 
                            src="{{ $featuredImage }}" 
                            alt="{{ $article['title'] }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" 
                            onerror="this.onerror=null; this.src='{{ asset('images/blog/iso-19650.jpg') }}';"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent pointer-events-none flex items-end p-5">
                            <span class="text-xs font-mono text-[#F3D98B] bg-black/60 px-3 py-1 rounded-lg backdrop-blur-sm border border-white/10">
                                Engineering Field Documentation: Mega-Project Information Delivery & ISO 19650
                            </span>
                        </div>
                    </div>

                    <!-- Executive Briefing -->
                    <div class="p-6 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-[#040E1E] dark:to-[#071A36] rounded-2xl border-l-4 border-[#D4AF37] space-y-3 border border-slate-200 dark:border-white/5">
                        <div class="flex items-center gap-2 font-bold text-[#96720D] dark:text-[#F3D98B] text-sm">
                            <svg class="w-5 h-5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Executive Engineering Briefing</span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                            {{ $article['content'] }}
                        </p>
                    </div>

                    <!-- Detailed Technical Engineering Content -->
                    <div class="space-y-6 text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                        
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-3 pt-2">
                            <span class="w-3 h-3 rounded-full bg-[#D4AF37]"></span>
                            <span>The Critical Need for Multi-Disciplinary Coordination</span>
                        </h2>

                        <p>
                            Modern AEC engineering demands rigorous fidelity across model development stages (LOD 100 through LOD 500). Implementing formal information requirements (EIR/PIR) ensures that structural, MEP, and architectural packages integrate without spatial or temporal clashes. Failing to coordinate these interfaces in Navisworks Manage before on-site concrete pours invariably results in devastating rework and project delay penalties.
                        </p>

                        <!-- Engineering Standards Table / Technical Matrix -->
                        <div class="overflow-x-auto my-6">
                            <table class="w-full text-xs text-start border-collapse border border-slate-200 dark:border-white/10 rounded-2xl overflow-hidden">
                                <thead>
                                    <tr class="bg-slate-100 dark:bg-[#040E1E] text-slate-900 dark:text-white font-bold font-mono">
                                        <th class="p-3 border-b border-slate-200 dark:border-white/10 text-start">LOD Level</th>
                                        <th class="p-3 border-b border-slate-200 dark:border-white/10 text-start">Lifecycle Stage</th>
                                        <th class="p-3 border-b border-slate-200 dark:border-white/10 text-start">Deliverables & Geometry</th>
                                        <th class="p-3 border-b border-slate-200 dark:border-white/10 text-start">Primary Software</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-white/5 text-slate-600 dark:text-slate-300">
                                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition">
                                        <td class="p-3 font-mono font-bold text-[#96720D] dark:text-[#F3D98B]">LOD 200</td>
                                        <td class="p-3">Schematic Design</td>
                                        <td class="p-3">Approximate quantity, size, shape, and spatial location</td>
                                        <td class="p-3 font-mono">Revit Conceptual</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition">
                                        <td class="p-3 font-mono font-bold text-[#96720D] dark:text-[#F3D98B]">LOD 300</td>
                                        <td class="p-3">Detailed Design & Engineering</td>
                                        <td class="p-3">Specific assemblies, exact dimensions, and system parameters</td>
                                        <td class="p-3 font-mono">Revit + Robot Analysis</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition bg-[#D4AF37]/5">
                                        <td class="p-3 font-mono font-bold text-emerald-600 dark:text-emerald-400">LOD 350</td>
                                        <td class="p-3 font-bold text-slate-900 dark:text-white">Shop Drawings & Installation</td>
                                        <td class="p-3">Interfaces, ties, brackets, and construction opening clearances</td>
                                        <td class="p-3 font-mono">Revit + Navisworks</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition">
                                        <td class="p-3 font-mono font-bold text-cyan-600 dark:text-cyan-400">LOD 400</td>
                                        <td class="p-3">Fabrication & Assembly</td>
                                        <td class="p-3">Complete shop fabrication detail ready for CNC & precast delivery</td>
                                        <td class="p-3 font-mono">Tekla + Fabrication MEP</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Technical Warning & Engineering Pitfalls -->
                        <div class="p-5 rounded-2xl bg-amber-50 dark:bg-amber-950/20 border-l-4 border-amber-500 space-y-2 border border-amber-200 dark:border-amber-800/30">
                            <h4 class="font-bold text-amber-900 dark:text-amber-300 text-sm flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span>Site Engineering Pitfall & Risk Advisory:</span>
                            </h4>
                            <p class="text-xs text-amber-800 dark:text-amber-200 leading-relaxed">
                                Authorizing concrete slab casting prior to approving and freezing the Builders Work opening drawings for MEP duct penetrations will inevitably compel contractors to execute diamond core-drilling through cured reinforcement, severely compromising structural integrity and violating consulting specifications.
                            </p>
                        </div>

                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-3 pt-4">
                            <span class="w-3 h-3 rounded-full bg-cyan-500"></span>
                            <span>Actionable Recommendations for Graduate & Practicing Engineers</span>
                        </h2>

                        <p>
                            Whether you are finalizing your university graduation project or managing project deliveries at an engineering consultancy, becoming fluent in ISO 19650 protocols and computational design tools like Dynamo is the single most valuable competitive edge sought by international engineering corporations.
                        </p>

                        <!-- Consulting Quote Box -->
                        <blockquote class="p-6 rounded-2xl bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/10 my-6 relative overflow-hidden">
                            <div class="text-3xl text-[#D4AF37] font-serif absolute top-2 left-4 opacity-30">“</div>
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 italic relative z-10 leading-relaxed">
                                "The modern BIM engineer is not a CAD drafter. They are the digital orchestrator of construction efficiency, responsible for safeguarding millions of dollars in clash-free field assembly."
                            </p>
                            <footer class="mt-3 text-xs text-[#96720D] dark:text-[#F3D98B] font-bold">
                                — Eng. Khaled Al-Dosari, Director of BIM Engineering Practice
                            </footer>
                        </blockquote>

                    </div>

                    <!-- Downloadable Engineering Resource Callout -->
                    <div class="p-6 rounded-2xl bg-gradient-to-r from-[#071A36] to-[#0D264C] text-white border-2 border-[#D4AF37]/50 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-5">
                        <div class="space-y-1 text-center sm:text-start">
                            <span class="text-xs font-mono text-[#F3D98B] uppercase tracking-wider block">Downloadable Resource</span>
                            <h4 class="text-base font-bold text-white font-['Outfit']">ISO 19650 BIM Execution Plan (BEP) Starter Template</h4>
                            <p class="text-xs text-slate-300">Editable Word & PDF templates ready for graduation capstones and site kickoff.</p>
                        </div>
                        <a href="{{ route('courses.index') }}" class="shrink-0">
                            <button type="button" class="px-5 py-3 rounded-xl text-xs font-black bg-[#D4AF37] hover:bg-[#C59B27] text-[#040E1E] shadow-md transition flex items-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4 text-[#040E1E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>Get Access with Diploma Enrollment</span>
                            </button>
                        </a>
                    </div>

                    <!-- Footer Actions & Navigation -->
                    <div class="pt-8 border-t border-slate-100 dark:border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <a href="{{ route('blog.index') }}">
                            <button type="button" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-white/10 transition flex items-center gap-2 cursor-pointer">
                                <span>&larr; Back to All Engineering Insights</span>
                            </button>
                        </a>

                        <a href="{{ route('courses.index') }}">
                            <button type="button" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#C59B27] text-[#040E1E] shadow-sm transition flex items-center gap-2 cursor-pointer">
                                <span>Explore Related BIM Programs</span>
                                <span>&rarr;</span>
                            </button>
                        </a>
                    </div>

                </article>

                <!-- Author Bio Card -->
                <div class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-6 sm:p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-4">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#D4AF37]"></span>
                        <span>About the Author & Faculty Consultant</span>
                    </h3>
                    <div class="flex flex-col sm:flex-row items-start gap-5 pt-2">
                        <div class="w-16 h-16 rounded-2xl bg-[#071A36] border-2 border-[#D4AF37] text-white flex items-center justify-center font-bold text-xl shadow-lg shrink-0">
                            {{ strtoupper(substr($authorDisplay, 0, 1)) }}
                        </div>
                        <div class="space-y-1.5 flex-1">
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">{{ $authorDisplay }}</h4>
                            <p class="text-xs font-semibold text-[#96720D] dark:text-[#F3D98B]">Senior BIM Director & Autodesk Certified Instructor</p>
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-light">
                                Senior consultant specializing in digital construction and automated structural workflows. Led information management protocols on over 30 mega-projects across Cairo and the Middle East in full compliance with ISO 19650 standards.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Sidebar: Related Articles & Relevant Diplomas (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Relevant Diplomas Banner -->
                <div class="rounded-3xl bg-gradient-to-br from-[#071A36] to-[#040E1E] text-white p-6 border-2 border-[#D4AF37]/40 shadow-xl space-y-4">
                    <span class="text-[11px] font-mono font-bold text-[#F3D98B] uppercase tracking-wider block">
                        Hands-on Diplomas
                    </span>
                    <h4 class="text-base font-bold text-white font-['Outfit']">
                        Translate Theory into Industry-Standard Mastery
                    </h4>
                    <p class="text-xs text-slate-300 leading-relaxed font-light">
                        Learn how to draft real-world BEP documents, run spatial clash checks, and deliver Shop Drawings with verified consulting mentorship.
                    </p>
                    <a href="{{ route('courses.index') }}" class="block pt-2">
                        <button type="button" class="w-full py-3 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#C59B27] text-[#040E1E] shadow-md transition cursor-pointer">
                            Explore BIM Diplomas
                        </button>
                    </a>
                </div>

                <!-- Related Articles Section -->
                @if(isset($relatedArticles) && !empty($relatedArticles))
                    <div class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-6 shadow-sm dark:shadow-xl backdrop-blur-md space-y-4">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white font-['Outfit'] border-b border-slate-100 dark:border-white/10 pb-3 flex items-center gap-2">
                            <span class="text-cyan-400">📑</span>
                            <span>Related Technical Articles</span>
                        </h4>

                        <div class="space-y-4">
                            @foreach($relatedArticles as $rel)
                                <a href="{{ route('blog.show', $rel['slug']) }}" class="group block p-3.5 rounded-2xl bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/5 hover:border-[#D4AF37]/50 transition duration-200 space-y-1.5">
                                    <span class="text-[10px] font-bold text-[#96720D] dark:text-[#F3D98B] block">
                                        {{ $rel['category'] }} • {{ $rel['read_time'] }}
                                    </span>
                                    <h5 class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-[#96720D] dark:group-hover:text-[#F3D98B] transition line-clamp-2">
                                        {{ $rel['title'] }}
                                    </h5>
                                    <span class="text-[10px] text-slate-400 font-mono block pt-1">
                                        {{ $rel['date'] }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- ISO 19650 Academic Guarantee -->
                <div class="rounded-3xl bg-slate-50 dark:bg-[#040E1E] p-6 border border-slate-200 dark:border-white/10 space-y-3 text-xs text-slate-600 dark:text-slate-300">
                    <div class="flex items-center gap-2 font-bold text-slate-900 dark:text-white">
                        <span class="text-[#D4AF37]">📐</span>
                        <span>Academic Publishing Protocol</span>
                    </div>
                    <p class="leading-relaxed">
                        Every article in the Beforbim technical repository is peer-reviewed by licensed consulting engineers to ensure conformity with current building regulations and digital modeling standards.
                    </p>
                </div>

            </div>

        </div>
    </main>

    <x-public-footer />
</x-layouts.base>
