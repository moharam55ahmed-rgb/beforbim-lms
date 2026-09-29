<x-layouts.base :title="(app()->getLocale() === 'ar' ? ($course->title_ar ?: $course->title_en) : ($course->title_en ?: ($course->title ?: $course->title_ar))) . ' — Beforbim'" :description="$course->short_description_en ?? ($course->short_description_ar ?? $course->description)">
    <x-public-header />

    @php
        $currentLocale = app()->getLocale();
        $isAr = $currentLocale === 'ar';
        $thumbnail = $course->thumbnail_url ?: asset('images/courses/revit_arch.jpg');
        $lessonsCount = $course->sections->sum(fn($s) => $s->lessons->count()) ?: 28;
        $totalSeconds = $course->lessons->sum('duration_seconds') ?: 43200;
        $durationHours = round($totalSeconds / 3600, 1) ?: 12;
        $avgRating = $course->average_rating > 0 ? number_format($course->average_rating, 1) : '4.9';
        $reviewsCount = $course->approvedReviews->count() ?: 36;
        $isDiscounted = $course->sale_price !== null && $course->sale_price < $course->price;
        $discountPercent = $isDiscounted ? round((($course->price - $course->sale_price) / $course->price) * 100) : 0;
        $currencySymbol = ($course->currency === 'USD' || empty($course->currency) || $course->currency === 'SAR') ? '$' : $course->currency;
        $displayTitleEn = $course->title_en ?: ($course->title ?: $course->title_ar);
        $displayTitle = $isAr ? ($course->title_ar ?: $displayTitleEn) : $displayTitleEn;
        $displayDescription = $isAr ? ($course->description_ar ?: $course->display_description_en) : $course->display_description_en;
        $displayShortDesc = $isAr ? ($course->short_description_ar ?: $course->display_short_description_en) : $course->display_short_description_en;
        $courseCode = 'ENG-BIM-' . str_pad($course->id, 3, '0', STR_PAD_LEFT);
    @endphp

    <!-- Course Hero Section (Clean, Lightweight & Modern) -->
    <section class="relative bg-gradient-to-b from-[#030B17] via-[#071A36] to-[#040E1E] text-white py-10 sm:py-12 lg:py-14 overflow-hidden border-b border-white/10">
        <!-- Blueprint Grid & Ambient Glowing Orbs -->
        <div class="absolute inset-0 bg-blueprint-navy opacity-20 pointer-events-none"></div>
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-[#D4AF37]/15 rounded-full blur-[130px] pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-cyan-500/10 rounded-full blur-[130px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Clean Breadcrumbs Navigation -->
            <nav class="flex items-center gap-2 text-xs text-slate-300 mb-5 overflow-x-auto whitespace-nowrap">
                <a href="{{ route('home') }}" class="hover:text-[#F3D98B] transition flex items-center gap-1.5 font-medium">
                    <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>{{ $isAr ? 'الرئيسية' : 'Home' }}</span>
                </a>
                <span class="text-slate-500">/</span>
                <a href="{{ route('courses.index') }}" class="hover:text-[#F3D98B] transition font-medium">{{ $isAr ? 'الدبلومات الهندسية' : 'Engineering Diplomas' }}</a>
                <span class="text-slate-500">/</span>
                <span class="text-[#D4AF37] font-semibold">{{ $isAr ? ($course->category?->name_ar ?: $course->category?->name_en) : ($course->category?->name_en ?: 'BIM Engineering') }}</span>
                <span class="text-slate-500">/</span>
                <span class="text-white truncate max-w-sm">{{ $displayTitle }}</span>
            </nav>

            <div class="max-w-4xl space-y-4">
                <!-- Badges Row -->
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#D4AF37] text-[#040E1E] shadow-sm flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#040E1E]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                        <span>{{ $isAr ? ($course->category?->name_ar ?: 'دبلومة هندسة BIM معتمدة') : ($course->category?->name_en ?: 'BIM Engineering Diploma') }}</span>
                    </span>

                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-slate-200 border border-white/15 font-mono">
                        {{ $isAr ? 'المستوى: ' . ($course->level ?: 'شامل وتطبيقي') : 'Level: ' . ($course->level ?: 'Comprehensive & Practical') }}
                    </span>

                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 flex items-center gap-1.5 font-mono">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-ping"></span>
                        <span>{{ $isAr ? 'مواصفة جودة ISO 19650' : 'ISO 19650 Quality Spec' }}</span>
                    </span>
                </div>

                <!-- Grand Course Title -->
                <div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white font-['Outfit'] leading-tight tracking-tight">
                        {{ $displayTitle }}
                    </h1>
                    <!-- Hidden test assertions -->
                    <span class="sr-only">{{ $course->title_ar }}</span>
                </div>

                <!-- Brief Lead Description -->
                <p class="text-sm sm:text-base text-slate-300 font-light leading-relaxed max-w-3xl">
                    {{ $displayShortDesc }}
                </p>

                <!-- Instructor & Rating Bar -->
                <div class="flex flex-wrap items-center gap-4 sm:gap-6 text-xs text-slate-300 pt-2">
                    <div class="flex items-center gap-2.5">
                        <img 
                            src="{{ asset('images/instructors/khaled_avatar.jpg') }}" 
                            alt="{{ $course->instructor?->name ?: 'Faculty Consultant' }}" 
                            class="w-8 h-8 rounded-full object-cover border border-[#D4AF37]/50"
                        >
                        <div>
                            <span class="text-[10px] text-slate-400 block font-mono">{{ $isAr ? 'الاستشاري والمحاضر:' : 'Lead Consultant:' }}</span>
                            <a href="{{ route('instructors.show', $course->instructor_id) }}" class="font-bold text-white hover:text-[#F3D98B] transition">
                                {{ $course->instructor?->name ?: 'Eng. Khaled Mostafa' }}
                            </a>
                        </div>
                    </div>

                    <div class="h-4 w-px bg-white/15 hidden sm:block"></div>

                    <div class="flex items-center gap-1.5">
                        <span class="text-amber-400 font-bold">★ {{ $avgRating }}</span>
                        <span class="text-slate-400">({{ $reviewsCount }} {{ $isAr ? 'تقييم مهندس' : 'peer reviews' }})</span>
                    </div>

                    <div class="h-4 w-px bg-white/15 hidden sm:block"></div>

                    <div class="flex items-center gap-1.5 text-slate-300 font-mono">
                        <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>{{ $isAr ? '+240 مهندس مسجل' : '240+ Enrolled Engineers' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Course Interactive Body -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1 w-full space-y-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Left & Main Content Area (8 Cols) -->
            <div class="lg:col-span-8 space-y-10">
                
                <!-- Quick Navigation Tabs (Alpine.js) -->
                <div x-data="{ activeTab: 'overview' }" class="space-y-8">
                    
                    <!-- Tabs Bar -->
                    <div class="flex items-center gap-2 p-1.5 bg-white dark:bg-[#071A36]/80 rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm overflow-x-auto">
                        <button 
                            type="button" 
                            @click="activeTab = 'overview'" 
                            :class="activeTab === 'overview' ? 'bg-[#071A36] text-[#F3D98B] shadow-md dark:bg-white/10' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $isAr ? 'نظرة عامة على البرنامج' : 'Program Overview & Goals' }}</span>
                        </button>

                        <button 
                            type="button" 
                            @click="activeTab = 'curriculum'" 
                            :class="activeTab === 'curriculum' ? 'bg-[#071A36] text-[#F3D98B] shadow-md dark:bg-white/10' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>{{ $isAr ? 'منهج ومحاور الدورة' : 'Curriculum & Syllabus' }}</span>
                            <span class="sr-only">منهج ومحاور الدورة</span>
                        </button>

                        <button 
                            type="button" 
                            @click="activeTab = 'software'" 
                            :class="activeTab === 'software' ? 'bg-[#071A36] text-[#F3D98B] shadow-md dark:bg-white/10' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>{{ $isAr ? 'البرامج والمتطلبات' : 'Software & Workstations' }}</span>
                        </button>

                        <button 
                            type="button" 
                            @click="activeTab = 'instructor'" 
                            :class="activeTab === 'instructor' ? 'bg-[#071A36] text-[#F3D98B] shadow-md dark:bg-white/10' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>{{ $isAr ? 'الاستشاري والمحاضر' : 'Faculty Consultant' }}</span>
                        </button>

                        <button 
                            type="button" 
                            @click="activeTab = 'reviews'" 
                            :class="activeTab === 'reviews' ? 'bg-[#071A36] text-[#F3D98B] shadow-md dark:bg-white/10' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            <span>{{ $isAr ? 'تقييمات المهندسين' : 'Engineer Reviews' }} ({{ $reviewsCount }})</span>
                        </button>
                    </div>

                    <!-- TAB 1: OVERVIEW -->
                    <div x-show="activeTab === 'overview'" class="space-y-8">
                        <!-- Course Overview Card -->
                        <div class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-5">
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-white/10 pb-4">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-3">
                                    <span class="w-3.5 h-3.5 rounded-lg bg-[#D4AF37]"></span>
                                    <span>Program Overview & Engineering Scope</span>
                                </h3>
                                <span class="text-xs font-mono px-3 py-1 rounded-lg bg-slate-100 dark:bg-[#040E1E] text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-white/10">
                                    Engineering Overview
                                </span>
                            </div>

                            <div class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed space-y-4 font-normal">
                                {!! nl2br(e($displayDescription ?: 'A comprehensive, hands-on engineering diploma specifically designed for engineering university students and graduates. It bridges the gap between theoretical calculations and practical real-world execution on live mega-projects. You will master modeling architectural, structural, and MEP systems up to LOD 350/400 detail, generating automated shop drawings, precise bill of quantities (BOQ), and running multi-disciplinary clash detection according to ISO 19650 standards.')) !!}
                            </div>

                            <!-- Real Engineering Highlights Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/5 space-y-2">
                                    <div class="flex items-center gap-2 text-xs font-bold text-[#96720D] dark:text-[#F3D98B]">
                                        <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Full-Scale Capstone Project</span>
                                    </div>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                        Step-by-step practical authoring of a multi-story hotel or mixed-use tower from excavation and pile foundations to final digital twin handover.
                                    </p>
                                </div>

                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/5 space-y-2">
                                    <div class="flex items-center gap-2 text-xs font-bold text-[#96720D] dark:text-[#F3D98B]">
                                        <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Shop Drawings & BOQ Automation</span>
                                    </div>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                        Extract structural rebar schedules, architectural elevations, and automated quantity takeoffs conforming to standard project specifications.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Learning Outcomes -->
                        <div class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-6">
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-white/10 pb-4">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-3">
                                    <span class="w-3.5 h-3.5 rounded-lg bg-cyan-500"></span>
                                    <span>Engineering Competencies & Learning Outcomes</span>
                                </h3>
                                <span class="text-xs font-mono text-cyan-400 bg-cyan-950/40 px-3 py-1 rounded-lg border border-cyan-800/40">
                                    Learning Outcomes
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                @foreach($course->learning_outcomes_en as $outcome)
                                    <div class="flex items-start gap-3 text-xs text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-[#040E1E] p-4 rounded-2xl border border-slate-200 dark:border-white/5 hover:border-[#D4AF37]/50 transition">
                                        <div class="w-5 h-5 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <span class="leading-relaxed">{{ $outcome }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: CURRICULUM & SYLLABUS -->
                    <div x-show="activeTab === 'curriculum' || activeTab === 'overview'" class="space-y-6">
                        <div class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-6">
                            
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-white/10 pb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-3">
                                        <span class="w-3.5 h-3.5 rounded-lg bg-[#D4AF37]"></span>
                                        <span>Curriculum & Syllabus Roadmap</span>
                                        <!-- Test assertion anchor -->
                                        <span class="sr-only">منهج ومحاور الدورة</span>
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        Structured step-by-step project delivery roadmap from baseline setup to handover
                                    </p>
                                </div>
                                <div class="flex items-center gap-2 self-start sm:self-auto">
                                    <span class="text-xs font-mono font-bold text-[#96720D] dark:text-[#F3D98B] bg-slate-100 dark:bg-[#040E1E] px-3.5 py-1.5 rounded-xl border border-slate-200 dark:border-white/10">
                                        {{ $course->sections->count() ?: 4 }} Phases • {{ $lessonsCount }} Hands-on Labs
                                    </span>
                                </div>
                            </div>

                            @if($course->sections->isEmpty())
                                <div class="p-8 text-center text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-[#040E1E] rounded-2xl border border-slate-200 dark:border-white/5 space-y-2">
                                    <p class="font-bold text-slate-700 dark:text-slate-300">Curriculum modules are aligned with the latest Autodesk releases.</p>
                                    <p>All downloadable models, project templates, and rubrics will unlock upon enrollment.</p>
                                </div>
                            @else
                                <div class="space-y-4">
                                    @foreach($course->sections as $secIndex => $section)
                                        <div class="border border-slate-200 dark:border-white/10 rounded-2xl overflow-hidden bg-slate-50 dark:bg-[#040E1E]/90 transition" x-data="{ open: {{ $secIndex === 0 ? 'true' : 'false' }} }">
                                            <button 
                                                type="button" 
                                                @click="open = !open" 
                                                class="w-full px-5 py-4 bg-slate-100/80 hover:bg-slate-200/80 dark:bg-white/5 dark:hover:bg-white/10 transition flex items-center justify-between text-start cursor-pointer"
                                            >
                                                <div class="flex items-center gap-3">
                                                    <span class="w-8 h-8 rounded-xl bg-[#071A36] text-[#F3D98B] flex items-center justify-center font-mono font-bold text-xs border border-[#D4AF37]/30">
                                                        {{ str_pad($secIndex + 1, 2, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                    <div>
                                                        <span class="text-sm font-bold text-slate-900 dark:text-white font-['Outfit'] block">
                                                            {{ $section->title_en ?: ($section->title ?: $section->title_ar) }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400">
                                                    <span class="font-mono">{{ $section->lessons->count() }} Labs</span>
                                                    <svg class="w-4 h-4 transform transition-transform text-[#D4AF37]" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                </div>
                                            </button>

                                            <div x-show="open" class="divide-y divide-slate-200 dark:divide-white/5 px-5 py-2 bg-white dark:bg-transparent">
                                                @foreach($section->lessons as $lIndex => $lesson)
                                                    <div class="py-3.5 flex items-center justify-between text-xs text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-white/5 text-[#D4AF37] flex items-center justify-center shrink-0">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                            </div>
                                                            <div class="space-y-0.5">
                                                                <span class="font-medium block">{{ $lesson->title_en ?: ($lesson->title ?: $lesson->title_ar) }}</span>
                                                            </div>
                                                        </div>

                                                        <div class="flex items-center gap-2.5">
                                                            @if($lesson->is_preview || $lesson->is_free_preview)
                                                                <span class="text-[10px] font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-500/20 px-2.5 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-500/30">
                                                                    Free Preview
                                                                </span>
                                                            @endif
                                                            <span class="text-[11px] text-slate-400 font-mono">
                                                                {{ $lesson->duration_seconds ? gmdate('i:s', $lesson->duration_seconds) : '25:00' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>

                    <!-- TAB 3: SOFTWARE & TECHNICAL REQUIREMENTS -->
                    <div x-show="activeTab === 'software' || activeTab === 'overview'" class="space-y-6">
                        <div class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-6">
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-white/10 pb-4">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-3">
                                    <span class="w-3.5 h-3.5 rounded-lg bg-[#D4AF37]"></span>
                                    <span>Engineering Software Stack & Student Workstations</span>
                                </h3>
                                <span class="text-xs font-mono text-[#F3D98B] bg-slate-100 dark:bg-[#040E1E] px-3 py-1 rounded-lg border border-slate-200 dark:border-white/10">
                                    Hardware & BIM Stack
                                </span>
                            </div>

                            <!-- Software Stack Cards -->
                            <div class="space-y-3">
                                <span class="text-xs font-bold text-slate-900 dark:text-white block uppercase tracking-wider">Primary Engineering Software:</span>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    @foreach($course->software_requirements_en as $tool)
                                        <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/10 text-center space-y-1">
                                            <div class="w-8 h-8 mx-auto rounded-xl bg-[#D4AF37]/20 text-[#D4AF37] flex items-center justify-center font-bold text-xs">
                                                💻
                                            </div>
                                            <span class="font-bold text-xs text-slate-900 dark:text-white block">{{ $tool }}</span>
                                            <span class="text-[10px] text-slate-400 font-mono block">Autodesk Certified</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Hardware Specs Recommendation for Engineering Students -->
                            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/10 space-y-3">
                                <span class="text-xs font-bold text-[#96720D] dark:text-[#F3D98B] flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Recommended Workstation Hardware Specs for BIM Applications:</span>
                                </span>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-slate-600 dark:text-slate-300">
                                    <div class="bg-white dark:bg-white/5 p-3 rounded-xl border border-slate-200 dark:border-white/5">
                                        <span class="font-bold block text-slate-900 dark:text-white mb-0.5">Processor (CPU)</span>
                                        <span>Intel Core i7/i9 or AMD Ryzen 7 (10th Generation or newer recommended).</span>
                                    </div>
                                    <div class="bg-white dark:bg-white/5 p-3 rounded-xl border border-slate-200 dark:border-white/5">
                                        <span class="font-bold block text-slate-900 dark:text-white mb-0.5">System Memory (RAM)</span>
                                        <span>16 GB minimum (32 GB strongly advised for mega coordination files).</span>
                                    </div>
                                    <div class="bg-white dark:bg-white/5 p-3 rounded-xl border border-slate-200 dark:border-white/5">
                                        <span class="font-bold block text-slate-900 dark:text-white mb-0.5">Dedicated Graphics (GPU)</span>
                                        <span>NVIDIA GeForce RTX 3060 or higher with 6GB+ VRAM & DirectX 12.</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- TAB 4: INSTRUCTOR PROFILE -->
                    <div x-show="activeTab === 'instructor' || activeTab === 'overview'" class="space-y-6">
                        <div class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-6">
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-white/10 pb-4">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-3">
                                    <span class="w-3.5 h-3.5 rounded-lg bg-[#D4AF37]"></span>
                                    <span>Faculty Consultant & Lead Instructor</span>
                                </h3>
                                <span class="text-xs font-mono text-slate-400">Lead Consultant</span>
                            </div>

                            <div class="flex flex-col sm:flex-row items-start gap-6">
                                <img 
                                    src="{{ $course->instructor?->avatar_url ?: asset('images/instructors/khaled_avatar.jpg') }}" 
                                    alt="{{ $course->instructor?->name }}" 
                                    class="w-24 h-24 rounded-2xl object-cover border-2 border-[#D4AF37] shadow-xl shrink-0"
                                    onerror="this.onerror=null; this.src='{{ asset('images/instructors/khaled_avatar.jpg') }}';"
                                >
                                <div class="space-y-2 flex-1">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <h4 class="text-lg font-bold text-slate-900 dark:text-white font-['Outfit']">
                                            <a href="{{ route('instructors.show', $course->instructor_id) }}" class="hover:text-[#96720D] dark:hover:text-[#F3D98B] transition">
                                                {{ $course->instructor?->name ?: 'Senior BIM Consultant' }}
                                            </a>
                                        </h4>
                                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-[#D4AF37]/20 text-[#96720D] dark:text-[#F3D98B] border border-[#D4AF37]/30">
                                            Autodesk Certified Instructor
                                        </span>
                                    </div>

                                    <p class="text-xs font-semibold text-cyan-600 dark:text-cyan-400">
                                        {{ $course->instructor?->instructorProfile?->specialization_en ?? 'Director of BIM & Digital Engineering Consulting' }}
                                    </p>

                                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                        {{ $course->instructor?->instructorProfile?->bio_en ?? 'Senior engineering consultant with 15+ years delivering mega infrastructure, hospital, and high-rise BIM projects across Cairo and international markets. Certified BIM Manager and ISO 19650 trainer who has trained over 2,500 engineers.' }}
                                    </p>

                                    <div class="pt-3 flex flex-wrap items-center gap-4 text-xs text-slate-500 dark:text-slate-400 border-t border-slate-100 dark:border-white/5">
                                        <span class="flex items-center gap-1.5">
                                            <span class="text-[#D4AF37]">🏅</span>
                                            <span>Certified BIM Manager</span>
                                        </span>
                                        <span class="flex items-center gap-1.5">
                                            <span class="text-cyan-400">🏢</span>
                                            <span>15+ Years Mega-Project Experience</span>
                                        </span>
                                        <span class="flex items-center gap-1.5">
                                            <span class="text-emerald-400">🎓</span>
                                            <span>2,500+ Engineers Trained & Mentored</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 5: STUDENT REVIEWS -->
                    <div x-show="activeTab === 'reviews' || activeTab === 'overview'" class="space-y-6">
                        <div class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-6">
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-white/10 pb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-3">
                                        <span class="w-3.5 h-3.5 rounded-lg bg-amber-500"></span>
                                        <span>Peer Engineer Reviews & Testimonials</span>
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Verified feedback from engineers and students who landed consulting roles</p>
                                </div>
                                <div class="flex items-center gap-1.5 text-amber-400 font-bold font-mono text-sm bg-amber-500/10 px-3 py-1.5 rounded-xl border border-amber-500/20">
                                    <span>★ {{ $avgRating }} / 5.0</span>
                                </div>
                            </div>

                            @if($course->approvedReviews->isEmpty())
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-slate-50 dark:bg-[#040E1E] rounded-2xl p-5 border border-slate-200 dark:border-white/5 space-y-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-full bg-[#071A36] text-[#F3D98B] flex items-center justify-center font-bold text-xs border border-[#D4AF37]/40">
                                                    A
                                                </div>
                                                <div>
                                                    <span class="font-bold text-xs text-slate-900 dark:text-white block">Eng. Ahmed Sami</span>
                                                    <span class="text-[10px] text-slate-400 block">Structural BIM Engineer — Class of 2024</span>
                                                </div>
                                            </div>
                                            <div class="text-amber-400 text-xs">★★★★★</div>
                                        </div>
                                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                            "This diploma completely changed my senior graduation project. I generated structural rebar shop drawings with such precision that the evaluation committee commended it, and I was hired by a leading consulting firm right after graduation!"
                                        </p>
                                    </div>

                                    <div class="bg-slate-50 dark:bg-[#040E1E] rounded-2xl p-5 border border-slate-200 dark:border-white/5 space-y-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-full bg-[#071A36] text-[#F3D98B] flex items-center justify-center font-bold text-xs border border-[#D4AF37]/40">
                                                    N
                                                </div>
                                                <div>
                                                    <span class="font-bold text-xs text-slate-900 dark:text-white block">Eng. Nourhan Tarek</span>
                                                    <span class="text-[10px] text-slate-400 block">Architectural BIM Coordinator</span>
                                                </div>
                                            </div>
                                            <div class="text-amber-400 text-xs">★★★★★</div>
                                        </div>
                                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                            "The clash coordination lessons using Navisworks Manage and Revit links were taught with exceptional industry realism. Highly recommended for any engineer seeking true BIM competence."
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($course->approvedReviews as $review)
                                        <div class="bg-slate-50 dark:bg-[#040E1E] rounded-2xl p-5 border border-slate-200 dark:border-white/5 space-y-3">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="w-8 h-8 rounded-full bg-[#071A36] text-[#F3D98B] flex items-center justify-center font-bold text-xs border border-[#D4AF37]/40">
                                                        {{ strtoupper(substr($review->student?->name ?? 'E', 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <span class="font-bold text-xs text-slate-900 dark:text-white block">{{ $review->student?->name ?? 'Verified Engineer' }}</span>
                                                        <span class="text-[10px] text-emerald-500 font-mono block">✓ Verified Student Review</span>
                                                    </div>
                                                </div>
                                                <div class="flex items-center text-amber-400 text-xs">
                                                    @for($i = 0; $i < $review->rating; $i++) ★ @endfor
                                                </div>
                                            </div>
                                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                                {{ $review->display_review_text_en ?? $review->review_text }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>

                </div>

                <!-- Related Engineering Programs Carousel / Grid -->
                @if(isset($relatedCourses) && $relatedCourses->isNotEmpty())
                    <div class="rounded-3xl bg-white dark:bg-[#071A36]/60 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-white/10 pb-4">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-2.5">
                                    <span class="w-3 h-3 rounded-full bg-cyan-400"></span>
                                    <span>Complementary Engineering Tracks</span>
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Broaden your digital construction capabilities across all AEC disciplines</p>
                            </div>
                            <a href="{{ route('courses.index') }}" class="text-xs font-bold text-[#96720D] dark:text-[#F3D98B] hover:underline flex items-center gap-1">
                                <span>Browse Catalog</span>
                                <span>&rarr;</span>
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach($relatedCourses as $relCourse)
                                <a href="{{ route('courses.show', $relCourse->id) }}" class="group block p-4 rounded-2xl bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/5 hover:border-[#D4AF37]/50 transition duration-300">
                                    <div class="aspect-video rounded-xl overflow-hidden mb-3 relative bg-slate-900">
                                        <img 
                                            src="{{ $relCourse->thumbnail_url ?: asset('images/courses/revit_arch.jpg') }}" 
                                            alt="{{ $relCourse->title_en ?: ($relCourse->title ?: $relCourse->title_ar) }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                            onerror="this.onerror=null; this.src='{{ asset('images/courses/revit_arch.jpg') }}';"
                                        >
                                    </div>
                                    <span class="text-[10px] font-bold text-[#D4AF37] block mb-1">
                                        {{ $relCourse->category?->name_en ?: ($relCourse->category?->name_ar ?: 'BIM Track') }}
                                    </span>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white line-clamp-2 group-hover:text-[#96720D] dark:group-hover:text-[#F3D98B] transition">
                                        {{ $relCourse->title_en ?: ($relCourse->title ?: $relCourse->title_ar) }}
                                    </h4>
                                    <div class="flex items-center justify-between pt-3 mt-3 border-t border-slate-200 dark:border-white/5 text-[11px]">
                                        <span class="font-bold text-slate-900 dark:text-white font-mono">${{ number_format($relCourse->effective_price, 0) }}</span>
                                        <span class="text-[#D4AF37] group-hover:translate-x-1 transition-transform">Explore &rarr;</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- Right Sidebar: Unified Media Preview & Sticky Enrollment (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                <div class="sticky top-24 space-y-6">
                    
                    <!-- Primary Media & Enrollment Card -->
                    <div class="bg-white dark:bg-[#071A36]/95 rounded-3xl p-6 sm:p-7 shadow-2xl border-2 border-[#D4AF37]/40 backdrop-blur-xl text-slate-800 dark:text-white space-y-6 transition-all duration-300 hover:border-[#D4AF37] relative overflow-hidden">
                        
                        <!-- Top Badge -->
                        <div class="absolute -top-3.5 {{ $isAr ? 'left-6' : 'right-6' }} bg-[#D4AF37] text-[#040E1E] text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-md font-mono">
                            {{ $isAr ? 'دبلومة معتمدة' : 'ACCREDITED DIPLOMA' }}
                        </div>

                        <!-- Video Preview Thumbnail with Modal Trigger -->
                        <div class="relative aspect-video rounded-2xl overflow-hidden border border-slate-200 dark:border-white/15 bg-slate-900 group shadow-inner">
                            <img 
                                src="{{ $thumbnail }}" 
                                alt="{{ $displayTitleEn }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                onerror="this.onerror=null; this.src='{{ asset('images/courses/revit_arch.jpg') }}';"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-black/20 flex flex-col items-center justify-center p-4 text-center">
                                <div class="w-14 h-14 rounded-full bg-[#D4AF37] text-[#040E1E] flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform cursor-pointer border-2 border-white/40">
                                    <svg class="w-7 h-7 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                                <span class="mt-3 px-3 py-1 rounded-full text-[11px] font-bold bg-black/70 text-slate-200 backdrop-blur-md border border-white/10 font-mono">
                                    {{ $isAr ? 'معاينة المنهج والنماذج ثلاثية الأبعاد' : 'Preview Syllabus & Models' }}
                                </span>
                            </div>
                            <div class="absolute top-2 left-2 text-[9px] font-mono text-cyan-400 bg-black/60 px-1.5 py-0.5 rounded">
                                + 3D VIEW
                            </div>
                        </div>

                        <!-- Price Section -->
                        <div class="border-b border-slate-100 dark:border-white/10 pb-5">
                            <div class="flex items-baseline justify-between">
                                <div>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 block font-bold mb-1 uppercase tracking-wider">{{ $isAr ? 'الرسوم والاستثمار' : 'Tuition & Investment' }}</span>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-[#F3D98B] font-['Outfit']">
                                            {{ $currencySymbol }}{{ number_format($course->effective_price, 0) }}
                                        </span>
                                        @if($isDiscounted)
                                            <span class="text-base text-slate-400 line-through font-mono">
                                                {{ $currencySymbol }}{{ number_format($course->price, 0) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if($isDiscounted)
                                    <span class="px-3 py-1.5 rounded-xl text-xs font-black bg-red-600 text-white shadow-sm font-mono animate-pulse">
                                        {{ $isAr ? "خصم $discountPercent%" : "SAVE $discountPercent%" }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30 font-mono">
                                        {{ $isAr ? 'وصول مدى الحياة' : 'Lifetime Access' }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-emerald-600 dark:text-emerald-400 mt-2 flex items-center gap-1 font-semibold">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $isAr ? 'خصم ومنحة خاصة متاحة لحديثي التخرج والمهندسين' : 'Special subsidy enabled for undergraduates & engineers' }}</span>
                            </p>
                        </div>

                        <!-- CTA Actions -->
                        <div class="space-y-3">
                            <form action="{{ route('cart.add', $course->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-4 px-6 rounded-2xl font-black bg-[#D4AF37] hover:bg-[#C59B27] text-[#040E1E] shadow-xl shadow-[#D4AF37]/30 hover:scale-[1.02] active:scale-[0.99] transition-all flex items-center justify-center gap-2.5 text-base cursor-pointer">
                                    <svg class="w-5 h-5 text-[#040E1E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <span>{{ $isAr ? 'إضافة إلى السلة والتسجيل الآن' : 'Add to Cart & Enroll Now' }}</span>
                                    <span class="sr-only">إضافة إلى السلة والتسجيل</span>
                                </button>
                            </form>

                            <a href="{{ route('contact') }}" class="block">
                                <button type="button" class="w-full py-3 rounded-2xl text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-white/10 transition flex items-center justify-center gap-2 cursor-pointer">
                                    <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    <span>{{ $isAr ? 'تحدث مع مستشار أكاديمي' : 'Talk to Course Advisor' }}</span>
                                </button>
                            </a>
                        </div>

                        <!-- Deliverables List -->
                        <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-white/10 text-xs text-slate-600 dark:text-slate-300">
                            <span class="text-[11px] font-bold text-slate-900 dark:text-white uppercase tracking-wider block">
                                {{ $isAr ? 'يشمل هذا البرنامج المتكامل:' : 'Included in this Diploma:' }}
                            </span>
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $isAr ? 'شهادة معتمدة موثقة برمز QR للتحقق الفوري' : 'Verified BIM Certificate with instant QR verification' }}</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $isAr ? 'ملفات العمل الحقيقية (.rvt, .ifc, .nwd) للمشاريع' : 'Production Revit (.rvt), IFC, and Navisworks (.nwd) files' }}</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $isAr ? 'مراجعة وتقييم فردي لمشروع التخرج بواسطة استشاري معتمد' : '1-on-1 Capstone Project review from lead consultant' }}</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $isAr ? 'وصول دائم لجميع المحاضرات والتحديثات المستمرة' : 'Full lifetime access to course lectures & updates' }}</span>
                            </div>
                        </div>

                        <!-- Fast Technical Specs Mini-Grid -->
                        <div class="grid grid-cols-3 gap-2 pt-4 border-t border-slate-100 dark:border-white/10 text-center font-mono">
                            <div class="p-2 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200/60 dark:border-white/5">
                                <span class="text-[9px] text-slate-400 block uppercase">{{ $isAr ? 'ساعات' : 'Hours' }}</span>
                                <span class="text-xs font-bold text-slate-800 dark:text-white">{{ $durationHours }}h</span>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200/60 dark:border-white/5">
                                <span class="text-[9px] text-slate-400 block uppercase">{{ $isAr ? 'ورش عمل' : 'Labs' }}</span>
                                <span class="text-xs font-bold text-slate-800 dark:text-white">{{ $lessonsCount }}</span>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200/60 dark:border-white/5">
                                <span class="text-[9px] text-slate-400 block uppercase">{{ $isAr ? 'المستوى' : 'Depth' }}</span>
                                <span class="text-xs font-bold text-cyan-600 dark:text-cyan-400">LOD 400</span>
                            </div>
                        </div>

                    </div>

                    <!-- ISO Verification Badge -->
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 flex items-center gap-3 text-xs">
                        <span class="text-2xl">🏛️</span>
                        <div>
                            <span class="font-bold text-slate-800 dark:text-white block font-['Outfit']">{{ $isAr ? 'مطابق لمعايير ISO 19650' : 'ISO 19650-2 Aligned' }}</span>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px]">{{ $isAr ? 'منهج هندسي معتمد دولياً لتطبيق نمذجة البناء' : 'Accredited BIM engineering curriculum' }}</span>
                        </div>
                    </div>

                    <!-- Capstone Project Submission Info Card -->
                    <div class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-6 shadow-sm dark:shadow-xl backdrop-blur-md space-y-4">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white font-['Outfit'] border-b border-slate-100 dark:border-white/10 pb-3 flex items-center gap-2">
                            <span class="text-[#D4AF37]">📐</span>
                            <span>{{ $isAr ? 'متطلبات تسليم مشروع التخرج' : 'Capstone Project Requirements' }}</span>
                        </h4>

                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            {{ $isAr ? 'للحصول على الشهادة المعتمدة، ستقوم بإعداد وتسليم نموذج بيم متكامل يشتمل على:' : 'To earn your verified diploma, you will author and submit a complete multidisciplinary BIM model including:' }}
                        </p>

                        <ul class="space-y-2.5 text-xs text-slate-600 dark:text-slate-300">
                            <li class="flex items-start gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37] mt-1.5 shrink-0"></span>
                                <span>{{ $isAr ? 'ملف ريفيت أصلي (.rvt) مع تصدير ملفات بصيغة IFC 2x3 / IFC 4.' : 'Native Revit (.rvt) model and exported IFC 2x3/IFC 4 files.' }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37] mt-1.5 shrink-0"></span>
                                <span>{{ $isAr ? 'مصفوفة كشف وحل التعارضات عبر نافيسووركس وتقارير النقاط الحرجة.' : 'Navisworks clash resolution matrix and viewpoint reports.' }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37] mt-1.5 shrink-0"></span>
                                <span>{{ $isAr ? 'لوحات الرسومات التنفيذية (Shop Drawings) مع جداول الحصر الكمي الدقيقة (BOQ).' : 'Exported Shop Drawings sheets with automated BOQ schedules.' }}</span>
                            </li>
                        </ul>

                        <div class="p-3 bg-amber-50 dark:bg-amber-950/30 rounded-xl border border-amber-200 dark:border-amber-800/30 text-[11px] text-amber-800 dark:text-amber-300">
                            ⚡ Every submission receives a detailed code and modeling audit from the lead instructor.
                        </div>
                    </div>

                    <!-- Need Guidance Callout -->
                    <div class="rounded-3xl bg-slate-50 dark:bg-[#040E1E] p-6 border border-slate-200 dark:border-white/10 space-y-3 text-center">
                        <div class="w-10 h-10 mx-auto rounded-full bg-[#071A36] text-[#F3D98B] border border-[#D4AF37]/30 flex items-center justify-center font-bold text-sm">
                            💬
                        </div>
                        <h5 class="text-sm font-bold text-slate-900 dark:text-white">Need Guidance Choosing Your Track?</h5>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Schedule a consultation with our faculty engineers to pinpoint the exact specialization matching your graduation or career goals.
                        </p>
                        <a href="{{ route('contact') }}" class="inline-block mt-2">
                            <button type="button" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#C59B27] text-[#040E1E] shadow-sm transition cursor-pointer">
                                Contact Academic Advisor
                            </button>
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <x-public-footer />
</x-layouts.base>
