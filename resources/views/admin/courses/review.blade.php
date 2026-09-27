<x-layouts.base title="تدقيق المنهج الهندسي — {{ $course->title_ar }}">
    <!-- Admin Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.courses.pending') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg">
                        B
                    </div>
                    <div>
                        <span class="text-base font-black tracking-wider text-white font-['Tajawal']">{{ $course->title_ar }}</span>
                        <span class="block text-[10px] text-[#F3D98B] font-mono tracking-widest uppercase">التدقيق الأكاديمي الشامل للمنهج</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.courses.pending') }}">
                    <x-button variant="outline-gold" size="sm">العودة لقائمة الانتظار</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-8">
        @if(session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif

        <!-- Decision Box Top -->
        <div class="p-6 rounded-2xl bg-[#071A36] border border-[#D4AF37]/40 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-1">
                <span class="text-xs font-bold text-[#D4AF37] uppercase tracking-wider">قرار الاعتماد الأكاديمي</span>
                <h2 class="text-lg font-bold font-['Tajawal']">هل يستوفي هذا البرنامج معايير بيفوربيم الهندسية؟</h2>
                <p class="text-xs text-slate-300">بالموافقة، سيتم نشر الدورة مباشرة في الدليل وتفعيل مسار التسجيل والشهادات.</p>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <form method="POST" action="{{ route('admin.courses.approve', $course->id) }}">
                    @csrf
                    <x-button type="submit" variant="gold" class="shadow-lg shadow-[#D4AF37]/20">
                        ✓ اعتماد ونشر الدورة
                    </x-button>
                </form>
            </div>
        </div>

        <!-- Course Meta Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-sm">
                <h3 class="text-base font-bold text-[#071A36] border-b border-slate-100 pb-2">بيانات المنهج</h3>
                <p class="text-sm text-slate-700 leading-relaxed">{{ $course->description_ar ?: $course->short_description_ar }}</p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-3 border-t border-slate-100 text-xs">
                    <div>
                        <span class="text-slate-400 block">التصنيف:</span>
                        <span class="font-bold text-[#071A36]">{{ $course->category?->name_ar }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">المستوى:</span>
                        <span class="font-bold text-[#071A36]">{{ $course->level }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">السعر:</span>
                        <span class="font-bold font-mono text-[#071A36]">{{ $course->effective_price }} {{ $course->currency }}</span>
                    </div>
                </div>
            </div>

            <!-- Instructor Profile Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-3 shadow-sm">
                <h3 class="text-sm font-bold text-[#071A36] border-b border-slate-100 pb-2">بيانات المدرب</h3>
                <p class="font-bold text-sm text-[#071A36]">{{ $course->instructor?->name }}</p>
                <p class="text-xs text-slate-500 font-mono">{{ $course->instructor?->email }}</p>
                <div class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <p class="font-semibold text-slate-700">التخصص الهندسي:</p>
                    <p>{{ $course->instructor?->instructorProfile?->specialization ?: 'خبير استشاري BIM' }}</p>
                    <p class="font-semibold text-slate-700 mt-2">سنوات الخبرة:</p>
                    <p>{{ $course->instructor?->instructorProfile?->experience_years ?: 'غير محدد' }} سنة</p>
                </div>
            </div>
        </div>

        <!-- Curriculum Inspection Tree -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-sm">
            <h3 class="text-base font-bold text-[#071A36] border-b border-slate-100 pb-2 flex items-center justify-between">
                <span>محتوى الفصول والمحاضرات ({{ $course->sections->count() }} أقسام)</span>
                <span class="text-xs font-mono text-slate-500">إجمالي {{ $course->lessons->count() }} محاضرة</span>
            </h3>

            @if($course->sections->isEmpty())
                <p class="text-xs text-slate-400 text-center py-4">الدورة لا تحتوي على أي أقسام!</p>
            @else
                <div class="space-y-4">
                    @foreach($course->sections as $secIndex => $sec)
                        <div class="rounded-xl border border-slate-200 overflow-hidden">
                            <div class="bg-[#F5F7FA] px-4 py-3 border-b border-slate-200 font-bold text-xs text-[#071A36] flex items-center justify-between">
                                <span>{{ $secIndex + 1 }}. {{ $sec->title_ar }}</span>
                                <span class="text-[11px] font-mono text-slate-500">{{ $sec->lessons->count() }} دروس</span>
                            </div>
                            <div class="p-3 divide-y divide-slate-100 text-xs">
                                @foreach($sec->lessons as $lesIndex => $les)
                                    <div class="py-2 flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-slate-400">{{ $lesIndex + 1 }}</span>
                                            <span class="font-medium text-slate-800">{{ $les->title_ar }}</span>
                                            <span class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded">{{ $les->lesson_type }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 font-mono text-slate-500">
                                            <span>{{ round($les->duration_seconds / 60) }} د</span>
                                            @if($les->resources->isNotEmpty())
                                                <span class="text-[10px] bg-blue-50 text-[#123B68] px-2 py-0.5 rounded">
                                                    {{ $les->resources->count() }} ملفات BIM
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Rejection Form -->
        <div class="bg-white rounded-2xl border border-rose-200 p-6 space-y-4 shadow-sm">
            <h3 class="text-sm font-bold text-rose-700 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-rose-600"></span>
                إرجاع الدورة للمدرب مع إبداء الملاحظات والتعديلات المطلوبة
            </h3>
            <form method="POST" action="{{ route('admin.courses.reject', $course->id) }}" class="space-y-4">
                @csrf
                <div class="space-y-1.5 text-start">
                    <label for="rejection_feedback" class="block text-xs font-semibold text-slate-700">
                        ملاحظات التدقيق الأكاديمي (سيتم إرسالها إلى المدرب):
                    </label>
                    <textarea name="rejection_feedback" id="rejection_feedback" rows="3" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-xs focus:border-rose-500 focus:ring-2 focus:ring-rose-200" placeholder="مثال: يرجى إضافة نماذج الـ RVT للفصل الثالث، وتحديد مخرجات التعلم بشكل أوضح..."></textarea>
                </div>
                <div class="flex justify-end">
                    <x-button type="submit" variant="danger" size="sm">
                        إرجاع الدورة بملاحظات
                    </x-button>
                </div>
            </form>
        </div>
    </main>
</x-layouts.base>
