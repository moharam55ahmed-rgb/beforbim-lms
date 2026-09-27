<x-layouts.base :title="$assessment->title_ar . ' — ' . $course->title_ar">
    <div class="min-h-screen bg-[#F5F7FA] py-12 px-4 md:px-8">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#071A36] to-[#123B68] rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10">
                    <span class="inline-block bg-[#D4AF37]/20 text-[#D4AF37] px-3 py-1 rounded-full text-xs font-bold mb-3">
                        {{ $assessment->type === 'FINAL_CERTIFICATION_EXAM' ? 'امتحان التخرج والاعتماد النهائي' : 'اختبار تقييم هندسي' }}
                    </span>
                    <h1 class="text-2xl md:text-3xl font-extrabold mb-2">{{ $assessment->title_ar }}</h1>
                    <p class="text-sm text-slate-300">{{ $course->title_ar }}</p>
                </div>
            </div>

            <!-- Parameters Card -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200/80">
                <h3 class="text-base font-bold text-[#071A36] mb-6">تعليمات وإرشادات الاختبار:</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                    <div class="bg-slate-50 rounded-2xl p-4 text-center border border-slate-100">
                        <span class="block text-xs text-slate-500 mb-1">المدة الزمنية</span>
                        <span class="text-lg font-bold text-[#071A36]">{{ $assessment->time_limit_minutes ? $assessment->time_limit_minutes . ' دقيقة' : 'مفتوح' }}</span>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 text-center border border-slate-100">
                        <span class="block text-xs text-slate-500 mb-1">نسبة الاجتياز</span>
                        <span class="text-lg font-bold text-emerald-600">{{ $assessment->passing_score_percentage }}%</span>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 text-center border border-slate-100">
                        <span class="block text-xs text-slate-500 mb-1">عدد المحاولات</span>
                        <span class="text-lg font-bold text-[#071A36]">{{ $assessment->max_attempts > 0 ? $assessment->max_attempts : 'غير محدود' }}</span>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 text-center border border-slate-100">
                        <span class="block text-xs text-slate-500 mb-1">المحاولات السابقة</span>
                        <span class="text-lg font-bold text-[#123B68]">{{ $pastAttempts->count() }}</span>
                    </div>
                </div>

                @if ($assessment->is_proctored_mode)
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-xs text-amber-800 mb-6 flex items-start gap-3">
                        <svg class="w-5 h-5 shrink-0 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>
                            <span class="font-bold block mb-1">تنبيه أمني صارم (وضع المراقبة مفعل):</span>
                            يمنع مغادرة شاشة الاختبار أو التبديل بين النوافذ أو الخروج من وضع ملء الشاشة. أي مخالفات متكررة تؤدي إلى استبعاد المحاولة تلقائياً.
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <a href="{{ route('learn.player', $course->slug) }}" class="text-xs text-slate-500 hover:text-slate-800">
                        &larr; العودة إلى مشغل الدورة
                    </a>

                    @if ($eligibility['allowed'])
                        <form action="{{ route('assessment.start', [$course->slug, $assessment->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-[#D4AF37] to-[#F3D98B] text-[#071A36] font-bold text-sm rounded-xl hover:opacity-95 shadow-lg shadow-[#D4AF37]/20 transition">
                                {{ !empty($eligibility['resumed']) ? 'استئناف الاختبار' : 'بدء الاختبار الآن' }}
                            </button>
                        </form>
                    @else
                        <div class="text-xs font-semibold text-rose-600 bg-rose-50 px-4 py-2 rounded-xl">
                            {{ $eligibility['reason'] ?? 'غير متاح حالياً' }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Past Attempts -->
            @if ($pastAttempts->isNotEmpty())
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80">
                    <h4 class="text-sm font-bold text-[#071A36] mb-4">سجل المحاولات السابقة:</h4>
                    <div class="divide-y divide-slate-100">
                        @foreach ($pastAttempts as $past)
                            <div class="py-3 flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-bold text-slate-800">المحاولة رقم {{ $past->attempt_number }}</span>
                                    <span class="text-slate-400 mr-2">({{ $past->created_at->format('Y-m-d H:i') }})</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-bold {{ $past->passed ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $past->score_percentage }}% ({{ $past->passed ? 'ناجح' : 'راسب' }})
                                    </span>
                                    <a href="{{ route('assessment.result', $past->id) }}" class="text-blue-600 hover:underline">عرض النتيجة</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.base>
