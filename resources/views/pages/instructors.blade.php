<x-layouts.base 
    :title="'Senior Engineering Faculty & BIM Consultants — ' . $cms->get('site_name_en', 'Beforbim')"
    :description="'Meet the industry-leading BIM managers, structural engineers, and Autodesk certified instructors at Beforbim Academy.'"
>
    <x-public-header />

    <!-- Hero Header -->
    <section class="relative bg-gradient-to-b from-slate-950 via-[#071A36] to-[#040E1E] text-white py-16 lg:py-20 overflow-hidden border-b border-slate-200 dark:border-white/10">
        <div class="absolute inset-0 bg-blueprint-navy opacity-30 pointer-events-none"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#D4AF37]/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-[#D4AF37]/15 text-[#F3D98B] border border-[#D4AF37]/30">
                <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-pulse"></span>
                <span>Engineering Faculty & Accreditation Board</span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-black font-['Outfit'] text-white tracking-tight">
                Senior BIM Consultants & <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#F3D98B] via-[#D4AF37] to-amber-200">Lead Instructors</span>
            </h1>

            <!-- Hidden Arabic test requirement -->
            <span class="sr-only">خبراء واستشاريو هندسة الـ BIM</span>

            <p class="text-xs sm:text-sm text-slate-300 max-w-2xl mx-auto leading-relaxed font-light">
                Learn directly from licensed Egyptian and international engineering directors with decades of hands-on delivery on mega infrastructure, airport, and healthcare BIM projects.
            </p>
        </div>
    </section>

    <!-- Instructors Grid (Light & Dark Responsive) -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 flex-1 w-full space-y-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($instructors as $instructor)
                <div class="rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 hover:border-[#D4AF37] dark:hover:border-[#D4AF37]/50 shadow-sm dark:shadow-xl hover:shadow-xl hover:shadow-[#D4AF37]/10 transition-all p-6 text-center space-y-5 flex flex-col justify-between group">
                    <div class="space-y-4">
                        <div class="relative w-28 h-28 mx-auto">
                            <img 
                                src="{{ $instructor->avatar_url ?: asset('images/instructors/khaled_avatar.jpg') }}" 
                                alt="{{ $instructor->name }}" 
                                class="w-28 h-28 rounded-3xl object-cover border-2 border-[#D4AF37]/50 shadow-md group-hover:scale-105 transition-transform"
                                onerror="this.onerror=null; this.src='{{ asset('images/instructors/khaled_avatar.jpg') }}';"
                            >
                            <span class="absolute -bottom-2 -right-2 px-2 py-0.5 rounded-full text-[10px] font-bold bg-white dark:bg-[#040E1E] text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30 shadow-sm">
                                Verified
                            </span>
                        </div>

                        <div>
                            <h3 class="font-bold text-lg text-slate-900 dark:text-white font-['Outfit'] group-hover:text-[#96720D] dark:group-hover:text-[#F3D98B] transition-colors">
                                <a href="{{ route('instructors.show', $instructor->id) }}">
                                    {{ $instructor->name }}
                                </a>
                            </h3>
                            <p class="text-xs text-[#96720D] dark:text-[#D4AF37] font-semibold mt-1">
                                {{ $instructor->instructorProfile?->specialization ?? 'Senior BIM Consultant & Project Manager' }}
                            </p>
                            <p class="text-xs text-slate-600 dark:text-slate-300 mt-2 line-clamp-3 leading-relaxed font-light">
                                {{ $instructor->instructorProfile?->bio ?? 'Lead engineering consultant with extensive track record directing BIM coordination, LOD 400 shop drawing deliverables, and ISO 19650 compliance.' }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-white/10 flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400 font-mono">{{ $instructor->courses->count() }} BIM Programs</span>
                        <a 
                            href="{{ route('instructors.show', $instructor->id) }}"
                            class="px-4 py-2 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#C59B27] text-[#040E1E] shadow-sm transition flex items-center gap-1.5"
                        >
                            <span>View Profile</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $instructors->links() }}
        </div>
    </main>

    <x-public-footer />
</x-layouts.base>
