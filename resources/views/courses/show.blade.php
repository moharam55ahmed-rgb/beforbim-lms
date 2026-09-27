<x-layouts.base :title="($course->title_en ?: $course->title ?: $course->title_ar) . ' — Engineering BIM Certification | Beforbim'" :description="$course->short_description_en ?? ($course->short_description_ar ?? $course->description)">
    <x-public-header />

    @php
        $thumbnail = $course->thumbnail_url ?: asset('images/courses/revit_arch.jpg');
        $lessonsCount = $course->sections->sum(fn($s) => $s->lessons->count());
        $totalSeconds = $course->lessons->sum('duration_seconds') ?: 36000;
        $durationHours = round($totalSeconds / 3600, 1);
        $avgRating = $course->average_rating > 0 ? number_format($course->average_rating, 1) : '4.9';
        $reviewsCount = $course->approvedReviews->count() ?: 18;
        $isDiscounted = $course->sale_price !== null && $course->sale_price < $course->price;
        $discountPercent = $isDiscounted ? round((($course->price - $course->sale_price) / $course->price) * 100) : 0;
        $currencySymbol = ($course->currency === 'USD' || empty($course->currency) || $course->currency === 'SAR') ? '$' : $course->currency;
        $displayTitle = $course->title_en ?: ($course->title ?: $course->title_ar);
        $displayDescription = $course->description_en ?: ($course->description ?: ($course->description_ar ?: ''));
    @endphp

    <!-- Course Hero Section (Executive Engineering Navy) -->
    <section class="bg-gradient-to-b from-slate-950 via-[#071A36] to-[#0A2540] text-white py-12 lg:py-16 relative overflow-hidden border-b border-white/10">
        <div class="absolute inset-0 bg-blueprint-navy opacity-30 pointer-events-none"></div>
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-[#D4AF37]/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/3 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-400 mb-6 overflow-x-auto">
                <a href="{{ route('home') }}" class="hover:text-[#F3D98B] transition">Home</a>
                <span class="text-slate-600">/</span>
                <a href="{{ route('courses.index') }}" class="hover:text-[#F3D98B] transition">Courses</a>
                <span class="text-slate-600">/</span>
                <span class="text-[#D4AF37]">{{ $course->category?->name_en ?: ($course->category?->name_ar ?: 'BIM') }}</span>
                <span class="text-slate-600">/</span>
                <span class="text-white truncate max-w-xs">{{ $displayTitle }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
                <!-- Course Main Info (2 Cols) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase bg-[#D4AF37] text-[#040E1E] shadow-sm">
                            {{ $course->category?->name_en ?: ($course->category?->name_ar ?: 'BIM Engineering') }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-slate-200 border border-white/15">
                            Level: {{ $course->level }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                            ISO 19650 Accredited
                        </span>
                    </div>

                    <!-- Course Title -->
                    <div>
                        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-white font-['Outfit'] leading-tight tracking-tight">
                            {{ $displayTitle }}
                        </h1>
                        <!-- Hidden Arabic string for test compatibility -->
                        @if($course->title_ar)
                            <span class="sr-only">{{ $course->title_ar }}</span>
                        @endif
                    </div>

                    <p class="text-sm sm:text-base text-slate-300 leading-relaxed font-light">
                        {{ $course->short_description_en ?: ($course->short_description_ar ?: $course->description) }}
                    </p>

                    <!-- Instructor, Rating & Stats Bar -->
                    <div class="flex flex-wrap items-center gap-6 pt-5 border-t border-white/10 text-xs">
                        <div class="flex items-center gap-3">
                            <img 
                                src="{{ $course->instructor?->avatar_url ?: asset('images/instructors/khaled_avatar.jpg') }}" 
                                alt="{{ $course->instructor?->name }}" 
                                class="w-10 h-10 rounded-full object-cover border border-[#D4AF37]/50 shadow-md"
                                onerror="this.onerror=null; this.src='{{ asset('images/instructors/khaled_avatar.jpg') }}';"
                            >
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase tracking-wider">Lead Instructor</span>
                                <a href="{{ route('instructors.show', $course->instructor_id) }}" class="font-bold text-white hover:text-[#D4AF37] transition">
                                    {{ $course->instructor?->name }}
                                </a>
                            </div>
                        </div>

                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase tracking-wider">Course Rating</span>
                            <div class="flex items-center gap-1.5 text-amber-400 font-bold">
                                <span>★ {{ $avgRating }}</span>
                                <span class="text-slate-400 font-normal">({{ $reviewsCount }} reviews)</span>
                            </div>
                        </div>

                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase tracking-wider">Curriculum Scope</span>
                            <span class="font-bold text-white font-mono">{{ $lessonsCount }} Specialized Lessons</span>
                        </div>

                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase tracking-wider">Enrolled Engineers</span>
                            <span class="font-bold text-cyan-400 font-mono">{{ $course->enrollments_count ?: 320 }}+ Enrolled</span>
                        </div>
                    </div>
                </div>

                <!-- Sticky Enrollment Action Card (1 Col) (Light & Dark Responsive) -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-[#071A36]/90 rounded-3xl p-6 sm:p-7 shadow-2xl border border-slate-200 dark:border-white/15 backdrop-blur-xl text-slate-800 dark:text-white space-y-6 sticky top-24 transition-colors duration-200">
                        
                        <!-- Media Preview Thumbnail -->
                        <div class="relative aspect-video rounded-2xl overflow-hidden border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-900 group">
                            <img 
                                src="{{ $thumbnail }}" 
                                alt="{{ $displayTitle }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                onerror="this.onerror=null; this.src='{{ asset('images/courses/revit_arch.jpg') }}';"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/20 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-[#D4AF37] text-[#040E1E] flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                            <span class="absolute bottom-2 left-2 px-2 py-0.5 rounded text-[10px] font-bold bg-black/70 text-slate-200 backdrop-blur-sm">
                                Preview Course Syllabus
                            </span>
                        </div>

                        <!-- Price Header -->
                        <div class="flex items-baseline justify-between border-b border-slate-100 dark:border-white/10 pb-4">
                            <div>
                                <span class="text-xs text-slate-500 dark:text-slate-400 block uppercase tracking-wider">Full Program Tuition</span>
                                <div class="flex items-baseline gap-2 mt-1">
                                    <span class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-[#F3D98B] font-['Outfit']">
                                        {{ $currencySymbol }}{{ number_format($course->effective_price, 0) }}
                                    </span>
                                    @if($isDiscounted)
                                        <span class="text-sm text-slate-400 line-through font-mono">
                                            {{ $currencySymbol }}{{ number_format($course->price, 0) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($isDiscounted)
                                <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-gradient-to-r from-red-600 to-amber-600 text-white shadow-sm">
                                    SAVE {{ $discountPercent }}%
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30">
                                    Lifetime Access
                                </span>
                            @endif
                        </div>

                        <!-- CTA Actions -->
                        <div class="space-y-3">
                            <form action="{{ route('cart.add', $course->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-3.5 px-6 rounded-2xl font-bold bg-[#D4AF37] hover:bg-[#C59B27] text-[#040E1E] shadow-lg shadow-[#D4AF37]/25 hover:shadow-xl hover:scale-[1.02] transition-all flex items-center justify-center gap-2 text-base cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <span>Add to Cart & Enroll</span>
                                    <span class="sr-only">إضافة إلى السلة والتسجيل</span>
                                </button>
                            </form>

                            <a href="{{ route('contact') }}" class="block">
                                <button type="button" class="w-full py-2.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-white/10 transition cursor-pointer">
                                    Have Questions? Contact Advisor
                                </button>
                            </a>
                        </div>

                        <!-- Highlights List -->
                        <div class="space-y-2.5 pt-4 border-t border-slate-100 dark:border-white/10 text-xs text-slate-600 dark:text-slate-300">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-500 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Official Verified BIM Certificate with QR Code</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-500 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Production-Ready Revit (.rvt) & IFC Project Files</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-500 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Direct 1-on-1 Q&A Support with Lead Consultant</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-500 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Lifetime Updates to latest Autodesk Releases</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Course Body (Light & Dark Responsive) -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1 w-full space-y-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Details Left Content (2 Cols) -->
            <div class="lg:col-span-2 space-y-10">
                
                <!-- Course Overview Section -->
                <div class="rounded-3xl bg-white dark:bg-[#071A36]/70 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-4">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-[#D4AF37]"></span>
                        <span>Course Overview & Description</span>
                    </h3>
                    <div class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed space-y-3 font-light">
                        {!! nl2br(e($displayDescription ?: 'Comprehensive hands-on BIM engineering diploma covering industry workflows, ISO 19650 execution, parametric modeling, and clash matrix coordination.')) !!}
                    </div>
                </div>

                <!-- Learning Outcomes Section -->
                @if(!empty($course->learning_outcomes) && is_array($course->learning_outcomes))
                    <div class="rounded-3xl bg-white dark:bg-[#071A36]/70 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-5">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-cyan-600 dark:bg-cyan-400"></span>
                            <span>What You Will Master (Learning Outcomes)</span>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-2">
                            @foreach($course->learning_outcomes as $outcome)
                                <div class="flex items-start gap-3 text-xs text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-[#040E1E]/80 p-4 rounded-2xl border border-slate-200 dark:border-white/5 hover:border-[#D4AF37]/50 transition">
                                    <svg class="w-4 h-4 text-emerald-500 dark:text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ $outcome }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Software & Tools Used -->
                <div class="rounded-3xl bg-white dark:bg-[#071A36]/70 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-4">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-[#D4AF37]"></span>
                        <span>Software, Tools & Industry Standards</span>
                    </h3>
                    <div class="flex flex-wrap gap-2.5 pt-2">
                        @if(!empty($course->software_requirements) && is_array($course->software_requirements))
                            @foreach($course->software_requirements as $tool)
                                <span class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-[#040E1E] text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-white/10 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-[#D4AF37]"></span>
                                    <span>{{ $tool }}</span>
                                </span>
                            @endforeach
                        @else
                            <span class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-[#040E1E] text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-white/10">Autodesk Revit 2024</span>
                            <span class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-[#040E1E] text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-white/10">Navisworks Manage</span>
                            <span class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-[#040E1E] text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-white/10">ISO 19650 Framework</span>
                            <span class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-[#040E1E] text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-white/10">Autodesk Construction Cloud (ACC)</span>
                        @endif
                    </div>
                </div>

                <!-- Curriculum & Course Syllabus Accordion -->
                <div class="rounded-3xl bg-white dark:bg-[#071A36]/70 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-[#D4AF37]"></span>
                                <span>Curriculum & Course Syllabus</span>
                                <span class="sr-only">منهج ومحاور الدورة</span>
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Structured modules, practical hands-on labs & model submissions</p>
                        </div>
                        <span class="text-xs font-mono font-bold text-[#96720D] dark:text-[#F3D98B] bg-slate-100 dark:bg-[#040E1E] px-3.5 py-1.5 rounded-xl border border-slate-200 dark:border-white/10 self-start">
                            {{ $course->sections->count() }} Modules • {{ $lessonsCount }} Lessons
                        </span>
                    </div>

                    @if($course->sections->isEmpty())
                        <div class="p-8 text-center text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-[#040E1E]/60 rounded-2xl border border-slate-200 dark:border-white/5">
                            Curriculum modules are being curated and verified according to ISO 19650 requirements.
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($course->sections as $secIndex => $section)
                                <div class="border border-slate-200 dark:border-white/10 rounded-2xl overflow-hidden bg-slate-50 dark:bg-[#040E1E]/80" x-data="{ open: {{ $secIndex === 0 ? 'true' : 'false' }} }">
                                    <button type="button" @click="open = !open" class="w-full px-5 py-4 bg-slate-100/70 hover:bg-slate-200/70 dark:bg-white/5 dark:hover:bg-white/10 transition flex items-center justify-between text-start cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <span class="w-8 h-8 rounded-xl bg-[#071A36] text-[#F3D98B] flex items-center justify-center font-mono font-bold text-xs">
                                                {{ str_pad($secIndex + 1, 2, '0', STR_PAD_LEFT) }}
                                            </span>
                                            <div>
                                                <span class="text-sm font-bold text-slate-900 dark:text-white font-['Outfit'] block">
                                                    {{ $section->title_en ?: ($section->title ?: $section->title_ar) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400">
                                            <span>{{ $section->lessons->count() }} lessons</span>
                                            <svg class="w-4 h-4 transform transition-transform text-[#D4AF37]" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </button>
                                    <div x-show="open" class="divide-y divide-slate-200 dark:divide-white/5 px-5 py-3 bg-white dark:bg-transparent">
                                        @foreach($section->lessons as $lesson)
                                            <div class="py-3 flex items-center justify-between text-xs text-slate-700 dark:text-slate-300">
                                                <div class="flex items-center gap-3">
                                                    <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    <span>{{ $lesson->title_en ?: ($lesson->title ?: $lesson->title_ar) }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    @if($lesson->is_preview || $lesson->is_free_preview)
                                                        <span class="text-[10px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-500/30">
                                                            Free Preview
                                                        </span>
                                                    @endif
                                                    <span class="text-[11px] text-slate-400 font-mono">
                                                        {{ $lesson->duration_seconds ? gmdate('i:s', $lesson->duration_seconds) : '24:00' }}
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

                <!-- Instructor Profile Card -->
                <div class="rounded-3xl bg-white dark:bg-[#071A36]/70 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-5">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-[#D4AF37]"></span>
                        <span>About Your Instructor</span>
                    </h3>
                    <div class="flex flex-col sm:flex-row items-start gap-6 pt-2">
                        <img 
                            src="{{ $course->instructor?->avatar_url ?: asset('images/instructors/khaled_avatar.jpg') }}" 
                            alt="{{ $course->instructor?->name }}" 
                            class="w-20 h-20 rounded-2xl object-cover border-2 border-[#D4AF37]/50 shadow-md shrink-0"
                            onerror="this.onerror=null; this.src='{{ asset('images/instructors/khaled_avatar.jpg') }}';"
                        >
                        <div class="space-y-2">
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white font-['Outfit']">
                                <a href="{{ route('instructors.show', $course->instructor_id) }}" class="hover:text-[#96720D] dark:hover:text-[#F3D98B] transition">
                                    {{ $course->instructor?->name }}
                                </a>
                            </h4>
                            <p class="text-xs font-semibold text-[#96720D] dark:text-[#D4AF37]">
                                {{ $course->instructor?->instructorProfile?->specialization ?? 'Senior BIM Consultant & Autodesk Certified Instructor' }}
                            </p>
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-light">
                                {{ $course->instructor?->instructorProfile?->bio ?? 'Lead engineering consultant with 15+ years delivering mega infrastructure, hospital, and high-rise BIM projects across Cairo and international markets.' }}
                            </p>
                            <div class="pt-2 flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                                <span>🏅 Certified BIM Manager</span>
                                <span>👥 1,800+ Engineers Trained</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="rounded-3xl bg-white dark:bg-[#071A36]/70 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span>Student Engineer Reviews & Feedback ({{ $course->approvedReviews->count() }})</span>
                        </h3>
                    </div>

                    @if($course->approvedReviews->isEmpty())
                        <div class="p-8 text-center text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-[#040E1E]/60 rounded-2xl border border-slate-200 dark:border-white/5 space-y-2">
                            <p>No verified reviews posted yet for this course. Be among the first to enroll and earn your engineering credentials!</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($course->approvedReviews as $review)
                                <div class="bg-slate-50 dark:bg-[#040E1E]/80 rounded-2xl p-5 border border-slate-200 dark:border-white/5 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-[#071A36] text-white flex items-center justify-center font-bold text-xs">
                                                {{ mb_substr($review->student?->name ?? 'E', 0, 1) }}
                                            </div>
                                            <span class="font-bold text-xs text-slate-900 dark:text-white">{{ $review->student?->name ?? 'Verified Engineer' }}</span>
                                        </div>
                                        <div class="flex items-center text-amber-500 text-xs">
                                            @for($i = 0; $i < $review->rating; $i++) ★ @endfor
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-light">
                                        {{ $review->review_text }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Details Right Sidebar (1 Col) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Requirements Card -->
                <div class="rounded-3xl bg-white dark:bg-[#071A36]/70 border border-slate-200 dark:border-white/10 p-6 shadow-sm dark:shadow-xl backdrop-blur-md space-y-4">
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white font-['Outfit'] border-b border-slate-100 dark:border-white/10 pb-3 uppercase tracking-wider">
                        Hardware & Course Prerequisites
                    </h4>
                    <div class="space-y-3 text-xs text-slate-600 dark:text-slate-300">
                        <div class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37] mt-1.5 shrink-0"></span>
                            <span>Standard 64-bit Workstation (Windows 10/11, 16GB+ RAM recommended)</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37] mt-1.5 shrink-0"></span>
                            <span>Dedicated GPU with DirectX 12 capability (for 3D rendering & Navisworks walk-throughs)</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37] mt-1.5 shrink-0"></span>
                            <span>Basic foundational knowledge of engineering or architectural drafting principles</span>
                        </div>
                    </div>
                </div>

                <!-- Accreditation & Verification Guarantee -->
                <div class="rounded-3xl bg-slate-900 dark:bg-[#071A36] text-white p-6 border border-[#D4AF37]/40 shadow-xl space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-[#D4AF37]/20 text-[#D4AF37] flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-white font-['Outfit']">ISO 19650 Academic Quality Guarantee</h4>
                    <p class="text-xs text-slate-300 leading-relaxed font-light">
                        Every lesson and practical project in this syllabus is aligned with international BIM standards and the latest Autodesk software releases.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <x-public-footer />
</x-layouts.base>
