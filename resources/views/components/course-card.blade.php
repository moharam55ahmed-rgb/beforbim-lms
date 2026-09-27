@props(['course'])

@php
    $lessonsCount = $course->lessons_count ?? $course->lessons()->count();
    $totalSeconds = $course->lessons->sum('duration_seconds') ?: 36000;
    $durationHours = round($totalSeconds / 3600, 1);
    
    // Rating calculation
    $avgRating = $course->reviews_avg_rating ?? ($course->reviews()->avg('rating') ?: 4.9);
    $reviewsCount = $course->reviews_count ?? ($course->reviews()->count() ?: 18);
    $studentsCount = $course->enrollments_count ?? ($course->enrollments()->count() ?: 240);

    // Image fallback
    $thumbnail = $course->thumbnail_url ?: asset('images/courses/revit_arch.jpg');
    
    // Pricing
    $isDiscounted = $course->sale_price && $course->sale_price < $course->price;
    $discountPercent = $isDiscounted ? round((($course->price - $course->sale_price) / $course->price) * 100) : 0;
    $currencySymbol = ($course->currency === 'USD' || empty($course->currency) || $course->currency === 'SAR') ? '$' : $course->currency;

    // English-first title and summary
    $displayTitle = $course->title_en ?: ($course->title ?: $course->title_ar);
    $displayDescription = $course->short_description_en ?: ($course->description ?: ($course->short_description_ar ?: ''));
@endphp

<article class="group relative rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 hover:border-[#D4AF37] dark:hover:border-[#D4AF37]/60 shadow-sm dark:shadow-xl dark:shadow-black/40 hover:shadow-xl hover:shadow-[#D4AF37]/10 transition-all duration-300 flex flex-col justify-between overflow-hidden">
    
    <!-- Course Media Thumbnail & Badges -->
    <div class="relative aspect-video w-full overflow-hidden bg-slate-100 dark:bg-slate-900">
        <img 
            src="{{ $thumbnail }}" 
            alt="{{ $displayTitle }}" 
            class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
            loading="lazy"
            onerror="this.onerror=null; this.src='{{ asset('images/courses/revit_arch.jpg') }}';"
        >
        
        <!-- Subtle Top & Bottom Gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20 pointer-events-none"></div>

        <!-- Top Badges -->
        <div class="absolute top-3 inset-x-3 flex items-center justify-between pointer-events-none">
            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase bg-black/60 backdrop-blur-md text-[#F3D98B] border border-white/10 shadow-md">
                {{ $course->category?->name_en ?: ($course->category?->name_ar ?: 'BIM Engineering') }}
            </span>

            @if($isDiscounted)
                <span class="px-2.5 py-1 rounded-full text-[11px] font-black tracking-wide bg-gradient-to-r from-red-600 to-amber-600 text-white shadow-lg">
                    -{{ $discountPercent }}% OFF
                </span>
            @else
                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-600/90 text-white backdrop-blur-md">
                    ISO 19650
                </span>
            @endif
        </div>

        <!-- Level Pill on bottom thumbnail -->
        <div class="absolute bottom-2.5 left-3">
            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-black/70 backdrop-blur-sm text-slate-200 border border-white/10">
                {{ $course->level }}
            </span>
        </div>
    </div>

    <!-- Course Content Body -->
    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
        
        <div>
            <!-- Course Title -->
            <h3 class="text-base sm:text-lg font-bold font-['Outfit'] text-slate-900 dark:text-white group-hover:text-[#96720D] dark:group-hover:text-[#F3D98B] transition-colors line-clamp-2 leading-snug">
                <a href="{{ route('courses.show', $course->id) }}" class="focus:outline-none">
                    {{ $displayTitle }}
                </a>
            </h3>

            <!-- Clean Description -->
            @if($displayDescription)
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1.5 line-clamp-2 leading-relaxed">
                    {{ $displayDescription }}
                </p>
            @endif
        </div>

        <!-- Instructor Row -->
        <div class="flex items-center gap-3 pt-2 border-t border-slate-100 dark:border-white/5">
            <img 
                src="{{ $course->instructor?->avatar_url ?: asset('images/instructors/khaled_avatar.jpg') }}" 
                alt="{{ $course->instructor?->name }}" 
                class="w-8 h-8 rounded-full object-cover border border-[#D4AF37]/40 shrink-0"
                onerror="this.onerror=null; this.src='{{ asset('images/instructors/khaled_avatar.jpg') }}';"
            >
            <div class="min-w-0 flex-1">
                <span class="block text-xs font-semibold text-slate-800 dark:text-slate-200 truncate">{{ $course->instructor?->name }}</span>
                <span class="block text-[10px] text-slate-500 dark:text-slate-400 truncate">{{ $course->instructor?->engineering_title ?: 'Senior BIM Consultant' }}</span>
            </div>
        </div>

        <!-- Metrics Grid (Duration, Lessons, Rating, Students) -->
        <div class="grid grid-cols-2 gap-2 text-[11px] text-slate-600 dark:text-slate-300 pt-2 border-t border-slate-100 dark:border-white/5">
            <div class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ $durationHours > 0 ? $durationHours : '16' }} hrs duration</span>
            </div>
            <div class="flex items-center gap-1.5 justify-end">
                <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>{{ $lessonsCount }} Modules</span>
            </div>
            <div class="flex items-center gap-1">
                <div class="flex text-amber-500">
                    <span>★</span>
                </div>
                <span class="font-bold text-slate-900 dark:text-white">{{ number_format($avgRating, 1) }}</span>
                <span class="text-slate-400 text-[10px]">({{ $reviewsCount }})</span>
            </div>
            <div class="flex items-center gap-1.5 justify-end text-slate-500 dark:text-slate-400 text-[10px]">
                <svg class="w-3 h-3 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>{{ $studentsCount }}+ enrolled</span>
            </div>
        </div>

    </div>

    <!-- Course Footer Card (Price & Action CTAs) -->
    <div class="px-5 py-3.5 bg-slate-50 dark:bg-[#040E1E] border-t border-slate-100 dark:border-white/10 flex items-center justify-between">
        
        <!-- Price display -->
        <div class="flex flex-col">
            @if($isDiscounted)
                <div class="flex items-baseline gap-1.5">
                    <span class="text-lg font-black font-['Outfit'] text-[#071A36] dark:text-[#F3D98B]">
                        {{ $currencySymbol }}{{ number_format($course->sale_price, 0) }}
                    </span>
                    <span class="text-xs text-slate-400 line-through">
                        {{ $currencySymbol }}{{ number_format($course->price, 0) }}
                    </span>
                </div>
            @else
                <span class="text-lg font-black font-['Outfit'] text-[#071A36] dark:text-[#F3D98B]">
                    {{ $course->price > 0 ? $currencySymbol . number_format($course->price, 0) : 'Free' }}
                </span>
            @endif
            <span class="text-[9px] text-slate-500 dark:text-slate-400 uppercase tracking-wider">Lifetime Access</span>
        </div>

        <!-- CTA Buttons -->
        <div class="flex items-center gap-2">
            <a 
                href="{{ route('courses.show', $course->id) }}" 
                class="px-4 py-2 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#C59B27] text-[#071A36] shadow-sm hover:shadow transition-all flex items-center gap-1.5"
            >
                <span>View Details</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</article>
