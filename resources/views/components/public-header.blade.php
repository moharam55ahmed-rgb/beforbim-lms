@php
    $cms = app(\App\Modules\Setting\Services\CmsSettingService::class);
    $siteName = $cms->get('site_name_en', 'Beforbim');
    $currentLocale = app()->getLocale();
    $isAr = $currentLocale === 'ar';
    $targetLocale = $isAr ? 'en' : 'ar';
    $targetLabel = $isAr ? 'English' : 'العربية';
    
    $cartCount = 0;
    if (auth()->check()) {
        $cart = \App\Modules\Cart\Models\Cart::where('user_id', auth()->id())->withCount('items')->first();
        $cartCount = $cart?->items_count ?? 0;
    }
@endphp

<header 
    class="sticky top-0 z-50 transition-all duration-300 backdrop-blur-xl border-b" 
    :class="scrolled 
        ? 'bg-white/85 dark:bg-[#051329]/90 border-slate-200/80 dark:border-white/15 shadow-lg shadow-black/5 dark:shadow-black/40 py-2 sm:py-2.5' 
        : 'bg-white/40 dark:bg-[#071A36]/40 border-slate-200/40 dark:border-white/10 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] py-3 sm:py-4'"
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
    x-init="scrolled = (window.pageYOffset > 15)"
    @scroll.window="scrolled = (window.pageYOffset > 15)"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        
        <!-- Brand Logo & Identity -->
        <div class="flex items-center gap-6 lg:gap-8">
            <a href="{{ route('home') }}" class="flex items-center group">
                <img 
                    src="{{ asset('images/branding/logo.png') }}" 
                    alt="Beforbim" 
                    class="h-10 sm:h-12 w-auto object-contain transition-transform group-hover:scale-105"
                    onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';"
                >
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden lg:flex items-center gap-1 text-xs font-semibold tracking-wide">
                <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl transition duration-200 {{ request()->routeIs('home') ? 'bg-[#071A36]/10 text-[#071A36] dark:bg-white/10 dark:text-[#F3D98B] font-bold' : 'text-slate-600 hover:text-[#071A36] hover:bg-slate-100/70 dark:text-slate-300 dark:hover:text-white dark:hover:bg-white/5' }}">
                    {{ $isAr ? 'الرئيسية' : 'Home' }}
                </a>
                <a href="{{ route('courses.index') }}" class="px-3.5 py-2 rounded-xl transition duration-200 {{ request()->routeIs('courses.*') ? 'bg-[#071A36]/10 text-[#071A36] dark:bg-white/10 dark:text-[#F3D98B] font-bold' : 'text-slate-600 hover:text-[#071A36] hover:bg-slate-100/70 dark:text-slate-300 dark:hover:text-white dark:hover:bg-white/5' }}">
                    {{ $isAr ? 'الدورات والدبلومات' : 'Courses' }}
                </a>
                <a href="{{ route('instructors.index') }}" class="px-3.5 py-2 rounded-xl transition duration-200 {{ request()->routeIs('instructors.*') ? 'bg-[#071A36]/10 text-[#071A36] dark:bg-white/10 dark:text-[#F3D98B] font-bold' : 'text-slate-600 hover:text-[#071A36] hover:bg-slate-100/70 dark:text-slate-300 dark:hover:text-white dark:hover:bg-white/5' }}">
                    {{ $isAr ? 'هيئة التدريس' : 'Faculty' }}
                </a>
                <a href="{{ route('about') }}" class="px-3.5 py-2 rounded-xl transition duration-200 {{ request()->routeIs('about') ? 'bg-[#071A36]/10 text-[#071A36] dark:bg-white/10 dark:text-[#F3D98B] font-bold' : 'text-slate-600 hover:text-[#071A36] hover:bg-slate-100/70 dark:text-slate-300 dark:hover:text-white dark:hover:bg-white/5' }}">
                    {{ $isAr ? 'عن الأكاديمية' : 'About Us' }}
                </a>
                <a href="{{ route('blog.index') }}" class="px-3.5 py-2 rounded-xl transition duration-200 {{ request()->routeIs('blog.*') ? 'bg-[#071A36]/10 text-[#071A36] dark:bg-white/10 dark:text-[#F3D98B] font-bold' : 'text-slate-600 hover:text-[#071A36] hover:bg-slate-100/70 dark:text-slate-300 dark:hover:text-white dark:hover:bg-white/5' }}">
                    {{ $isAr ? 'المقالات والأبحاث' : 'Insights' }}
                </a>
                <a href="{{ route('contact') }}" class="px-3.5 py-2 rounded-xl transition duration-200 {{ request()->routeIs('contact') ? 'bg-[#071A36]/10 text-[#071A36] dark:bg-white/10 dark:text-[#F3D98B] font-bold' : 'text-slate-600 hover:text-[#071A36] hover:bg-slate-100/70 dark:text-slate-300 dark:hover:text-white dark:hover:bg-white/5' }}">
                    {{ $isAr ? 'اتصل بنا' : 'Contact' }}
                </a>
            </nav>
        </div>

        <!-- Right Side: Language Switcher + Dark/Light Mode + Cart + Auth Button -->
        <div class="flex items-center gap-2 sm:gap-2.5">
            
            <!-- 1. Language Switcher (EN | AR) -->
            <a 
                href="{{ route('language.switch', $targetLocale) }}" 
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100/80 hover:bg-slate-200/80 dark:bg-white/10 dark:hover:bg-white/15 border border-slate-200/80 dark:border-white/10 text-xs font-bold text-[#071A36] dark:text-[#F3D98B] transition shadow-sm backdrop-blur-md"
                title="{{ $isAr ? 'Switch to English' : 'التبديل إلى العربية' }}"
            >
                <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                <span>{{ $targetLabel }}</span>
            </a>

            <!-- 2. Dark / Light Mode Switcher Toggle Button -->
            <button 
                type="button" 
                @click="toggleTheme()" 
                class="p-2 rounded-xl bg-slate-100/80 hover:bg-slate-200/80 dark:bg-white/10 dark:hover:bg-white/15 border border-slate-200/80 dark:border-white/10 transition cursor-pointer text-slate-700 dark:text-[#F3D98B] shadow-sm backdrop-blur-md"
                title="{{ $isAr ? 'تبديل المظهر الليلي / النهاري' : 'Toggle Light / Dark Mode' }}"
                aria-label="{{ $isAr ? 'تبديل المظهر' : 'Toggle Theme' }}"
            >
                <svg x-show="isDark" x-cloak class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg x-show="!isDark" class="w-4 h-4 text-[#071A36]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>

            <!-- 3. Course Quick Search Trigger -->
            <a href="{{ route('courses.index') }}" class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100/80 hover:bg-slate-200/80 dark:bg-white/10 dark:hover:bg-white/15 border border-slate-200/80 dark:border-white/10 text-xs text-slate-700 dark:text-slate-300 transition shadow-sm backdrop-blur-md">
                <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>{{ $isAr ? 'بحث' : 'Search' }}</span>
            </a>

            <!-- 4. Registration Cart with Counter -->
            <a href="{{ route('cart.index') }}" class="relative p-2 rounded-xl bg-slate-100/80 hover:bg-slate-200/80 dark:bg-white/10 dark:hover:bg-white/15 text-slate-700 dark:text-white transition border border-slate-200/80 dark:border-white/10 group shadow-sm backdrop-blur-md" title="{{ $isAr ? 'سلة التسجيل' : 'Registration Cart' }}">
                <svg class="w-4 h-4 text-[#071A36] dark:text-[#F3D98B] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#D4AF37] text-[#071A36] text-[9px] font-black flex items-center justify-center shadow-md animate-pulse">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            <!-- 5. Account / Authentication Pill Button (Navy & Gold Luxury Branding) -->
            @auth
                @if(auth()->user()->hasRole(['super_admin', 'admin']))
                    <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold bg-[#071A36] hover:bg-[#0D224D] dark:bg-[#D4AF37] dark:hover:bg-[#F3D98B] text-white dark:text-[#071A36] shadow-sm transition">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>{{ $isAr ? 'لوحة الإدارة' : 'Admin Panel' }}</span>
                    </a>
                @elseif(auth()->user()->hasRole('instructor'))
                    <a href="{{ route('instructor.dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold bg-[#071A36] hover:bg-[#0D224D] dark:bg-[#D4AF37] dark:hover:bg-[#F3D98B] text-white dark:text-[#071A36] shadow-sm transition">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>{{ $isAr ? 'بوابة المدرب' : 'Faculty Portal' }}</span>
                    </a>
                @else
                    <a href="{{ route('student.dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold bg-[#071A36] hover:bg-[#0D224D] dark:bg-[#D4AF37] dark:hover:bg-[#F3D98B] text-white dark:text-[#071A36] shadow-sm transition">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>{{ $isAr ? 'حسابي' : 'My Account' }}</span>
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hidden sm:inline-flex px-3 py-1.5 rounded-full text-xs font-semibold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white border border-slate-200/80 dark:border-white/10 transition cursor-pointer">
                        {{ $isAr ? 'خروج' : 'Sign Out' }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-1.5 px-5 py-2 rounded-full text-xs font-bold bg-[#071A36] hover:bg-[#0D224D] dark:bg-[#D4AF37] dark:hover:bg-[#F3D98B] text-white dark:text-[#071A36] shadow-md shadow-[#071A36]/10 dark:shadow-[#D4AF37]/20 transition">
                    <span>{{ $isAr ? 'حسابي' : 'My Account' }}</span>
                </a>
            @endauth

            <!-- Mobile Menu Toggle Button -->
            <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-xl bg-slate-100/80 dark:bg-white/10 text-slate-800 dark:text-white border border-slate-200/80 dark:border-white/10 transition shadow-sm" aria-label="Toggle Navigation Menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileMenuOpen" x-cloak class="lg:hidden bg-white/95 dark:bg-[#071A36]/95 backdrop-blur-2xl border-t border-slate-200 dark:border-white/10 px-5 pt-4 pb-8 space-y-2 shadow-xl">
        <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'bg-[#071A36] text-[#F3D98B]' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5' }}">
            {{ $isAr ? 'الرئيسية' : 'Home' }}
        </a>
        <a href="{{ route('courses.index') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('courses.*') ? 'bg-[#071A36] text-[#F3D98B]' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5' }}">
            {{ $isAr ? 'الدورات والدبلومات' : 'Courses' }}
        </a>
        <a href="{{ route('instructors.index') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('instructors.*') ? 'bg-[#071A36] text-[#F3D98B]' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5' }}">
            {{ $isAr ? 'هيئة التدريس' : 'Faculty' }}
        </a>
        <a href="{{ route('about') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('about') ? 'bg-[#071A36] text-[#F3D98B]' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5' }}">
            {{ $isAr ? 'عن الأكاديمية' : 'About Us' }}
        </a>
        <a href="{{ route('blog.index') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('blog.*') ? 'bg-[#071A36] text-[#F3D98B]' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5' }}">
            {{ $isAr ? 'المقالات والأبحاث' : 'Insights' }}
        </a>
        <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('contact') ? 'bg-[#071A36] text-[#F3D98B]' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5' }}">
            {{ $isAr ? 'اتصل بنا' : 'Contact' }}
        </a>

        <div class="pt-4 border-t border-slate-200 dark:border-white/10 flex flex-col gap-2.5">
            <a href="{{ route('language.switch', $targetLocale) }}" class="w-full text-center py-2 rounded-full text-xs font-bold bg-slate-100 dark:bg-white/10 text-[#071A36] dark:text-[#F3D98B]">
                {{ $targetLabel }}
            </a>
            @auth
                <a href="{{ route('student.dashboard') }}" class="w-full text-center py-2.5 rounded-full text-xs font-bold bg-[#071A36] text-[#F3D98B]">
                    {{ $isAr ? 'حسابي' : 'My Account' }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center py-2 rounded-full text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white border border-slate-200 dark:border-white/10 cursor-pointer">
                        {{ $isAr ? 'تسجيل الخروج' : 'Sign Out' }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="w-full text-center py-2.5 rounded-full text-xs font-bold bg-[#071A36] text-[#F3D98B]">
                    {{ $isAr ? 'حسابي' : 'My Account' }}
                </a>
            @endauth
        </div>
    </div>
</header>
