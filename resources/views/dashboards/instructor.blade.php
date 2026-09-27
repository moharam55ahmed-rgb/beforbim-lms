<x-layouts.base title="استوديو المحاضر الهندسي — Beforbim">
    <!-- Instructor Header (Navy / Gold) -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#123B68] to-[#071A36] border border-[#D4AF37]/40 flex items-center justify-center font-black text-[#D4AF37] shadow-md text-sm">
                    BIM
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold font-['Tajawal'] text-white">م. {{ $user->name }}</h1>
                        @if($profile && $profile->isApproved())
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-[#D4AF37]/20 text-[#F3D98B] border border-[#D4AF37]/40">
                                مدرب BIM معتمد
                            </span>
                        @else
                            <x-badge variant="warning">الملف قيد المراجعة</x-badge>
                        @endif
                    </div>
                    <p class="text-xs text-slate-300 font-sans">{{ $profile?->specialization ?: 'خبير نمذجة معلومات البناء' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('courses.create') }}">
                    <x-button variant="gold" size="sm">
                        + إنشاء دورة تدريبية
                    </x-button>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <x-button type="submit" variant="outline-gold" size="sm">تسجيل الخروج</x-button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-8">
        
        <!-- Studio Metrics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:border-[#D4AF37]/50 transition-all">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-semibold">إجمالي الدورات</span>
                    <span class="p-2 rounded-xl bg-[#071A36]/5 text-[#071A36]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-[#071A36] font-mono">{{ $metrics['total_courses'] }}</span>
                    <span class="text-xs text-slate-400">منها {{ $metrics['published_courses'] }} منشورة</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:border-[#D4AF37]/50 transition-all">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-semibold">المهندسون المتدربون</span>
                    <span class="p-2 rounded-xl bg-blue-50 text-[#123B68]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-[#123B68] font-mono">{{ $metrics['total_students'] }}</span>
                    <span class="text-xs text-slate-400">مشترك مسجل</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:border-[#D4AF37]/50 transition-all">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-semibold">دورات قيد التدقيق</span>
                    <span class="p-2 rounded-xl bg-amber-50 text-amber-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-amber-600 font-mono">{{ $metrics['pending_courses'] }}</span>
                    <span class="text-xs text-slate-400">في انتظار الاعتماد</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:border-[#D4AF37]/50 transition-all">
                <div class="flex items-center justify-between text-slate-500 mb-2">
                    <span class="text-xs font-semibold">مراجعات المشاريع والتكليفات</span>
                    <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-emerald-600 font-mono">{{ $metrics['pending_assignment_reviews'] }}</span>
                    <span class="text-xs text-slate-400">مشروع بانتظار التقييم</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Courses List (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-[#071A36] font-['Tajawal']">المناهج والبرامج التي تشرف عليها</h3>
                        <p class="text-xs text-slate-500">إدارة الأقسام والدروس ورفع ملفات Revit و Navisworks والمواد التعليمية</p>
                    </div>
                </div>

                @if($courses->isEmpty())
                    <x-card class="text-center py-12 border-dashed border-2 border-slate-300">
                        <div class="w-16 h-16 rounded-2xl bg-[#071A36]/5 text-[#071A36] flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-[#071A36]">لم تقم بإنشاء أي دورات بعد</h4>
                        <p class="text-sm text-slate-500 max-w-md mx-auto mt-2">
                            ابدأ في تصميم محتوى الدورة الأولى وحدد الأقسام والمحاضرات وأرسلها للاعتماد الأكاديمي.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('courses.create') }}">
                                <x-button variant="gold">إنشاء أول دورة تدريبية</x-button>
                            </a>
                        </div>
                    </x-card>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($courses as $course)
                            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between overflow-hidden">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-3">
                                        @if($course->status === 'APPROVED')
                                            <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800">معتمدة ومنشورة</span>
                                        @elseif($course->status === 'SUBMITTED')
                                            <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-800">قيد المراجعة</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700">مسودة</span>
                                        @endif
                                        <span class="text-xs font-mono font-bold text-slate-500">
                                            {{ $course->enrollments_count }} مشترك
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-[#071A36] text-base mb-2 font-['Tajawal']">
                                        {{ $course->title_ar ?? $course->title }}
                                    </h4>
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                        {{ $course->short_description_ar ?? $course->description }}
                                    </p>
                                    <div class="flex items-center gap-4 mt-4 pt-3 border-t border-slate-100 text-xs text-slate-500 font-mono">
                                        <span>{{ $course->sections_count }} أقسام</span>
                                        <span>•</span>
                                        <span>{{ $course->effective_price }} {{ $course->currency }}</span>
                                    </div>
                                </div>
                                <div class="px-6 py-3.5 bg-[#F5F7FA] border-t border-slate-100 flex items-center justify-between">
                                    <a href="{{ route('courses.edit', $course->id) }}" class="text-xs font-bold text-[#123B68] hover:text-[#071A36]">
                                        تعديل البيانات
                                    </a>
                                    <a href="{{ route('courses.curriculum', $course->id) }}">
                                        <x-button variant="navy" size="sm">
                                            بناء المنهج
                                        </x-button>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Side Review Queue (1 Col) -->
            <div class="space-y-6">
                <x-card variant="default" padding="p-6">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <h4 class="font-bold text-sm text-[#071A36] flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            طابور تصحيح التكليفات
                        </h4>
                        <span class="text-xs font-mono text-slate-400">{{ $pending_reviews->count() }} بانتظار التقييم</span>
                    </div>

                    @if($pending_reviews->isEmpty())
                        <div class="text-center py-6">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <p class="text-xs text-slate-500">تم الانتهاء من تصحيح جميع التكليفات المسلمة!</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($pending_reviews as $submission)
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70 hover:border-[#D4AF37]/50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-[#071A36]">{{ $submission->user?->name }}</span>
                                        <span class="text-[10px] font-mono text-slate-400">{{ $submission->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-600 mt-1 font-medium">{{ $submission->assignment?->title }}</p>
                                    <div class="mt-2 pt-2 border-t border-slate-200/50 flex justify-end">
                                        <x-button variant="outline" size="sm" class="text-[10px] py-1">تقييم المشروع</x-button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-card>

                <!-- Professional Profile Status -->
                <div class="p-5 rounded-2xl bg-[#071A36] border border-[#D4AF37]/30 text-white space-y-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-[#D4AF37]">الملف المهني للمدرب</span>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        يتم إظهار بيانات اعتمادك وسنوات خبرتك وتخصصك في صفحات الدورات العامة بعد مراجعة الإدارة الهندسية.
                    </p>
                    <div class="pt-2">
                        <x-button variant="outline-gold" size="sm" class="w-full justify-center">
                            تحديث السيرة الذاتية والشهادات
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layouts.base>
