@php
    $cms = app(\App\Modules\Setting\Services\CmsSettingService::class);
    $siteName = $cms->get('site_name_en', 'Beforbim');
    $tagline = $cms->get('site_tagline', 'The Premier BIM & Digital Construction Academy');
    $cartCount = 0;
    if (auth()->check()) {
        $cart = \App\Modules\Cart\Models\Cart::where('user_id', auth()->id())->withCount('items')->first();
        $cartCount = $cart?->items_count ?? 0;
    }
@endphp

<header 
    class="bg-white/95 dark:bg-[#070F1E]/95 text-slate-800 dark:text-white border-b border-slate-200/80 dark:border-white/10 sticky top-0 z-50 shadow-sm dark:shadow-2xl dark:shadow-black/40 backdrop-blur-xl transition-colors duration-200" 
    x-data="{ 
        mobileMenuOpen: false, 
        scrolled: false,
        isDark: document.documentElement.classList.contains('dark'),
        toggleTheme() {
            if (this.isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                this.isDark = false;
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                this.isDark = true;
            }
        }
    }" 
    @scroll.window="scrolled = (window.pageYOffset > 20)"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
        
        <!-- Brand Logo & Identity -->
        <div class="flex items-center gap-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3.5 group">
                <div class="relative flex items-center justify-center p-1 rounded-2xl bg-slate-100 dark:bg-gradient-to-br dark:from-[#071A36] dark:to-[#0A254D] border border-slate-200 dark:border-[#D4AF37]/40 shadow-sm group-hover:border-[#D4AF37] group-hover:scale-105 transition-all duration-300">
                    <img src="{{ asset('images/branding/logo.png') }}" alt="Beforbim LMS" class="h-10 w-auto sm:h-11 object-contain drop-shadow">
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xl font-black tracking-wider text-slate-900 dark:text-white font-['Outfit'] group-hover:text-[#C59B27] dark:group-hover:text-[#F3D98B] transition-colors">BEFORBIM</span>
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-[#D4AF37]/20 text-[#96720D] dark:text-[#F3D98B] border border-[#D4AF37]/30 tracking-widest font-mono">BIM</span>
                    </div>
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium tracking-wide hidden sm:block">Digital Construction Academy</span>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden lg:flex items-center gap-1 text-sm font-semibold tracking-wide">
                <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl transition duration-200 {{ request()->routeIs('home') ? 'bg-slate-100 text-[#071A36] dark:bg-white/10 dark:text-[#F3D98B] font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-white dark:hover:bg-white/5' }}">
                    Home
                </a>
                <a href="{{ route('courses.index') }}" class="px-3.5 py-2 rounded-xl transition duration-200 {{ request()->routeIs('courses.*') ? 'bg-slate-100 text-[#071A36] dark:bg-white/10 dark:text-[#F3D98B] font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-white dark:hover:bg-white/5' }}">
                    Courses
                </a>
                <a href="{{ route('instructors.index') }}" class="px-3.5 py-2 rounded-xl transition duration-200 {{ request()->routeIs('instructors.*') ? 'bg-slate-100 text-[#071A36] dark:bg-white/10 dark:text-[#F3D98B] font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-white dark:hover:bg-white/5' }}">
                    Faculty
                </a>
                <a href="{{ route('about') }}" class="px-3.5 py-2 rounded-xl transition duration-200 {{ request()->routeIs('about') ? 'bg-slate-100 text-[#071A36] dark:bg-white/10 dark:text-[#F3D98B] font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-white dark:hover:bg-white/5' }}">
                    About Us
                </a>
                <a href="{{ route('blog.index') }}" class="px-3.5 py-2 rounded-xl transition duration-200 {{ request()->routeIs('blog.*') ? 'bg-slate-100 text-[#071A36] dark:bg-white/10 dark:text-[#F3D98B] font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-white dark:hover:bg-white/5' }}">
                    Insights
                </a>
                <a href="{{ route('contact') }}" class="px-3.5 py-2 rounded-xl transition duration-200 {{ request()->routeIs('contact') ? 'bg-slate-100 text-[#071A36] dark:bg-white/10 dark:text-[#F3D98B] font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-slate-300 dark:hover:text-white dark:hover:bg-white/5' }}">
                    Contact
                </a>
            </nav>
        </div>

        <!-- Right Side: Search / Cart / Theme / Auth -->
        <div class="flex items-center gap-3">
            
            <!-- Quick Course Search Trigger -->
            <a href="{{ route('courses.index') }}" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 border border-slate-200 dark:border-white/10 text-xs text-slate-700 dark:text-slate-300 transition">
                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>Search</span>
            </a>

            <!-- Light / Dark Mode Toggle Switch -->
            <button 
                type="button" 
                @click="toggleTheme()" 
                class="p-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 border border-slate-200 dark:border-white/10 transition cursor-pointer text-slate-700 dark:text-amber-300"
                title="Toggle Light / Dark Mode"
                aria-label="Toggle Theme"
            >
                <svg x-show="isDark" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg x-show="!isDark" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>

            <!-- Cart Trigger with Counter -->
            <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 text-slate-700 dark:text-white transition border border-slate-200 dark:border-white/10 group" title="Registration Cart">
                <svg class="w-5 h-5 text-[#96720D] dark:text-[#F3D98B] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                @if($cartCount > 0)
                    <span class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-[#D4AF37] text-[#040E1E] text-[10px] font-black flex items-center justify-center shadow-md animate-pulse">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            <!-- Authentication Actions -->
            @auth
                @if(auth()->user()->hasRole(['super_admin', 'admin']))
                    <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#C59B27] text-[#040E1E] shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Admin Portal
                    </a>
                @elseif(auth()->user()->hasRole('instructor'))
                    <a href="{{ route('instructor.dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#C59B27] text-[#040E1E] shadow-sm transition">
                        Instructor Studio
                    </a>
                @else
                    <a href="{{ route('student.dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#C59B27] text-[#040E1E] shadow-sm transition">
                        My Learning
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hidden sm:inline-flex px-3 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/10 border border-slate-200 dark:border-white/10 transition">
                        Sign Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hidden sm:inline-flex px-4 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5 border border-slate-300 dark:border-white/15 transition">
                    Sign In
                </a>
                <a href="{{ route('register') }}" class="hidden sm:inline-flex px-4 py-2 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#C59B27] text-[#040E1E] shadow-sm transition">
                    Get Started Free
                </a>
            @endauth

            <!-- Mobile Menu Toggle Button -->
            <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-white border border-slate-200 dark:border-white/10 transition" aria-label="Toggle Navigation Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileMenuOpen" x-cloak class="lg:hidden bg-white dark:bg-[#070F1E] border-t border-slate-200 dark:border-white/10 px-5 pt-4 pb-8 space-y-3">
        <a href="{{ route('home') }}" class="block px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'bg-[#D4AF37] text-[#040E1E]' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5' }}">
            Home
        </a>
        <a href="{{ route('courses.index') }}" class="block px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('courses.*') ? 'bg-[#D4AF37] text-[#040E1E]' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5' }}">
            Courses
        </a>
        <a href="{{ route('instructors.index') }}" class="block px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('instructors.*') ? 'bg-[#D4AF37] text-[#040E1E]' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5' }}">
            Faculty
        </a>
        <a href="{{ route('about') }}" class="block px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('about') ? 'bg-[#D4AF37] text-[#040E1E]' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5' }}">
            About Us
        </a>
        <a href="{{ route('blog.index') }}" class="block px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('blog.*') ? 'bg-[#D4AF37] text-[#040E1E]' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5' }}">
            Insights
        </a>
        <a href="{{ route('contact') }}" class="block px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('contact') ? 'bg-[#D4AF37] text-[#040E1E]' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5' }}">
            Contact
        </a>

        <div class="pt-4 border-t border-slate-200 dark:border-white/10 flex flex-col gap-2.5">
            @auth
                @if(auth()->user()->hasRole(['super_admin', 'admin']))
                    <a href="{{ route('admin.dashboard') }}" class="w-full text-center py-2.5 rounded-xl text-xs font-bold bg-[#D4AF37] text-[#040E1E]">
                        Admin Portal
                    </a>
                @elseif(auth()->user()->hasRole('instructor'))
                    <a href="{{ route('instructor.dashboard') }}" class="w-full text-center py-2.5 rounded-xl text-xs font-bold bg-[#D4AF37] text-[#040E1E]">
                        Instructor Studio
                    </a>
                @else
                    <a href="{{ route('student.dashboard') }}" class="w-full text-center py-2.5 rounded-xl text-xs font-bold bg-[#D4AF37] text-[#040E1E]">
                        Student Dashboard
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center py-2.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 border border-slate-200 dark:border-white/10">
                        Sign Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="w-full text-center py-2.5 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-white/5 border border-slate-300 dark:border-white/15">
                    Sign In
                </a>
                <a href="{{ route('register') }}" class="w-full text-center py-2.5 rounded-xl text-xs font-bold bg-[#D4AF37] text-[#040E1E]">
                    Get Started Free
                </a>
            @endauth
        </div>
    </div>
</header>
