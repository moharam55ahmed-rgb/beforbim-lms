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
    class="sticky top-0 z-50 transition-all duration-300 backdrop-blur-xl border-b relative" 
    :class="scrolled 
        ? 'bg-white/90 dark:bg-[#040E1E]/95 border-slate-200/90 dark:border-white/15 shadow-xl shadow-[#071A36]/5 dark:shadow-black/60 py-2 sm:py-2.5' 
        : 'bg-white/60 dark:bg-[#071A36]/60 border-slate-200/50 dark:border-white/10 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.04)] py-3 sm:py-4'"
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
    <!-- Top Engineering Gold Ambient Border Accent -->
    <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-[#D4AF37]/70 to-transparent pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        
        <!-- Brand Logo & Identity -->
        <div class="flex items-center gap-6 lg:gap-8">
            <a href="{{ route('home') }}" class="flex items-center group relative">
                <div class="absolute -inset-1.5 bg-gradient-to-r from-[#D4AF37]/20 to-transparent rounded-2xl blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                <img 
                    src="{{ asset('images/branding/logo.png') }}" 
                    alt="Beforbim" 
                    class="h-10 sm:h-12 w-auto object-contain transition-transform duration-300 group-hover:scale-105 relative z-10"
                    onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';"
                >
            </a>

            <!-- Desktop Navigation Links with Animated Glowing Indicators -->
            <nav class="hidden lg:flex items-center gap-1.5 text-xs font-semibold tracking-wide">
                @php
                    $navItems = [
                        ['route' => 'home', 'label_ar' => 'الرئيسية', 'label_en' => 'Home', 'active' => request()->routeIs('home')],
                        ['route' => 'courses.index', 'label_ar' => 'الدورات والدبلومات', 'label_en' => 'Courses', 'active' => request()->routeIs('courses.*')],
                        ['route' => 'instructors.index', 'label_ar' => 'هيئة التدريس', 'label_en' => 'Faculty', 'active' => request()->routeIs('instructors.*')],
                        ['route' => 'about', 'label_ar' => 'عن الأكاديمية', 'label_en' => 'About Us', 'active' => request()->routeIs('about')],
                        ['route' => 'blog.index', 'label_ar' => 'المقالات والأبحاث', 'label_en' => 'Insights', 'active' => request()->routeIs('blog.*')],
                        ['route' => 'contact', 'label_ar' => 'اتصل بنا', 'label_en' => 'Contact', 'active' => request()->routeIs('contact')],
                    ];
                @endphp

                @foreach($navItems as $item)
                    <a 
                        href="{{ route($item['route']) }}" 
                        class="relative px-3.5 py-2 rounded-xl transition-all duration-300 font-semibold group flex items-center gap-1.5 {{ $item['active'] ? 'text-[#071A36] dark:text-[#F3D98B] bg-slate-100/90 dark:bg-white/10 shadow-xs' : 'text-slate-600 hover:text-[#071A36] hover:bg-slate-100/60 dark:text-slate-300 dark:hover:text-white dark:hover:bg-white/5' }}"
                    >
                        @if($item['active'])
                            <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37] shadow-[0_0_8px_#D4AF37] animate-pulse"></span>
                        @endif
                        <span>{{ $isAr ? $item['label_ar'] : $item['label_en'] }}</span>
                        
                        <!-- Smooth Gold Micro-Underline on Hover -->
                        <span class="absolute bottom-1 inset-x-3.5 h-[2px] bg-gradient-to-r from-[#D4AF37]/20 via-[#D4AF37] to-[#D4AF37]/20 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-center pointer-events-none {{ $item['active'] ? 'scale-x-100 opacity-90' : 'opacity-0 group-hover:opacity-100' }}"></span>
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- Right Side: Search + Language Switcher + Dark/Light Mode + Cart + Auth Button -->
        <div class="flex items-center gap-2 sm:gap-2.5">
            
            <!-- 1. Course Quick Search Trigger with Shortcut Tag -->
            <a 
                href="{{ route('courses.index') }}" 
                class="hidden md:inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100/80 hover:bg-slate-200/80 dark:bg-white/10 dark:hover:bg-white/15 border border-slate-200/80 dark:border-white/10 text-xs text-slate-700 dark:text-slate-300 transition-all hover:scale-[1.02] shadow-xs backdrop-blur-md group"
                title="{{ $isAr ? 'البحث عن الدورات' : 'Search Courses' }}"
            >
                <svg class="w-3.5 h-3.5 text-[#D4AF37] transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>{{ $isAr ? 'بحث سريع' : 'Search' }}</span>
                <kbd class="hidden xl:inline-block px-1.5 py-0.5 text-[10px] font-mono text-slate-500 dark:text-slate-400 bg-white/80 dark:bg-white/10 rounded border border-slate-200 dark:border-white/10 shadow-2xs">Ctrl K</kbd>
            </a>

            <!-- 2. Language Switcher (EN | AR) -->
            <a 
                href="{{ route('language.switch', $targetLocale) }}" 
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100/80 hover:bg-slate-200/80 dark:bg-white/10 dark:hover:bg-white/15 border border-slate-200/80 dark:border-white/10 text-xs font-bold text-[#071A36] dark:text-[#F3D98B] transition-all hover:scale-105 active:scale-95 shadow-xs backdrop-blur-md group"
                title="{{ $isAr ? 'Switch to English' : 'التبديل إلى العربية' }}"
            >
                <svg class="w-3.5 h-3.5 text-[#D4AF37] transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                <span>{{ $targetLabel }}</span>
            </a>

            <!-- 3. Dark / Light Mode Switcher Toggle Button with Smooth Rotation -->
            <button 
                type="button" 
                @click="toggleTheme()" 
                class="p-2 rounded-xl bg-slate-100/80 hover:bg-slate-200/80 dark:bg-white/10 dark:hover:bg-white/15 border border-slate-200/80 dark:border-white/10 transition-all hover:scale-105 active:scale-95 cursor-pointer text-slate-700 dark:text-[#F3D98B] shadow-xs backdrop-blur-md group"
                title="{{ $isAr ? 'تبديل المظهر الليلي / النهاري' : 'Toggle Light / Dark Mode' }}"
                aria-label="{{ $isAr ? 'تبديل المظهر' : 'Toggle Theme' }}"
            >
                <svg x-show="isDark" x-cloak class="w-4 h-4 text-amber-300 transition-transform duration-500 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg x-show="!isDark" class="w-4 h-4 text-[#071A36] transition-transform duration-500 group-hover:-rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>

            <!-- 4. Registration Cart with Animated Counter Halo -->
            <a 
                href="{{ route('cart.index') }}" 
                class="relative p-2 rounded-xl bg-slate-100/80 hover:bg-slate-200/80 dark:bg-white/10 dark:hover:bg-white/15 text-slate-700 dark:text-white transition-all hover:scale-105 active:scale-95 border border-slate-200/80 dark:border-white/10 group shadow-xs backdrop-blur-md" 
                title="{{ $isAr ? 'سلة التسجيل' : 'Registration Cart' }}"
            >
                <svg class="w-4 h-4 text-[#071A36] dark:text-[#F3D98B] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#D4AF37] text-[#071A36] text-[9px] font-black flex items-center justify-center shadow-md shadow-[#D4AF37]/50 animate-pulse">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            <!-- 5. Account / Authentication Button (with Shimmer Beam & Gold Glow) -->
            @auth
                @if(auth()->user()->hasRole(['super_admin', 'admin']))
                    <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold bg-[#071A36] hover:bg-[#0D224D] dark:bg-[#D4AF37] dark:hover:bg-[#F3D98B] text-white dark:text-[#071A36] shadow-sm hover:shadow-md transition-all hover:scale-[1.02]">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>{{ $isAr ? 'لوحة الإدارة' : 'Admin Panel' }}</span>
                    </a>
                @elseif(auth()->user()->hasRole('instructor'))
                    <a href="{{ route('instructor.dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold bg-[#071A36] hover:bg-[#0D224D] dark:bg-[#D4AF37] dark:hover:bg-[#F3D98B] text-white dark:text-[#071A36] shadow-sm hover:shadow-md transition-all hover:scale-[1.02]">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>{{ $isAr ? 'بوابة المدرب' : 'Faculty Portal' }}</span>
                    </a>
                @else
                    <a href="{{ route('student.dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold bg-[#071A36] hover:bg-[#0D224D] dark:bg-[#D4AF37] dark:hover:bg-[#F3D98B] text-white dark:text-[#071A36] shadow-sm hover:shadow-md transition-all hover:scale-[1.02]">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>{{ $isAr ? 'حسابي' : 'My Account' }}</span>
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hidden sm:inline-flex px-3 py-1.5 rounded-full text-xs font-semibold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white border border-slate-200/80 dark:border-white/10 transition-all hover:bg-slate-100 dark:hover:bg-white/5 cursor-pointer">
                        {{ $isAr ? 'خروج' : 'Sign Out' }}
                    </button>
                </form>
            @else
                <a 
                    href="{{ route('login') }}" 
                    class="relative hidden sm:inline-flex items-center gap-1.5 px-5 py-2 rounded-full text-xs font-black bg-gradient-to-r from-[#071A36] to-[#0D264C] dark:from-[#D4AF37] dark:to-[#B38F24] text-white dark:text-[#040E1E] shadow-md shadow-[#071A36]/15 dark:shadow-[#D4AF37]/25 hover:shadow-lg hover:shadow-[#071A36]/25 dark:hover:shadow-[#D4AF37]/40 hover:scale-[1.02] active:scale-[0.98] transition-all overflow-hidden group"
                >
                    <!-- Shimmer beam effect -->
                    <span class="absolute inset-0 w-1/2 h-full bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-[300%] transition-transform duration-700 ease-out pointer-events-none"></span>
                    <svg class="w-3.5 h-3.5 text-[#D4AF37] dark:text-[#040E1E] transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>{{ $isAr ? 'حسابي' : 'My Account' }}</span>
                </a>
            @endauth

            <!-- Mobile Menu Toggle Button with Icon Morph -->
            <button 
                type="button" 
                @click="mobileMenuOpen = !mobileMenuOpen" 
                class="lg:hidden p-2 rounded-xl bg-slate-100/80 dark:bg-white/10 text-slate-800 dark:text-white border border-slate-200/80 dark:border-white/10 transition-all hover:scale-105 active:scale-95 shadow-xs" 
                aria-label="Toggle Navigation Menu"
            >
                <svg class="w-5 h-5 transition-transform duration-300" :class="mobileMenuOpen ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer with Smooth Alpine Transition -->
    <div 
        x-show="mobileMenuOpen" 
        x-cloak 
        x-transition:enter="transition ease-out duration-300 transform" 
        x-transition:enter-start="opacity-0 -translate-y-4" 
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200 transform" 
        x-transition:leave-start="opacity-100 translate-y-0" 
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="lg:hidden bg-white/95 dark:bg-[#071A36]/95 backdrop-blur-2xl border-t border-slate-200 dark:border-white/10 px-5 pt-4 pb-8 space-y-2 shadow-2xl"
    >
        @foreach($navItems as $item)
            <a 
                href="{{ route($item['route']) }}" 
                class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $item['active'] ? 'bg-[#071A36] text-[#F3D98B] dark:bg-white/10 shadow-sm' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5' }}"
            >
                <span>{{ $isAr ? $item['label_ar'] : $item['label_en'] }}</span>
                @if($item['active'])
                    <span class="w-2 h-2 rounded-full bg-[#D4AF37] shadow-[0_0_6px_#D4AF37]"></span>
                @else
                    <svg class="w-4 h-4 text-slate-400 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                @endif
            </a>
        @endforeach

        <div class="pt-4 border-t border-slate-200 dark:border-white/10 flex flex-col gap-2.5">
            <a 
                href="{{ route('language.switch', $targetLocale) }}" 
                class="w-full text-center py-2.5 rounded-xl text-xs font-bold bg-slate-100 dark:bg-white/10 text-[#071A36] dark:text-[#F3D98B] flex items-center justify-center gap-2"
            >
                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                <span>{{ $targetLabel }}</span>
            </a>
            @auth
                <a href="{{ route('student.dashboard') }}" class="w-full text-center py-2.5 rounded-xl text-xs font-bold bg-[#071A36] text-[#F3D98B]">
                    {{ $isAr ? 'حسابي' : 'My Account' }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center py-2.5 rounded-xl text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white border border-slate-200 dark:border-white/10 cursor-pointer">
                        {{ $isAr ? 'تسجيل الخروج' : 'Sign Out' }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="w-full text-center py-3 rounded-xl text-xs font-black bg-gradient-to-r from-[#071A36] to-[#0D264C] dark:from-[#D4AF37] dark:to-[#B38F24] text-white dark:text-[#040E1E] shadow-md">
                    {{ $isAr ? 'تسجيل الدخول / حسابي' : 'Sign In / My Account' }}
                </a>
            @endauth
        </div>
    </div>
</header>
