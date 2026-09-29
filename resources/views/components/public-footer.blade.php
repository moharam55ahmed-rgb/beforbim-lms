@php
    $cms = app(\App\Modules\Setting\Services\CmsSettingService::class);
    $currentLocale = app()->getLocale();
    $isAr = $currentLocale === 'ar';
    $siteName = $isAr ? $cms->get('site_name_ar', 'Beforbim — الأكاديمية الهندسية لنمذجة معلومات البناء') : $cms->get('site_name_en', 'Beforbim — BIM Engineering Academy');
    $contactEmail = $cms->get('contact_email', 'info@beforbim.com');
    $contactPhone = $cms->get('contact_phone', '+20 2 2456 7890');
    $address = $isAr ? 'مركز Beforbim الهندسي، التجمع الخامس، القاهرة، مصر' : $cms->get('contact_address', 'Beforbim Engineering Center, New Cairo, Cairo, Egypt');
    $socialTwitter = $cms->get('social_twitter', 'https://twitter.com/BeforbimAcademy');
    $socialLinkedin = $cms->get('social_linkedin', 'https://linkedin.com/company/beforbim');
    $socialYoutube = $cms->get('social_youtube', 'https://youtube.com');
@endphp

<footer class="bg-white dark:bg-[#040C1A] text-slate-700 dark:text-slate-300 border-t border-slate-200/80 dark:border-white/10 pt-16 pb-12 mt-auto relative overflow-hidden transition-colors duration-200">
    <!-- Ambient Subtle Glow -->
    <div class="absolute -top-32 right-1/4 w-96 h-96 bg-[#D4AF37]/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-200/80 dark:border-white/10">
            
            <!-- Col 1 & 2: Brand & Mission with Official Logo -->
            <div class="lg:col-span-2 space-y-4">
                <a href="{{ route('home') }}" class="inline-block group">
                    <img 
                        src="{{ asset('images/branding/logo.png') }}" 
                        alt="Beforbim" 
                        class="h-12 w-auto sm:h-14 object-contain transition-transform group-hover:scale-105"
                        onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';"
                    >
                </a>
                
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed max-w-sm font-light">
                    @if($isAr)
                        الأكاديمية الهندسية الرائدة في تقنيات الـ BIM والنمذجة الرقمية بالقاهرة. تمكين المهندسين المعماريين والإنشائيين وكهروميكانيك الـ MEP بدبلومات احترافية معتمدة وفق المعايير الدولية ISO 19650.
                    @else
                        The premier digital construction academy based in Cairo, Egypt. Empowering architects, structural engineers, and MEP specialists with industry-certified BIM masterclasses aligned with international ISO 19650 standards.
                    @endif
                </p>

                <!-- Social Handles -->
                <div class="flex items-center gap-3 pt-2">
                    <a href="{{ $socialLinkedin }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-[#071A36] hover:text-[#F3D98B] dark:bg-white/5 dark:hover:bg-[#D4AF37] dark:hover:text-[#071A36] text-slate-700 dark:text-slate-300 flex items-center justify-center transition border border-slate-200 dark:border-white/10" aria-label="LinkedIn">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                    <a href="{{ $socialTwitter }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-[#071A36] hover:text-[#F3D98B] dark:bg-white/5 dark:hover:bg-[#D4AF37] dark:hover:text-[#071A36] text-slate-700 dark:text-slate-300 flex items-center justify-center transition border border-slate-200 dark:border-white/10" aria-label="Twitter">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="{{ $socialYoutube }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-[#071A36] hover:text-[#F3D98B] dark:bg-white/5 dark:hover:bg-[#D4AF37] dark:hover:text-[#071A36] text-slate-700 dark:text-slate-300 flex items-center justify-center transition border border-slate-200 dark:border-white/10" aria-label="YouTube">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Col 3: Engineering Programs -->
            <div class="space-y-3">
                <h4 class="text-sm font-bold text-[#071A36] dark:text-white font-['Outfit'] tracking-wider border-b border-slate-200 dark:border-white/10 pb-2">
                    {{ $isAr ? 'دبلومات الـ BIM' : 'BIM Programs' }}
                </h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('courses.index') }}" class="text-slate-600 hover:text-[#071A36] dark:text-slate-400 dark:hover:text-[#F3D98B] transition">{{ $isAr ? 'دبلومة ريفيت معماري LOD 350' : 'Revit Architecture LOD 350' }}</a></li>
                    <li><a href="{{ route('courses.index') }}" class="text-slate-600 hover:text-[#071A36] dark:text-slate-400 dark:hover:text-[#F3D98B] transition">{{ $isAr ? 'الـ BIM الإنشائي وتفريد حديد التسليح' : 'Structural BIM & Rebar' }}</a></li>
                    <li><a href="{{ route('courses.index') }}" class="text-slate-600 hover:text-[#071A36] dark:text-slate-400 dark:hover:text-[#F3D98B] transition">{{ $isAr ? 'ريفيت كهروميكانيك MEP الشامل' : 'Comprehensive Revit MEP' }}</a></li>
                    <li><a href="{{ route('courses.index') }}" class="text-slate-600 hover:text-[#071A36] dark:text-slate-400 dark:hover:text-[#F3D98B] transition">{{ $isAr ? 'كشف التعارضات وتنسيق نافيسووركس' : 'Navisworks Clash Detection' }}</a></li>
                    <li><a href="{{ route('courses.index') }}" class="text-slate-600 hover:text-[#071A36] dark:text-slate-400 dark:hover:text-[#F3D98B] transition">{{ $isAr ? 'أتمتة الداينامو وبايثون الهندسي' : 'Dynamo & Python Automation' }}</a></li>
                </ul>
            </div>

            <!-- Col 4: Platform Links -->
            <div class="space-y-3">
                <h4 class="text-sm font-bold text-[#071A36] dark:text-white font-['Outfit'] tracking-wider border-b border-slate-200 dark:border-white/10 pb-2">
                    {{ $isAr ? 'روابط المنصة' : 'Navigation' }}
                </h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('about') }}" class="text-slate-600 hover:text-[#071A36] dark:text-slate-400 dark:hover:text-[#F3D98B] transition">{{ $isAr ? 'عن الأكاديمية' : 'About Academy' }}</a></li>
                    <li><a href="{{ route('instructors.index') }}" class="text-slate-600 hover:text-[#071A36] dark:text-slate-400 dark:hover:text-[#F3D98B] transition">{{ $isAr ? 'هيئة التدريس والخبراء' : 'Accredited Faculty' }}</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-slate-600 hover:text-[#071A36] dark:text-slate-400 dark:hover:text-[#F3D98B] transition">{{ $isAr ? 'الأبحاث والمقالات الهندسية' : 'Insights & Research' }}</a></li>
                    <li><a href="{{ route('contact') }}" class="text-slate-600 hover:text-[#071A36] dark:text-slate-400 dark:hover:text-[#F3D98B] transition">{{ $isAr ? 'استشارة مهنية مجانية' : 'Consultation' }}</a></li>
                    <li><a href="{{ route('login') }}" class="text-slate-600 hover:text-[#071A36] dark:text-slate-400 dark:hover:text-[#F3D98B] transition">{{ $isAr ? 'بوابة المهندسين (دخول)' : 'Student Portal' }}</a></li>
                </ul>
            </div>

            <!-- Col 5: Contact -->
            <div class="space-y-3">
                <h4 class="text-sm font-bold text-[#071A36] dark:text-white font-['Outfit'] tracking-wider border-b border-slate-200 dark:border-white/10 pb-2">
                    {{ $isAr ? 'المقر الرئيسي والدعم' : 'Headquarters & Support' }}
                </h4>
                <ul class="space-y-2.5 text-xs text-slate-600 dark:text-slate-400">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#D4AF37] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>{{ $contactEmail }}</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#D4AF37] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span dir="ltr">{{ $contactPhone }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-[#D4AF37] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $address }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="pt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between text-xs text-slate-500 gap-4">
            <p>
                @if($isAr)
                    &copy; {{ date('Y') }} أكاديمية Beforbim للتعليم والتدريب الهندسي. جميع الحقوق محفوظة. القاهرة، مصر.
                @else
                    &copy; {{ date('Y') }} Beforbim Engineering Education Ltd. All rights reserved. Based in Cairo, Egypt.
                @endif
            </p>
            <div class="flex items-center gap-4 text-[11px]">
                <span class="text-slate-600 dark:text-slate-400">{{ $isAr ? 'شهادات معتمدة مع رمز QR' : 'QR-Verified Certificates' }}</span>
                <span class="w-1 h-1 rounded-full bg-[#D4AF37]"></span>
                <span class="text-slate-600 dark:text-slate-400">{{ $isAr ? 'مطابقة لمعايير ISO 19650' : 'ISO 19650 Compliance' }}</span>
                <span class="w-1 h-1 rounded-full bg-[#D4AF37]"></span>
                <span class="text-slate-600 dark:text-slate-400">{{ $isAr ? 'بيئة أوتوديسك الهندسية' : 'Autodesk Ecosystem' }}</span>
            </div>
        </div>
    </div>
</footer>
