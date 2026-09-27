<x-layouts.base title="Instructor Engineering Studio — Beforbim">
    <!-- Instructor Header (Navy / Gold) -->
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
                        @if($profile && $profile->isApproved())
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#D4AF37]/20 text-[#F3D98B] border border-[#D4AF37]/40">
                                Certified BIM Instructor
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40">
                                Profile In Review
                            </span>
                        @endif
                    </div>
                    <p class="text-[11px] text-slate-400 font-mono">{{ $profile?->specialization ?: 'Senior BIM Consultant' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('instructor.reports.earnings') }}">
                    <button type="button" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#D4AF37]/10 hover:bg-[#D4AF37]/20 text-[#F3D98B] border border-[#D4AF37]/30 transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Earnings Report</span>
                    </button>
                </a>
                <a href="{{ route('courses.create') }}">
                    <button type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#F3D98B] text-[#040E1E] shadow-md shadow-[#D4AF37]/20 transition flex items-center gap-1.5">
                        <span>+ New Course</span>
                    </button>
                </a>
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
        
        <!-- Studio Metrics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-3xl bg-[#071A36]/80 p-5 border border-white/10 hover:border-[#D4AF37]/50 shadow-xl transition-all">
                <div class="flex items-center justify-between text-slate-400 mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider">Total Courses</span>
                    <span class="p-2 rounded-xl bg-white/5 text-[#D4AF37]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-white font-['Outfit']">{{ $metrics['total_courses'] }}</span>
                    <span class="text-xs text-slate-400">({{ $metrics['published_courses'] }} Published)</span>
                </div>
            </div>

            <div class="rounded-3xl bg-[#071A36]/80 p-5 border border-white/10 hover:border-[#D4AF37]/50 shadow-xl transition-all">
                <div class="flex items-center justify-between text-slate-400 mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider">Trained Engineers</span>
                    <span class="p-2 rounded-xl bg-blue-500/10 text-cyan-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-white font-['Outfit']">{{ $metrics['total_students'] }}</span>
                    <span class="text-xs text-slate-400">Enrolled</span>
                </div>
            </div>

            <div class="rounded-3xl bg-[#071A36]/80 p-5 border border-white/10 hover:border-[#D4AF37]/50 shadow-xl transition-all">
                <div class="flex items-center justify-between text-slate-400 mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider">Pending Audit</span>
                    <span class="p-2 rounded-xl bg-amber-500/10 text-amber-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-amber-400 font-['Outfit']">{{ $metrics['pending_courses'] }}</span>
                    <span class="text-xs text-slate-400">Awaiting Approval</span>
                </div>
            </div>

            <div class="rounded-3xl bg-[#071A36]/80 p-5 border border-white/10 hover:border-[#D4AF37]/50 shadow-xl transition-all">
                <div class="flex items-center justify-between text-slate-400 mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider">Project Submissions</span>
                    <span class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-emerald-400 font-['Outfit']">{{ $metrics['pending_assignment_reviews'] }}</span>
                    <span class="text-xs text-slate-400">Needs Grading</span>
                </div>
            </div>
        </div>

        <!-- Instructor Main Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Courses List (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white font-['Outfit']">My Curriculum & BIM Courses</h3>
                        <p class="text-xs text-slate-400">Manage syllabus, upload sample datasets, and review student attendance</p>
                    </div>
                    <a href="{{ route('courses.create') }}">
                        <button type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#F3D98B] text-[#040E1E] transition">
                            + Add Course
                        </button>
                    </a>
                </div>

                @if($courses->isEmpty())
                    <div class="p-12 rounded-3xl bg-[#071A36]/60 border border-white/10 text-center space-y-4">
                        <div class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mx-auto text-[#D4AF37]">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white font-['Outfit']">No Published Courses Yet</h4>
                            <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1 font-light">
                                Start authoring your BIM curriculum, adding lessons and interactive 3D model files.
                            </p>
                        </div>
                        <a href="{{ route('courses.create') }}" class="inline-block pt-2">
                            <button type="button" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[#D4AF37] text-[#040E1E]">
                                Create First Course
                            </button>
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($courses as $course)
                            <div class="rounded-3xl bg-[#071A36]/80 border border-white/10 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-[#D4AF37]/50 transition">
                                <div class="flex items-center gap-4">
                                    <img 
                                        src="{{ $course->thumbnail_url ?: asset('images/courses/revit_arch.jpg') }}" 
                                        alt="{{ $course->title_en ?: $course->title }}" 
                                        class="w-16 h-16 rounded-2xl object-cover border border-white/10 shrink-0"
                                        onerror="this.onerror=null; this.src='{{ asset('images/courses/revit_arch.jpg') }}';"
                                    >
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#040E1E] text-[#F3D98B] border border-white/10">
                                                {{ $course->category?->name_en ?: ($course->category?->name_ar ?: 'BIM') }}
                                            </span>
                                            @if($course->status === 'APPROVED')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                                    Published
                                                </span>
                                            @elseif($course->status === 'PENDING')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                                    Under Review
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-500/20 text-slate-300 border border-slate-500/30">
                                                    Draft
                                                </span>
                                            @endif
                                        </div>
                                        <h4 class="font-bold text-white text-base mt-1 font-['Outfit']">
                                            {{ $course->title_en ?: $course->title_ar ?: $course->title }}
                                        </h4>
                                        <p class="text-xs text-slate-400 font-mono mt-0.5">
                                            ${{ number_format($course->effective_price, 0) }} • {{ $course->enrollments_count ?? 0 }} enrolled
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 self-end sm:self-center">
                                    <a href="{{ route('courses.curriculum', $course->id) }}">
                                        <button type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white border border-white/10 transition">
                                            Curriculum
                                        </button>
                                    </a>
                                    <a href="{{ route('courses.edit', $course->id) }}">
                                        <button type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#F3D98B] text-[#040E1E] transition">
                                            Edit
                                        </button>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Instructor Sidebar (1 Col) -->
            <div class="space-y-6">
                <!-- Academic Accreditation Badge -->
                <div class="rounded-3xl bg-[#040E1E] border border-[#D4AF37]/30 p-6 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#D4AF37]/10 border border-[#D4AF37]/30 flex items-center justify-center text-[#F3D98B]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-white font-['Outfit']">ISO 19650 Academic Quality</h4>
                            <p class="text-[11px] text-slate-400">All courses undergo peer curriculum review before publishing</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed font-light">
                        Make sure each lesson includes downloadable sample files (.rvt, .dwg, or .dyn) to maintain our 5-star engineering rating.
                    </p>
                </div>
            </div>
        </div>
    </main>
</x-layouts.base>
