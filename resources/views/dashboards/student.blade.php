<x-layouts.base title="منصة بيفوربيم — بوابة المتدرب الهندسية">
    <!-- Student Header (Deep Navy) -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg">
                    B
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold font-['Tajawal'] text-white">{{ $user->name }}</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-[#D4AF37]/20 text-[#F3D98B] border border-[#D4AF37]/40">
                            مهندس معتمد
                        </span>
                    </div>
                    <p class="text-xs text-slate-300 font-mono">{{ $user->email }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[#123B68]/60 border border-white/10 text-xs text-slate-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>جلسة نشطة ({{ $active_devices->count() }} أجهزة)</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <x-button type="submit" variant="outline-gold" size="sm">تسجيل الخروج</x-button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-8">
        
        <!-- Welcome Hero Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-[#071A36] border border-[#D4AF37]/30 p-8 shadow-2xl bg-blueprint-navy">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <span class="text-xs font-bold tracking-widest uppercase text-[#D4AF37]">الاستوديو الهندسي الرقمي</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-white font-['Tajawal']">
                        مرحباً بك، م. {{ $user->name }}
                    </h2>
                    <p class="text-sm text-slate-300 max-w-2xl leading-relaxed">
                        لوحة التحكم المركزية لمتابعة مسارات نمذجة معلومات البناء (BIM)، تسليم المشروعات الهندسية، والحصول على الشهادات المهنية المعتمدة.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <x-button variant="gold" class="whitespace-nowrap shadow-lg shadow-[#D4AF37]/20">
                        استعراض الدبلومات والمسارات
                    </x-button>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-white/10">
                <div class="bg-white/5 rounded-xl p-4 border border-white/10 backdrop-blur-sm">
                    <p class="text-xs text-slate-300">الدورات النشطة</p>
                    <p class="text-2xl font-black text-white font-mono mt-1">{{ $stats['total_enrolled'] }}</p>
                </div>
                <div class="bg-white/5 rounded-xl p-4 border border-white/10 backdrop-blur-sm">
                    <p class="text-xs text-slate-300">الدورات المكتملة</p>
                    <p class="text-2xl font-black text-[#D4AF37] font-mono mt-1">{{ $stats['completed_courses'] }}</p>
                </div>
                <div class="bg-white/5 rounded-xl p-4 border border-white/10 backdrop-blur-sm">
                    <p class="text-xs text-slate-300">قيد المتابعة</p>
                    <p class="text-2xl font-black text-blue-300 font-mono mt-1">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="bg-white/5 rounded-xl p-4 border border-white/10 backdrop-blur-sm">
                    <p class="text-xs text-slate-300">معدل الإنجاز العام</p>
                    <p class="text-2xl font-black text-emerald-400 font-mono mt-1">{{ $stats['average_progress'] }}%</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Active Courses (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-[#071A36] font-['Tajawal']">المسارات والكورسات النشطة</h3>
                        <p class="text-xs text-slate-500">تابع دروسك الهندسية وتطبيقات الـ BIM العملية</p>
                    </div>
                    <span class="text-xs font-semibold text-[#123B68]">
                        إجمالي: {{ $enrollments->count() }} مسار
                    </span>
                </div>

                @if($enrollments->isEmpty())
                    <x-card class="text-center py-12 border-dashed border-2 border-slate-300">
                        <div class="w-16 h-16 rounded-2xl bg-[#071A36]/5 text-[#071A36] flex items-center justify-center mx-auto mb-4 border border-slate-200">
                            <svg class="w-8 h-8 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-[#071A36]">لا توجد دورات نشطة مسجلة حالياً</h4>
                        <p class="text-sm text-slate-500 max-w-md mx-auto mt-2">
                            اختر من باقة برامج بيفوربيم المعتمدة في Revit, Navisworks, Dynamo ومسارات إدارة المشروعات الهندسية.
                        </p>
                        <div class="mt-6">
                            <x-button variant="gold">تصفح مكتبة البرامج الهندسية</x-button>
                        </div>
                    </x-card>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($enrollments as $enrollment)
                            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-[#D4AF37]/50 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <x-badge variant="navy">{{ $enrollment->course->level ?? 'جميع المستويات' }}</x-badge>
                                        <span class="text-xs font-mono font-bold text-[#D4AF37] bg-[#071A36] px-2.5 py-1 rounded-lg">
                                            {{ $enrollment->progress_percentage }}%
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-[#071A36] text-base mb-2 font-['Tajawal'] leading-snug">
                                        {{ $enrollment->course->title_ar ?? $enrollment->course->title }}
                                    </h4>
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                        {{ $enrollment->course->short_description_ar ?? $enrollment->course->description }}
                                    </p>

                                    <!-- Gold Progress Bar -->
                                    <div class="mt-4 pt-2">
                                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                            <div class="bg-gradient-to-r from-[#D4AF37] to-[#F3D98B] h-2 rounded-full transition-all duration-500" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-6 py-4 bg-[#F5F7FA] border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-xs text-slate-600 font-medium">
                                        م. {{ $enrollment->course->instructor?->name ?? 'طاقم بيفوربيم' }}
                                    </span>
                                    <x-button variant="gold" size="sm">
                                        متابعة المحاضرات
                                    </x-button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Column: Timeline & Assessments (1 Col) -->
            <div class="space-y-6">
                <!-- Upcoming Assessments Widget -->
                <x-card variant="default" padding="p-6">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <h4 class="font-bold text-sm text-[#071A36] flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#D4AF37]"></span>
                            الاختبارات والتقييمات
                        </h4>
                        <span class="text-xs text-slate-400 font-mono">{{ $upcoming_assessments->count() }} متاحة</span>
                    </div>

                    @if($upcoming_assessments->isEmpty())
                        <p class="text-xs text-slate-500 text-center py-4">لا توجد اختبارات مجدولة حالياً</p>
                    @else
                        <div class="space-y-3">
                            @foreach($upcoming_assessments as $assessment)
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 hover:border-[#D4AF37]/50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-[#071A36]">{{ $assessment->title }}</span>
                                        <span class="text-[10px] font-mono bg-blue-50 text-blue-700 px-2 py-0.5 rounded">{{ $assessment->time_limit_minutes }} دقيقة</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-1">{{ $assessment->course?->title }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-card>

                <!-- Activity Timeline Widget -->
                <x-card variant="default" padding="p-6">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <h4 class="font-bold text-sm text-[#071A36] flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#123B68]"></span>
                            سجل النشاط الهندسي الأخير
                        </h4>
                    </div>

                    @if($timeline->isEmpty())
                        <p class="text-xs text-slate-500 text-center py-4">لا يوجد نشاط مسجل حديثاً</p>
                    @else
                        <div class="relative border-r border-slate-200 space-y-4 pr-4 mr-2">
                            @foreach($timeline as $activity)
                                <div class="relative">
                                    <span class="absolute -right-[21px] top-1 w-2.5 h-2.5 rounded-full bg-[#D4AF37] ring-4 ring-white"></span>
                                    <p class="text-xs font-bold text-slate-800">{{ $activity['title'] }}</p>
                                    <p class="text-[11px] text-slate-500">{{ $activity['subtitle'] }}</p>
                                    <span class="text-[10px] font-mono text-slate-400 mt-0.5 block">
                                        {{ $activity['date']->diffForHumans() }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-card>

                <!-- Device Security Info -->
                <div class="p-4 rounded-2xl bg-[#071A36] border border-[#D4AF37]/20 text-white text-xs space-y-2">
                    <div class="flex items-center justify-between text-[#F3D98B]">
                        <span class="font-bold">أمان الحساب والجلسات</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <p class="text-slate-300 leading-relaxed text-[11px]">
                        نظام الحماية مفعل ضد المشاركة المتزامنة. أجهزتك المتصلة محددة ومراقبة طبقاً لسياسة الملكية الفكرية.
                    </p>
                </div>
            </div>
        </div>
    </main>
</x-layouts.base>
