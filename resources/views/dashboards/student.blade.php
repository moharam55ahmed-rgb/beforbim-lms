<x-layouts.base title="Student Engineering Portal — Beforbim">
    <!-- Student Header (Deep Navy) -->
    <header class="bg-[#071A36]/95 backdrop-blur-md text-white border-b border-white/10 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img 
                        src="{{ asset('images/branding/logo.png') }}" 
                        alt="Beforbim" 
                        class="w-8 h-8 object-contain drop-shadow"
                        onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';"
                    >
                    <span class="font-black font-['Outfit'] text-base tracking-wider text-white hidden sm:inline">
                        BEFOR<span class="text-[#D4AF37]">BIM</span>
                    </span>
                </a>

                <div class="h-6 w-px bg-white/10 hidden sm:block"></div>

                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-sm sm:text-base font-bold font-['Outfit'] text-white">Eng. {{ $user->name }}</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#D4AF37]/20 text-[#F3D98B] border border-[#D4AF37]/40">
                            Verified Engineer
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-400 font-mono">{{ $user->email }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('courses.index') }}" class="hidden sm:inline-block">
                    <button type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-white/5 hover:bg-white/10 text-slate-200 border border-white/10 transition">
                        Browse Courses
                    </button>
                </a>

                <a href="{{ route('support.index') }}">
                    <button type="button" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#D4AF37]/10 hover:bg-[#D4AF37]/20 text-[#F3D98B] border border-[#D4AF37]/30 transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>Support Desk</span>
                    </button>
                </a>

                <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-[#040E1E] border border-white/10 text-[11px] text-slate-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Active Session ({{ $active_devices->count() }} devices)</span>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-white/5 hover:bg-red-500/20 text-slate-300 hover:text-red-400 border border-white/10 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-8">
        
        <!-- Welcome Hero Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#040E1E] via-[#071A36] to-[#0A2540] border border-white/15 p-8 shadow-2xl backdrop-blur-xl">
            <div class="absolute inset-0 bg-blueprint-navy opacity-30 pointer-events-none"></div>
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-[#D4AF37]/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-[#D4AF37]/15 text-[#F3D98B] border border-[#D4AF37]/30">
                        <span class="w-2 h-2 rounded-full bg-[#D4AF37]"></span>
                        <span>Digital Engineering Learning Studio</span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black text-white font-['Outfit']">
                        Welcome back, Eng. {{ $user->name }}
                    </h2>
                    
                    <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed font-light">
                        Track your ISO 19650 learning tracks, download practical Revit project datasets, and earn your verified engineering certifications.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('courses.index') }}">
                        <button type="button" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#F3D98B] text-[#040E1E] shadow-xl shadow-[#D4AF37]/20 transition flex items-center gap-2">
                            <span>Browse New Diplomas</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-white/10">
                <div class="bg-[#040E1E]/60 rounded-2xl p-4 border border-white/10 backdrop-blur-sm">
                    <p class="text-[11px] text-slate-400 uppercase tracking-wider">Enrolled Programs</p>
                    <p class="text-2xl sm:text-3xl font-black text-white font-['Outfit'] mt-1">{{ $stats['total_enrolled'] }}</p>
                </div>
                <div class="bg-[#040E1E]/60 rounded-2xl p-4 border border-white/10 backdrop-blur-sm">
                    <p class="text-[11px] text-slate-400 uppercase tracking-wider">Completed Tracks</p>
                    <p class="text-2xl sm:text-3xl font-black text-[#F3D98B] font-['Outfit'] mt-1">{{ $stats['completed_courses'] }}</p>
                </div>
                <div class="bg-[#040E1E]/60 rounded-2xl p-4 border border-white/10 backdrop-blur-sm">
                    <p class="text-[11px] text-slate-400 uppercase tracking-wider">In Progress</p>
                    <p class="text-2xl sm:text-3xl font-black text-cyan-400 font-['Outfit'] mt-1">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="bg-[#040E1E]/60 rounded-2xl p-4 border border-white/10 backdrop-blur-sm">
                    <p class="text-[11px] text-slate-400 uppercase tracking-wider">Average Progress</p>
                    <p class="text-2xl sm:text-3xl font-black text-emerald-400 font-['Outfit'] mt-1">{{ $stats['average_progress'] }}%</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Active Courses (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white font-['Outfit']">My Active BIM Programs</h3>
                        <p class="text-xs text-slate-400">Continue your specialized modules and project reviews</p>
                    </div>
                    <span class="text-xs font-semibold text-[#F3D98B] font-mono">
                        {{ $enrollments->count() }} Programs
                    </span>
                </div>

                @if($enrollments->isEmpty())
                    <div class="p-12 rounded-3xl bg-[#071A36]/60 border border-white/10 text-center space-y-4">
                        <div class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mx-auto text-[#D4AF37]">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white font-['Outfit']">No Active Courses Enrolled Yet</h4>
                            <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1 font-light">
                                Select from our accredited diplomas in Revit Architecture, Structural Detailing, MEP Coordination, or Dynamo Automation.
                            </p>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('courses.index') }}">
                                <button type="button" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[#D4AF37] text-[#040E1E] shadow-md shadow-[#D4AF37]/20">
                                    Explore BIM Diplomas
                                </button>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($enrollments as $enrollment)
                            <div class="rounded-3xl bg-[#071A36]/80 border border-white/10 hover:border-[#D4AF37]/50 shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden">
                                <div class="p-6 space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#040E1E] text-slate-300 border border-white/10">
                                            {{ $enrollment->course->level ?? 'All Levels' }}
                                        </span>
                                        <span class="text-xs font-mono font-bold text-[#F3D98B] bg-[#040E1E] px-2.5 py-1 rounded-lg border border-white/10">
                                            {{ $enrollment->progress_percentage }}%
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-white text-base mb-1 font-['Outfit'] leading-snug">
                                            {{ $enrollment->course->title_en ?: $enrollment->course->title_ar ?: $enrollment->course->title }}
                                        </h4>
                                        <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed font-light">
                                            {{ $enrollment->course->short_description_ar ?? $enrollment->course->description }}
                                        </p>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="space-y-1.5 pt-2">
                                        <div class="w-full bg-[#040E1E] rounded-full h-2 overflow-hidden border border-white/5">
                                            <div class="bg-gradient-to-r from-[#D4AF37] to-[#F3D98B] h-2 rounded-full transition-all duration-500" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-6 py-4 bg-[#040E1E] border-t border-white/10 flex items-center justify-between">
                                    <span class="text-xs text-slate-400">
                                        {{ $enrollment->course->instructor?->name ?? 'Beforbim Faculty' }}
                                    </span>
                                    <a href="{{ route('courses.show', $enrollment->course->id) }}">
                                        <button type="button" class="px-4 py-1.5 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#F3D98B] text-[#040E1E] transition">
                                            Continue Learning &rarr;
                                        </button>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Column: Timeline & Assessments (1 Col) -->
            <div class="space-y-6">
                <!-- Upcoming Assessments Widget -->
                <div class="rounded-3xl bg-[#071A36]/80 border border-white/10 p-6 shadow-xl space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-white/10">
                        <h4 class="font-bold text-sm text-white font-['Outfit'] flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#D4AF37]"></span>
                            <span>Practical Assessments</span>
                        </h4>
                        <span class="text-xs text-slate-400 font-mono">{{ $upcoming_assessments->count() }} Available</span>
                    </div>

                    @if($upcoming_assessments->isEmpty())
                        <p class="text-xs text-slate-400 text-center py-4 font-light">No exams currently due.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($upcoming_assessments as $assessment)
                                <div class="p-3.5 rounded-2xl bg-[#040E1E] border border-white/5 hover:border-[#D4AF37]/40 transition">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-white">{{ $assessment->title }}</span>
                                        <span class="text-[10px] font-mono bg-blue-500/20 text-blue-300 px-2 py-0.5 rounded border border-blue-500/30">{{ $assessment->time_limit_minutes }} min</span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 mt-1 truncate">{{ $assessment->course?->title }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Activity Timeline Widget -->
                <div class="rounded-3xl bg-[#071A36]/80 border border-white/10 p-6 shadow-xl space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-white/10">
                        <h4 class="font-bold text-sm text-white font-['Outfit'] flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                            <span>Recent Learning Activity</span>
                        </h4>
                    </div>

                    @if($timeline->isEmpty())
                        <p class="text-xs text-slate-400 text-center py-4 font-light">No recent activity recorded.</p>
                    @else
                        <div class="relative border-l border-white/10 space-y-4 pl-4 ml-2">
                            @foreach($timeline as $activity)
                                <div class="relative">
                                    <span class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full bg-[#D4AF37] ring-4 ring-[#071A36]"></span>
                                    <p class="text-xs font-bold text-white">{{ $activity['title'] }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $activity['subtitle'] }}</p>
                                    <span class="text-[10px] font-mono text-slate-500 mt-0.5 block">
                                        {{ $activity['date']->diffForHumans() }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Security & Device Verification -->
                <div class="rounded-3xl bg-[#040E1E] border border-[#D4AF37]/30 p-5 text-white text-xs space-y-2">
                    <div class="flex items-center justify-between text-[#F3D98B]">
                        <span class="font-bold font-['Outfit']">Engineering IP Protection</span>
                        <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <p class="text-slate-300 leading-relaxed text-[11px] font-light">
                        Courseware and proprietary BIM models are watermarked with your student ID to protect intellectual property and ensure certificate authenticity.
                    </p>
                </div>
            </div>
        </div>
    </main>
</x-layouts.base>
