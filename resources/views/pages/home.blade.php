<x-layouts.base 
    :title="$cms->get('site_name_en', 'Beforbim') . ' — Leading BIM & Digital Construction Engineering Academy'"
    :description="$cms->get('seo_meta_description', 'Empowering engineers across Egypt and worldwide with accredited BIM masterclasses, ISO 19650 certification, Revit, Navisworks, and Dynamo.')"
>
    <x-public-header />

    <!-- 1. Hero Section with 3D Engineering BIM Visuals & Ambient Glow -->
    <section class="relative bg-gradient-to-b from-slate-950 via-[#071A36] to-[#040E1E] text-white py-20 lg:py-28 overflow-hidden min-h-[85vh] flex items-center">
        
        <!-- Large Visual 3D BIM Wireframe Background -->
        <div class="absolute inset-0 z-0">
            <img 
                src="{{ asset('images/hero/hero_bim_background.jpg') }}" 
                alt="BIM 3D Engineering Architecture" 
                class="w-full h-full object-cover object-center opacity-30 lg:opacity-40 scale-105 animate-pulse-glow"
            >
            <!-- High-Tech Gradient Overlays for Readability -->
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-slate-950/90"></div>
            <div class="absolute inset-0 bg-blueprint-grid opacity-25"></div>
        </div>

        <!-- Ambient Luxury Glow Orbs -->
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-[#D4AF37]/15 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-cyan-500/10 rounded-full blur-[140px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                <!-- Left Content (7 Cols) -->
                <div class="lg:col-span-7 space-y-6 text-start">
                    
                    <!-- Trust & Accreditation Pill -->
                    <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/5 border border-[#D4AF37]/40 text-xs font-semibold text-[#F3D98B] backdrop-blur-md shadow-lg shadow-black/30">
                        <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-ping"></span>
                        <span class="font-mono tracking-wide uppercase text-[11px] font-bold">ISO 19650 Accredited Academy</span>
                        <span class="text-slate-500">|</span>
                        <span class="text-slate-300 text-[11px]">Cairo, Egypt</span>
                    </div>

                    <!-- Main Catchy SaaS Title -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black font-['Outfit'] tracking-tight leading-[1.12] text-white">
                        Master the Future of <br class="hidden sm:block">
                        <span class="text-gradient-cyan-gold">Digital Construction & BIM</span>
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-base sm:text-lg text-slate-300 leading-relaxed max-w-2xl font-light">
                        Industrial-grade diplomas in <strong class="text-white font-semibold">Autodesk Revit (LOD 350)</strong>, <strong class="text-white font-semibold">Navisworks 4D Clash Coordination</strong>, and <strong class="text-white font-semibold">Dynamo Computational Design</strong>. Grounded in real mega-projects and taught by accredited consulting directors.
                    </p>

                    <!-- Interactive CTAs -->
                    <div class="flex flex-wrap items-center gap-4 pt-3">
                        <a href="{{ route('courses.index') }}" class="px-8 py-4 rounded-2xl text-sm font-extrabold bg-[#D4AF37] hover:bg-[#C59B27] text-[#040E1E] shadow-xl shadow-[#D4AF37]/25 hover:shadow-2xl hover:shadow-[#D4AF37]/40 hover:scale-[1.02] transition-all duration-300 flex items-center gap-2 group">
                            <span>Explore Professional Diplomas</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        <a href="{{ route('about') }}" class="px-7 py-4 rounded-2xl text-sm font-bold text-slate-200 hover:text-white bg-white/5 hover:bg-white/10 border border-white/15 backdrop-blur-md transition-all duration-300 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Platform Tour & Faculty</span>
                        </a>
                    </div>

                    <!-- Trust Bar Badges -->
                    <div class="pt-6 border-t border-white/10 flex flex-wrap items-center gap-6 sm:gap-10 text-xs text-slate-400">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>QR Code-Verified Certificates</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>100% Real Project Workflows</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>BuildingSMART Aligned</span>
                        </div>
                    </div>
                </div>

                <!-- Right Visual: Futuristic 3D BIM Model Terminal Card (5 Cols) -->
                <div class="lg:col-span-5 relative">
                    <div class="absolute -inset-1 rounded-[32px] bg-gradient-to-tr from-[#D4AF37]/50 via-cyan-500/30 to-[#123B68]/60 blur-xl opacity-60 animate-pulse"></div>

                    <div class="relative rounded-3xl bg-[#071A36]/90 border border-white/15 backdrop-blur-2xl shadow-2xl p-6 sm:p-7 space-y-5 overflow-hidden">
                        
                        <!-- Header status row -->
                        <div class="flex items-center justify-between border-b border-white/10 pb-4">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                                <span class="font-mono text-xs font-bold text-white tracking-widest">BIM ENGINE v3.4</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-mono font-bold bg-[#D4AF37]/20 text-[#F3D98B] border border-[#D4AF37]/30">
                                ACTIVE COHORTS
                            </span>
                        </div>

                        <!-- 3 Pipeline Rows -->
                        <div class="space-y-3">
                            <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-between hover:bg-white/10 transition group">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#040E1E] border border-cyan-400/40 text-cyan-400 flex items-center justify-center font-mono font-bold text-xs">
                                        RVT
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-white group-hover:text-[#F3D98B] transition">Architectural & Structural LOD 350</h4>
                                        <p class="text-[10px] text-slate-400">Parametric Modeling & 3D Rebar Detailing</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-mono text-emerald-400 font-bold bg-emerald-500/10 px-2 py-0.5 rounded">Enrolling</span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-between hover:bg-white/10 transition group">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#040E1E] border border-[#D4AF37]/40 text-[#F3D98B] flex items-center justify-center font-mono font-bold text-xs">
                                        NWD
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-white group-hover:text-[#F3D98B] transition">Navisworks 4D Clash Matrix</h4>
                                        <p class="text-[10px] text-slate-400">Federated Models & TimeLiner Schedule</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-mono text-[#D4AF37] font-bold bg-[#D4AF37]/10 px-2 py-0.5 rounded">Popular</span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-between hover:bg-white/10 transition group">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#040E1E] border border-purple-400/40 text-purple-400 flex items-center justify-center font-mono font-bold text-xs">
                                        DYN
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-white group-hover:text-[#F3D98B] transition">Dynamo & Python API Automation</h4>
                                        <p class="text-[10px] text-slate-400">Generative Algorithms & Parameter Logic</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-mono text-purple-300 font-bold bg-purple-500/10 px-2 py-0.5 rounded">Advanced</span>
                            </div>
                        </div>

                        <!-- Floating Stat Badge inside Card -->
                        <div class="p-4 rounded-2xl bg-gradient-to-r from-[#040E1E] to-[#0A254D] border border-[#D4AF37]/30 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] uppercase tracking-wider text-slate-400 block font-mono">Graduates Career Placement</span>
                                <span class="text-lg font-black text-[#F3D98B] font-['Outfit']">98.4% Across AEC Firms</span>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-[#D4AF37]/20 flex items-center justify-center text-[#F3D98B]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. Platform Key Performance Statistics Section (Light & Dark Responsive) -->
    <section class="py-12 bg-white dark:bg-[#020712] border-y border-slate-200 dark:border-white/10 relative z-20 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-slate-200 dark:divide-white/10">
                <div class="pt-4 md:pt-0">
                    <span class="block text-3xl sm:text-4xl lg:text-5xl font-black text-[#96720D] dark:text-[#F3D98B] font-['Outfit']">12,500+</span>
                    <span class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-medium mt-1 block">Engineers & Alumni Trained</span>
                </div>
                <div class="pt-4 md:pt-0">
                    <span class="block text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white font-['Outfit']">45+</span>
                    <span class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-medium mt-1 block">Mega Projects Simulated</span>
                </div>
                <div class="pt-4 md:pt-0">
                    <span class="block text-3xl sm:text-4xl lg:text-5xl font-black text-cyan-600 dark:text-cyan-400 font-['Outfit']">100%</span>
                    <span class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-medium mt-1 block">ISO 19650 Compliance</span>
                </div>
                <div class="pt-4 md:pt-0">
                    <span class="block text-3xl sm:text-4xl lg:text-5xl font-black text-[#D4AF37] font-['Outfit']">4.9 / 5</span>
                    <span class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-medium mt-1 block">Engineering Review Rating</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Engineering Tracks Section (Light & Dark Responsive) -->
    <section class="py-20 bg-slate-50 dark:bg-[#040E1E] relative transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[#D4AF37]/10 text-[#96720D] dark:text-[#F3D98B] border border-[#D4AF37]/25 font-mono">
                    Specialized Tracks
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white font-['Outfit']">
                    Select Your Engineering Track
                    <span class="sr-only">المسارات والتخصصات</span>
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-light">
                    Industry-curated programs designed to qualify engineers for consulting firms, general contractors, and governmental authorities.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $category)
                    <div class="group relative rounded-3xl p-6 bg-white dark:bg-[#071A36]/70 border border-slate-200 dark:border-white/10 hover:border-[#D4AF37] dark:hover:border-[#D4AF37]/60 shadow-sm dark:shadow-xl hover:shadow-xl hover:shadow-[#D4AF37]/10 transition-all duration-300 flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-[#040E1E] border border-slate-200 dark:border-[#D4AF37]/40 text-[#D4AF37] flex items-center justify-center font-bold text-lg group-hover:scale-110 group-hover:bg-[#D4AF37] group-hover:text-[#040E1E] transition-all duration-300 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-bold font-['Outfit'] text-slate-900 dark:text-white group-hover:text-[#96720D] dark:group-hover:text-[#F3D98B] transition">
                                    {{ $category->name_en ?: $category->name_ar }}
                                </h3>
                            </div>

                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-3">
                                {{ $category->description_en ?: ($category->description ?: 'Certified professional diploma curriculum designed to elevate digital engineering standards.') }}
                            </p>
                        </div>

                        <div class="pt-5 mt-5 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs">
                            <span class="font-mono text-slate-500 dark:text-slate-400 font-medium">{{ $category->courses_count }} Diplomas Available</span>
                            <a href="{{ route('courses.index') }}" class="font-bold text-[#96720D] dark:text-[#F3D98B] hover:text-[#071A36] dark:hover:text-white transition flex items-center gap-1 group-hover:translate-x-1 duration-200">
                                <span>Explore</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- 4. Featured Diplomas & Courses Section (Light & Dark Responsive) -->
    <section class="py-20 bg-white dark:bg-[#020712] border-y border-slate-200 dark:border-white/10 relative transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6">
                <div class="space-y-2">
                    <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-100 dark:bg-white/5 text-[#96720D] dark:text-[#F3D98B] border border-slate-200 dark:border-[#D4AF37]/30 font-mono">
                        Flagship Programs
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white font-['Outfit']">
                        Certified BIM Diplomas & Masterclasses
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-light">
                        High-demand credentials verified by industry experts and accredited according to ISO 19650 protocols.
                    </p>
                </div>
                <a href="{{ route('courses.index') }}" class="shrink-0 px-5 py-2.5 rounded-xl text-xs font-bold text-[#040E1E] bg-[#D4AF37] hover:bg-[#C59B27] transition flex items-center gap-1.5 shadow-md shadow-[#D4AF37]/20">
                    <span>View All Diplomas ({{ $featuredCourses->count() }})</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <!-- Course Cards Grid -->
            @if($featuredCourses->isEmpty())
                <div class="rounded-3xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-[#071A36]/40 p-12 text-center text-slate-500 dark:text-slate-400 text-sm">
                    Flagship diplomas are currently being prepared for the upcoming academic cohort.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($featuredCourses as $course)
                        <x-course-card :course="$course" />
                    @endforeach
                </div>
            @endif

        </div>
    </section>

    <!-- 5. 4-Stage BIM Methodology (Light & Dark Responsive) -->
    <section class="py-20 bg-slate-50 dark:bg-[#040E1E] relative overflow-hidden transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-14">
            
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[#D4AF37]/10 text-[#96720D] dark:text-[#F3D98B] border border-[#D4AF37]/25 font-mono">
                    Methodology
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white font-['Outfit']">
                    The 4-Stage BIM Engineering Framework
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-light">
                    We transition students from basic drawing drafting to full-scale digital construction information management.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Step 1 -->
                <div class="p-6 rounded-3xl bg-white dark:bg-[#071A36]/60 border border-slate-200 dark:border-white/10 hover:border-[#D4AF37] shadow-sm dark:shadow-xl transition space-y-4">
                    <span class="text-3xl font-black text-[#D4AF37] font-['Outfit']">01</span>
                    <h4 class="text-base font-bold text-slate-900 dark:text-white font-['Outfit']">Parametric Modeling</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Accurate 3D geometry with embedded engineering metadata, shared coordinates, and custom family creation up to LOD 350.
                    </p>
                    <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-mono text-cyan-700 dark:text-cyan-300 bg-cyan-50 dark:bg-cyan-900/30 border border-cyan-200 dark:border-cyan-500/20">Revit Architecture & Structure</span>
                </div>

                <!-- Step 2 -->
                <div class="p-6 rounded-3xl bg-white dark:bg-[#071A36]/60 border border-slate-200 dark:border-white/10 hover:border-[#D4AF37] shadow-sm dark:shadow-xl transition space-y-4">
                    <span class="text-3xl font-black text-[#D4AF37] font-['Outfit']">02</span>
                    <h4 class="text-base font-bold text-slate-900 dark:text-white font-['Outfit']">Clash Coordination</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Federated interdisciplinary models, clash matrix rules, BCF issue tracking, and proactive rework elimination.
                    </p>
                    <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-mono text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-500/20">Navisworks Manage</span>
                </div>

                <!-- Step 3 -->
                <div class="p-6 rounded-3xl bg-white dark:bg-[#071A36]/60 border border-slate-200 dark:border-white/10 hover:border-[#D4AF37] shadow-sm dark:shadow-xl transition space-y-4">
                    <span class="text-3xl font-black text-[#D4AF37] font-['Outfit']">03</span>
                    <h4 class="text-base font-bold text-slate-900 dark:text-white font-['Outfit']">4D/5D Simulation</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Binding spatial elements to project timelines (TimeLiner) and automated cost estimation for tender and execution control.
                    </p>
                    <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-mono text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-500/20">TimeLiner & Cost Schedule</span>
                </div>

                <!-- Step 4 -->
                <div class="p-6 rounded-3xl bg-white dark:bg-[#071A36]/60 border border-slate-200 dark:border-white/10 hover:border-[#D4AF37] shadow-sm dark:shadow-xl transition space-y-4">
                    <span class="text-3xl font-black text-[#D4AF37] font-['Outfit']">04</span>
                    <h4 class="text-base font-bold text-slate-900 dark:text-white font-['Outfit']">API & Python Automation</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Visual algorithm scripting and Python coding to automate tedious detailing, rebar generation, and complex calculations.
                    </p>
                    <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-mono text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-900/30 border border-purple-200 dark:border-purple-500/20">Dynamo & Python API</span>
                </div>
            </div>

        </div>
    </section>

    <!-- 6. Accredited Faculty Showcase (Light & Dark Responsive) -->
    <section class="py-20 bg-white dark:bg-[#020712] border-t border-slate-200 dark:border-white/10 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-100 dark:bg-white/5 text-[#96720D] dark:text-[#F3D98B] border border-slate-200 dark:border-[#D4AF37]/30 font-mono">
                    Accredited Faculty
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white font-['Outfit']">
                    Learn Directly From Consulting Directors
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-light">
                    Seasoned practitioners leading mega-scale construction projects in Egypt, UAE, and the international AEC arena.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredInstructors as $instructor)
                    <div class="rounded-3xl p-6 bg-slate-50 dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 hover:border-[#D4AF37] shadow-sm dark:shadow-xl transition-all duration-300 text-center space-y-4 group">
                        
                        <div class="relative w-24 h-24 mx-auto">
                            <img 
                                src="{{ $instructor->avatar_url ?: asset('images/instructors/khaled_avatar.jpg') }}" 
                                alt="{{ $instructor->name }}" 
                                class="w-full h-full rounded-full object-cover border-2 border-[#D4AF37]/50 shadow-md group-hover:scale-105 transition duration-300"
                                onerror="this.onerror=null; this.src='{{ asset('images/instructors/khaled_avatar.jpg') }}';"
                            >
                            <span class="absolute bottom-0 right-0 w-6 h-6 rounded-full bg-emerald-500 border-2 border-white dark:border-[#040E1E] flex items-center justify-center text-[10px] text-white font-bold" title="Verified Instructor">✓</span>
                        </div>

                        <div>
                            <h4 class="text-base font-bold text-slate-900 dark:text-white font-['Outfit'] group-hover:text-[#96720D] dark:group-hover:text-[#F3D98B] transition">
                                <a href="{{ route('instructors.show', $instructor->id) }}">
                                    {{ $instructor->name }}
                                </a>
                            </h4>
                            <p class="text-xs text-[#96720D] dark:text-[#D4AF37] font-semibold mt-1">
                                {{ $instructor->instructorProfile?->specialization ?? 'Senior Structural BIM Specialist' }}
                            </p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-2 line-clamp-2 leading-relaxed">
                                {{ $instructor->instructorProfile?->bio ?? 'Senior engineering consultant with 12+ years directing mega digital construction programs.' }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-200/80 dark:border-white/5">
                            <a href="{{ route('instructors.show', $instructor->id) }}" class="block w-full py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10 border border-slate-200 dark:border-white/10 transition">
                                View Academic Profile &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- 7. Engineering Testimonials Section (Light & Dark Responsive) -->
    @if(!$topReviews->isEmpty())
        <section class="py-20 bg-slate-50 dark:bg-[#040E1E] border-t border-slate-200 dark:border-white/10 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
                
                <div class="text-center max-w-2xl mx-auto space-y-3">
                    <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[#D4AF37]/10 text-[#96720D] dark:text-[#F3D98B] border border-[#D4AF37]/25 font-mono">
                        Student Reviews
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white font-['Outfit']">
                        Trusted by Engineers from Top AEC Firms
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($topReviews as $rev)
                        <div class="p-6 rounded-3xl bg-white dark:bg-[#071A36]/60 border border-slate-200 dark:border-white/10 space-y-4 flex flex-col justify-between shadow-sm dark:shadow-xl hover:border-[#D4AF37] transition">
                            <div class="space-y-3">
                                <div class="flex text-amber-500 text-sm">
                                    @for($i = 0; $i < $rev->rating; $i++) ★ @endfor
                                </div>
                                <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed italic">
                                    "{{ $rev->review_text }}"
                                </p>
                            </div>

                            <div class="pt-4 border-t border-slate-100 dark:border-white/10 flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-bold text-slate-900 dark:text-white block">{{ $rev->student?->name ?? 'Civil Engineer' }}</span>
                                    <span class="text-[10px] text-slate-500 dark:text-slate-400">Verified Graduate</span>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-slate-100 dark:bg-white/5 text-[#96720D] dark:text-[#F3D98B] border border-slate-200 dark:border-white/10 truncate max-w-[130px]">
                                    {{ $rev->course?->title_en ?: $rev->course?->title_ar }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    <!-- 8. Industry Partners & Software Ecosystem (Light & Dark Responsive) -->
    <section class="py-14 bg-slate-100 dark:bg-[#020712] border-t border-slate-200 dark:border-white/10 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <span class="text-xs font-mono font-bold tracking-widest uppercase text-slate-500 dark:text-slate-400">
                Software & Standards Ecosystem
            </span>
            <div class="flex flex-wrap items-center justify-center gap-8 lg:gap-16 opacity-70 hover:opacity-100 transition-opacity duration-300">
                <span class="font-['Outfit'] text-lg font-black tracking-widest text-slate-600 dark:text-slate-400">AUTODESK REVIT</span>
                <span class="font-['Outfit'] text-lg font-black tracking-widest text-slate-600 dark:text-slate-400">NAVISWORKS MANAGE</span>
                <span class="font-['Outfit'] text-lg font-black tracking-widest text-slate-600 dark:text-slate-400">BUILDINGSMART</span>
                <span class="font-['Outfit'] text-lg font-black tracking-widest text-slate-600 dark:text-slate-400">ISO 19650</span>
                <span class="font-['Outfit'] text-lg font-black tracking-widest text-slate-600 dark:text-slate-400">DYNAMO BIM</span>
            </div>
        </div>
    </section>

    <!-- 9. Engineering Knowledge & Blog Section (Light & Dark Responsive) -->
    <section class="py-20 bg-white dark:bg-[#040E1E] border-t border-slate-200 dark:border-white/10 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6">
                <div class="space-y-2">
                    <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-100 dark:bg-white/5 text-[#96720D] dark:text-[#F3D98B] border border-slate-200 dark:border-[#D4AF37]/30 font-mono">
                        Research & Insights
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white font-['Outfit']">
                        BIM Engineering Publications
                    </h2>
                </div>
                <a href="{{ route('blog.index') }}" class="text-xs font-bold text-[#96720D] dark:text-[#F3D98B] hover:underline transition flex items-center gap-1">
                    <span>Explore All Articles</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <!-- Blog Preview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <article class="rounded-3xl bg-slate-50 dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 overflow-hidden hover:border-[#D4AF37] shadow-sm dark:shadow-xl transition group flex flex-col justify-between">
                    <div>
                        <div class="h-44 w-full bg-slate-200 dark:bg-slate-900 overflow-hidden">
                            <img src="{{ asset('images/blog/iso_19650_bep.jpg') }}" alt="ISO 19650" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" onerror="this.onerror=null; this.src='{{ asset('images/courses/revit_arch.jpg') }}';">
                        </div>
                        <div class="p-6 space-y-2.5">
                            <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 font-mono">
                                <span class="text-[#96720D] dark:text-[#F3D98B] font-bold">BIM Management</span>
                                <span>7 min read</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white font-['Outfit'] group-hover:text-[#96720D] dark:group-hover:text-[#F3D98B] transition">
                                <a href="{{ route('blog.show', 'iso-19650-bim-execution-plan-guide') }}">
                                    Comprehensive Guide to the BIM Execution Plan (ISO 19650)
                                </a>
                            </h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                Key strategies for drafting an effective BEP to mitigate dispute risks and establish collaborative Common Data Environments.
                            </p>
                        </div>
                    </div>
                    <div class="px-6 py-3.5 bg-slate-100 dark:bg-[#020712] border-t border-slate-200 dark:border-white/5 flex items-center justify-between text-xs">
                        <span class="text-slate-600 dark:text-slate-400">Eng. Khaled El-Dossary</span>
                        <a href="{{ route('blog.show', 'iso-19650-bim-execution-plan-guide') }}" class="font-bold text-[#96720D] dark:text-[#F3D98B] hover:underline transition">Read &rarr;</a>
                    </div>
                </article>

                <article class="rounded-3xl bg-slate-50 dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 overflow-hidden hover:border-[#D4AF37] shadow-sm dark:shadow-xl transition group flex flex-col justify-between">
                    <div>
                        <div class="h-44 w-full bg-slate-200 dark:bg-slate-900 overflow-hidden">
                            <img src="{{ asset('images/blog/clash_detection_matrix.jpg') }}" alt="Clash Detection" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" onerror="this.onerror=null; this.src='{{ asset('images/courses/navisworks_4d.jpg') }}';">
                        </div>
                        <div class="p-6 space-y-2.5">
                            <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 font-mono">
                                <span class="text-[#96720D] dark:text-[#F3D98B] font-bold">Coordination</span>
                                <span>5 min read</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white font-['Outfit'] group-hover:text-[#96720D] dark:group-hover:text-[#F3D98B] transition">
                                <a href="{{ route('blog.show', 'revit-clash-detection-with-navisworks') }}">
                                    Clash Detection Strategies to Eliminate Site Waste
                                </a>
                            </h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                How multidisciplinary coordination matrix protocols save hundreds of thousands in field rework and delays.
                            </p>
                        </div>
                    </div>
                    <div class="px-6 py-3.5 bg-slate-100 dark:bg-[#020712] border-t border-slate-200 dark:border-white/5 flex items-center justify-between text-xs">
                        <span class="text-slate-600 dark:text-slate-400">Eng. Ahmed El-Shammari</span>
                        <a href="{{ route('blog.show', 'revit-clash-detection-with-navisworks') }}" class="font-bold text-[#96720D] dark:text-[#F3D98B] hover:underline transition">Read &rarr;</a>
                    </div>
                </article>

                <article class="rounded-3xl bg-slate-50 dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 overflow-hidden hover:border-[#D4AF37] shadow-sm dark:shadow-xl transition group flex flex-col justify-between">
                    <div>
                        <div class="h-44 w-full bg-slate-200 dark:bg-slate-900 overflow-hidden">
                            <img src="{{ asset('images/blog/dynamo_automation_workflow.jpg') }}" alt="Dynamo Automation" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" onerror="this.onerror=null; this.src='{{ asset('images/courses/dynamo_python.jpg') }}';">
                        </div>
                        <div class="p-6 space-y-2.5">
                            <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 font-mono">
                                <span class="text-[#96720D] dark:text-[#F3D98B] font-bold">Computational Design</span>
                                <span>10 min read</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white font-['Outfit'] group-hover:text-[#96720D] dark:group-hover:text-[#F3D98B] transition">
                                <a href="{{ route('blog.show', 'dynamo-automation-for-structural-detailing') }}">
                                    Automating Concrete Rebar Detailing with Dynamo & Python
                                </a>
                            </h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                Developing custom algorithmic nodes to speed up structural shop drawing production by more than 60%.
                            </p>
                        </div>
                    </div>
                    <div class="px-6 py-3.5 bg-slate-100 dark:bg-[#020712] border-t border-slate-200 dark:border-white/5 flex items-center justify-between text-xs">
                        <span class="text-slate-600 dark:text-slate-400">Eng. Omar Farouk</span>
                        <a href="{{ route('blog.show', 'dynamo-automation-for-structural-detailing') }}" class="font-bold text-[#96720D] dark:text-[#F3D98B] hover:underline transition">Read &rarr;</a>
                    </div>
                </article>
            </div>

        </div>
    </section>

    <!-- 10. High-Impact Call to Action Banner -->
    <section class="py-24 bg-gradient-to-b from-slate-950 to-[#071A36] text-white relative overflow-hidden border-t border-slate-200 dark:border-white/10">
        <div class="absolute inset-0 bg-blueprint-grid opacity-20 pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[38rem] h-[38rem] bg-[#D4AF37]/10 rounded-full blur-[140px] pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-7">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-[#D4AF37]/20 text-[#F3D98B] border border-[#D4AF37]/40 text-xs font-bold font-mono tracking-widest uppercase">
                Enroll Today
            </span>

            <h2 class="text-3xl sm:text-5xl font-black font-['Outfit'] text-white tracking-tight leading-tight">
                Accelerate Your Engineering Career with Global BIM Certification
            </h2>

            <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Join thousands of civil engineers, architects, and MEP designers who upgraded their technical mastery and unlocked high-paying international positions.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                <a href="{{ route('register') }}" class="px-8 py-4 rounded-2xl text-sm font-black bg-[#D4AF37] hover:bg-[#C59B27] text-[#040E1E] shadow-xl shadow-[#D4AF37]/30 hover:scale-[1.02] transition-all">
                    Create Free Student Account &rarr;
                </a>
                <a href="{{ route('contact') }}" class="px-7 py-4 rounded-2xl text-sm font-bold text-white bg-white/5 hover:bg-white/10 border border-white/15 backdrop-blur-md transition-all">
                    Request Corporate Training Quote
                </a>
            </div>
        </div>
    </section>

    <x-public-footer />
</x-layouts.base>
