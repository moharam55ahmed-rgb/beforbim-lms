<x-layouts.base title="دليل الدورات والبرامج الهندسية — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg">
                        B
                    </div>
                    <div>
                        <span class="text-base font-black tracking-wider text-white font-['Tajawal']">BEFORBIM</span>
                        <span class="block text-[10px] text-[#F3D98B] font-mono tracking-widest uppercase">دليل المناهج الهندسية</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    @if(auth()->user()->hasRole('instructor'))
                        <a href="{{ route('instructor.dashboard') }}">
                            <x-button variant="outline-gold" size="sm">لوحة المدرب</x-button>
                        </a>
                        <a href="{{ route('courses.create') }}">
                            <x-button variant="gold" size="sm">+ إضافة دورة جديدة</x-button>
                        </a>
                    @elseif(auth()->user()->hasRole(['super_admin', 'admin']))
                        <a href="{{ route('admin.dashboard') }}">
                            <x-button variant="outline-gold" size="sm">مركز الإدارة</x-button>
                        </a>
                    @else
                        <a href="{{ route('student.dashboard') }}">
                            <x-button variant="gold" size="sm">لوحتي التعليمية</x-button>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}">
                        <x-button variant="outline-gold" size="sm">تسجيل الدخول</x-button>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-8">
        @if(session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-[#071A36] font-['Tajawal']">الدورات والبرامج المعتمدة</h1>
                <p class="text-xs text-slate-500 mt-1">تصفح مسارات نمذجة معلومات البناء وتطبيقات الهندسة الإنشائية والمعمارية</p>
            </div>
        </div>

        @if($courses->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="text-base font-bold text-[#071A36]">لا توجد دورات متاحة حالياً</h3>
                <p class="text-xs text-slate-500 mt-1">سيتم إضافة وتفعيل دبلومات BIM المعتمدة قريباً.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-[#D4AF37]/50 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-[#071A36]/10 text-[#071A36]">
                                    {{ $course->category?->name_ar ?? 'BIM عام' }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-[#123B68]">
                                    {{ $course->level }}
                                </span>
                            </div>

                            <h3 class="font-bold text-[#071A36] text-base mb-2 font-['Tajawal']">
                                {{ $course->title_ar ?? $course->title }}
                            </h3>
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                {{ $course->short_description_ar ?? $course->description }}
                            </p>

                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-500">المدرب: {{ $course->instructor?->name }}</span>
                                <span class="font-mono font-bold text-sm text-[#071A36]">
                                    {{ $course->effective_price }} {{ $course->currency }}
                                </span>
                            </div>
                        </div>

                        <div class="px-6 py-3 bg-[#F5F7FA] border-t border-slate-100 flex items-center justify-between">
                            @auth
                                @if(auth()->user()->hasRole(['super_admin', 'admin']) || (auth()->user()->hasRole('instructor') && $course->instructor_id === auth()->id()))
                                    <a href="{{ route('courses.curriculum', $course->id) }}" class="text-xs font-bold text-[#123B68] hover:underline">
                                        إدارة المنهج
                                    </a>
                                    <a href="{{ route('courses.edit', $course->id) }}">
                                        <x-button variant="outline" size="sm">تعديل</x-button>
                                    </a>
                                @else
                                    <span class="text-xs text-emerald-600 font-bold">دورة متاحة للتسجيل</span>
                                    <x-button variant="gold" size="sm">تفاصيل الدورة</x-button>
                                @endif
                            @else
                                <span class="text-xs text-emerald-600 font-bold">دورة متاحة للتسجيل</span>
                                <a href="{{ route('login') }}">
                                    <x-button variant="gold" size="sm">سجل الآن</x-button>
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $courses->links() }}
            </div>
        @endif
    </main>
</x-layouts.base>
