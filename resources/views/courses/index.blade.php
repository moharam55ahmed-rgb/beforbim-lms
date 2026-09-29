<x-layouts.base title="Engineering Courses & BIM Certification Programs — Beforbim" description="Explore industry-standard BIM diplomas, Revit architectural, structural, MEP modeling, clash detection, and automation engineering courses.">
    <x-public-header />

    <!-- Courses Catalog Hero (Light default with Dark option) -->
    <section class="relative bg-gradient-to-b from-white via-slate-50 to-slate-100 dark:from-[#040E1E] dark:via-[#071A36] dark:to-[#040E1E] text-slate-900 dark:text-white py-14 lg:py-20 overflow-hidden border-b border-slate-200/80 dark:border-white/10 transition-colors duration-200">
        <!-- Blueprint Grid & Ambient Glows -->
        <div class="absolute inset-0 bg-cad-grid opacity-15 dark:opacity-20 pointer-events-none"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#D4AF37]/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div class="max-w-2xl space-y-3">
                    <div class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-[#071A36]/5 dark:bg-white/10 border border-[#D4AF37]/40 text-[#8B6B15] dark:text-[#F3D98B] text-xs font-semibold backdrop-blur-md">
                        <img src="{{ asset('images/branding/logo.png') }}" alt="" class="w-4 h-4 object-contain">
                        <span>ISO 19650 Certified Curriculum</span>
                        <span class="sr-only">المسارات والتخصصات</span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl font-black font-['Outfit'] text-[#071A36] dark:text-white tracking-tight leading-tight">
                        Explore BIM & <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#D4AF37] via-[#C59B27] to-amber-600">Engineering Diplomas</span>
                    </h1>
                    
                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed font-light">
                        Master Building Information Modeling from foundational parametric drafting to advanced 4D/5D clash management, digital twins, and Python automation.
                    </p>
                </div>

                <!-- Live Quick Stats -->
                <div class="flex items-center gap-6 p-4 rounded-2xl bg-white dark:bg-[#071A36] border border-slate-200 dark:border-white/10 backdrop-blur-md shrink-0 shadow-lg">
                    <div>
                        <span class="block text-2xl font-black font-['Outfit'] text-[#071A36] dark:text-[#F3D98B]">{{ $courses->total() }}</span>
                        <span class="text-[11px] text-slate-500 dark:text-slate-400 uppercase tracking-wider font-mono">Active Courses</span>
                    </div>
                    <div class="h-8 w-px bg-slate-200 dark:bg-white/10"></div>
                    <div>
                        <span class="block text-2xl font-black font-['Outfit'] text-emerald-600 dark:text-emerald-400">100%</span>
                        <span class="text-[11px] text-slate-500 dark:text-slate-400 uppercase tracking-wider font-mono">Hands-on Lab</span>
                    </div>
                    <div class="h-8 w-px bg-slate-200 dark:bg-white/10"></div>
                    <div>
                        <span class="block text-2xl font-black font-['Outfit'] text-[#D4AF37]">LOD 350</span>
                        <span class="text-[11px] text-slate-500 dark:text-slate-400 uppercase tracking-wider font-mono">Industry Spec</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Catalog View -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14 flex-1 w-full space-y-8 bg-slate-50 dark:bg-[#040E1E] transition-colors duration-200">
        @if(session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif

        <!-- Filter & Search Controls Bar -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 p-4 rounded-2xl bg-white dark:bg-[#071A36] border border-slate-200 dark:border-white/10 shadow-sm">
            
            <!-- Category Pills -->
            <div class="flex flex-wrap items-center gap-2">
                <a 
                    href="{{ route('courses.index') }}" 
                    class="px-4 py-2 rounded-full text-xs font-bold transition-all {{ !request('category') ? 'bg-[#071A36] text-[#F3D98B] dark:bg-[#D4AF37] dark:text-[#040E1E] shadow-sm' : 'bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 hover:bg-slate-200 border border-slate-200 dark:border-white/10' }}"
                >
                    All Disciplines
                </a>
                <a 
                    href="{{ route('courses.index', ['category' => 'architecture']) }}" 
                    class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ request('category') === 'architecture' ? 'bg-[#071A36] text-[#F3D98B] dark:bg-[#D4AF37] dark:text-[#040E1E]' : 'bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 hover:bg-slate-200 border border-slate-200 dark:border-white/10' }}"
                >
                    Architecture
                </a>
                <a 
                    href="{{ route('courses.index', ['category' => 'structural']) }}" 
                    class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ request('category') === 'structural' ? 'bg-[#071A36] text-[#F3D98B] dark:bg-[#D4AF37] dark:text-[#040E1E]' : 'bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 hover:bg-slate-200 border border-slate-200 dark:border-white/10' }}"
                >
                    Structural
                </a>
                <a 
                    href="{{ route('courses.index', ['category' => 'mep']) }}" 
                    class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ request('category') === 'mep' ? 'bg-[#071A36] text-[#F3D98B] dark:bg-[#D4AF37] dark:text-[#040E1E]' : 'bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 hover:bg-slate-200 border border-slate-200 dark:border-white/10' }}"
                >
                    MEP Systems
                </a>
                <a 
                    href="{{ route('courses.index', ['category' => 'coordination']) }}" 
                    class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ request('category') === 'coordination' ? 'bg-[#071A36] text-[#F3D98B] dark:bg-[#D4AF37] dark:text-[#040E1E]' : 'bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 hover:bg-slate-200 border border-slate-200 dark:border-white/10' }}"
                >
                    BIM Coordination
                </a>
                <a 
                    href="{{ route('courses.index', ['category' => 'automation']) }}" 
                    class="px-4 py-2 rounded-full text-xs font-semibold transition-all {{ request('category') === 'automation' ? 'bg-[#071A36] text-[#F3D98B] dark:bg-[#D4AF37] dark:text-[#040E1E]' : 'bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 hover:bg-slate-200 border border-slate-200 dark:border-white/10' }}"
                >
                    Dynamo & Automation
                </a>
            </div>

            <!-- Search and Sort form -->
            <form action="{{ route('courses.index') }}" method="GET" class="flex items-center gap-2">
                <div class="relative w-full sm:w-64">
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ request('q') }}" 
                        placeholder="Search BIM courses..." 
                        class="w-full bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2 pl-9 text-xs text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37]"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#071A36] hover:bg-[#0D224D] dark:bg-[#D4AF37] dark:hover:bg-[#F3D98B] text-white dark:text-[#040E1E] transition">
                    Filter
                </button>
            </form>
        </div>

        <!-- Course Cards Grid -->
        @if($courses->isEmpty())
            <div class="p-16 rounded-3xl bg-white dark:bg-[#071A36] border border-slate-200 dark:border-white/10 text-center space-y-4 shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 flex items-center justify-center mx-auto text-slate-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white font-['Outfit']">No engineering courses found</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-sm mx-auto">Try adjusting your category filter or search query. Our team regularly publishes new specialized diplomas.</p>
                </div>
                <a href="{{ route('courses.index') }}" class="inline-block px-5 py-2 rounded-full text-xs font-bold bg-[#D4AF37] text-[#040E1E]">
                    Clear Filters
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($courses as $course)
                    <x-course-card :course="$course" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-6">
                {{ $courses->links() }}
            </div>
        @endif

        <!-- Academic Certification Standards Note -->
        <div class="p-6 rounded-3xl bg-white dark:bg-[#071A36] border border-slate-200 dark:border-[#D4AF37]/30 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#D4AF37]/15 border border-[#D4AF37]/30 flex items-center justify-center text-[#96720D] dark:text-[#F3D98B] shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white font-['Outfit']">Need an Enterprise or Team Training Program?</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-400">We deliver on-site and remote tailored corporate training for engineering firms across Egypt and the MENA region.</p>
                </div>
            </div>
            <a href="{{ route('contact') }}" class="px-6 py-2.5 rounded-full text-xs font-bold bg-[#D4AF37] hover:bg-[#C59B27] text-[#040E1E] transition shrink-0 shadow-sm">
                Inquire for Teams &rarr;
            </a>
        </div>
    </main>

    <x-public-footer />
</x-layouts.base>
