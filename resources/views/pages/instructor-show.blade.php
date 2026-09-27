<x-layouts.base 
    :title="$instructor->name . ' — Faculty Profile & BIM Programs | Beforbim'"
    :description="$instructor->instructorProfile?->bio ?? 'Lead Engineering Instructor & BIM Consultant at Beforbim Academy.'"
>
    <x-public-header />

    <!-- Profile Header Hero -->
    <section class="relative bg-gradient-to-b from-slate-950 via-[#071A36] to-[#040E1E] text-white py-14 lg:py-18 overflow-hidden border-b border-slate-200 dark:border-white/10">
        <div class="absolute inset-0 bg-blueprint-navy opacity-30 pointer-events-none"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#D4AF37]/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8 text-center md:text-left">
                <div class="relative shrink-0">
                    <img 
                        src="{{ $instructor->avatar_url ?: asset('images/instructors/khaled_avatar.jpg') }}" 
                        alt="{{ $instructor->name }}" 
                        class="w-32 h-32 md:w-36 md:h-36 rounded-3xl object-cover border-2 border-[#D4AF37] shadow-2xl"
                        onerror="this.onerror=null; this.src='{{ asset('images/instructors/khaled_avatar.jpg') }}';"
                    >
                    <span class="absolute -bottom-2 -right-2 px-3 py-1 rounded-full text-xs font-bold bg-[#040E1E] text-emerald-400 border border-emerald-500/30 flex items-center gap-1.5 shadow-lg">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        Verified Lead
                    </span>
                </div>

                <div class="space-y-3 flex-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-[#D4AF37] text-[#040E1E]">
                        <span>Authorized Senior Instructor</span>
                        <span class="sr-only">مدرب معتمد بالأكاديمية</span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl font-black font-['Outfit'] text-white tracking-tight">
                        {{ $instructor->name }}
                    </h1>

                    <p class="text-base font-semibold text-[#F3D98B]">
                        {{ $instructor->instructorProfile?->specialization ?? 'Senior BIM Director & Construction Technology Consultant' }}
                    </p>

                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-6 text-xs text-slate-300 pt-3 border-t border-white/10 font-mono">
                        <div class="flex items-center gap-2">
                            <span class="text-[#D4AF37]">⏱</span>
                            <span>{{ $instructor->instructorProfile?->experience_years ?? '12' }}+ Years Industry Experience</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[#D4AF37]">📚</span>
                            <span>{{ $instructor->courses->count() }} Accredited Programs</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400">📍</span>
                            <span>Cairo, Egypt</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Profile Details & Courses (Light & Dark Responsive) -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex-1 w-full space-y-12">
        <!-- Bio Card -->
        <div class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-8 shadow-sm dark:shadow-xl backdrop-blur-md space-y-4">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-[#D4AF37]"></span>
                <span>Executive Bio & Professional Experience</span>
            </h3>
            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-light">
                {{ $instructor->instructorProfile?->bio ?? 'Senior BIM consultant with over a decade of practical execution in mega infrastructure, healthcare facilities, and high-rise commercial structures across Egypt and the MENA region. Holds key certifications in Autodesk Revit, Navisworks, and ISO 19650 Information Management.' }}
            </p>

            @if(!empty($instructor->instructorProfile?->certifications))
                <div class="pt-5 border-t border-slate-100 dark:border-white/10">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-3">
                        Professional Certifications & Accreditations:
                    </span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($instructor->instructorProfile->certifications as $cert)
                            <span class="px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-[#040E1E] text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-white/10 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37]"></span>
                                <span>{{ $cert }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Instructor's Courses Grid -->
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white font-['Outfit']">
                        Programs Led by {{ $instructor->name }} ({{ $instructor->courses->count() }})
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Explore all diplomas and specialized courses taught by this instructor.</p>
                </div>
            </div>

            @if($instructor->courses->isEmpty())
                <div class="p-12 text-center rounded-3xl bg-white dark:bg-[#071A36]/50 border border-slate-200 dark:border-white/10 text-xs text-slate-500 dark:text-slate-400 shadow-sm">
                    No active published courses under this instructor at the moment.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($instructor->courses as $course)
                        <x-course-card :course="$course" />
                    @endforeach
                </div>
            @endif
        </div>
    </main>

    <x-public-footer />
</x-layouts.base>
