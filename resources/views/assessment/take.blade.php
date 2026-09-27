<x-layouts.base :title="'أداء الاختبار — ' . $assessment->title_ar">
    <div class="min-h-screen bg-[#F5F7FA] py-8 px-4" x-data="{
        violations: {{ $attempt->anti_cheat_violations_count }},
        maxViolations: {{ $assessment->max_violations_allowed ?? 3 }},
        init() {
            @if ($assessment->is_proctored_mode || $assessment->monitor_tab_switch)
                window.addEventListener('blur', () => {
                    this.reportViolation('TAB_BLUR', 'الطالب قام بالخروج من نافذة الاختبار');
                });
            @endif
        },
        async reportViolation(eventType, details) {
            try {
                const res = await fetch('{{ route('assessment.security_violation', $attempt->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ event_type: eventType, details: details })
                });
                const data = await res.json();
                this.violations = data.violations_count;
                if (data.is_disqualified) {
                    alert('تم استبعاد المحاولة لتكرار مغادرة شاشة الاختبار.');
                    window.location.href = '{{ route('assessment.result', $attempt->id) }}';
                }
            } catch (e) {
                console.error(e);
            }
        }
    }">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Fixed Top Bar -->
            <div class="bg-[#071A36] text-white rounded-2xl p-4 flex items-center justify-between shadow-lg sticky top-4 z-30">
                <div>
                    <h2 class="text-sm font-bold text-white">{{ $assessment->title_ar }}</h2>
                    <p class="text-xs text-slate-400">المحاولة رقم {{ $attempt->attempt_number }}</p>
                </div>
                <div class="flex items-center gap-4 text-xs">
                    @if ($assessment->is_proctored_mode)
                        <div class="flex items-center gap-1.5 bg-rose-500/20 text-rose-300 px-3 py-1 rounded-full border border-rose-500/30">
                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                            <span>المخالفات: <strong x-text="violations">{{ $attempt->anti_cheat_violations_count }}</strong>/{{ $assessment->max_violations_allowed }}</span>
                        </div>
                    @endif
                    <div class="bg-white/10 px-3 py-1 rounded-full text-[#F3D98B] font-bold">
                        {{ $questions->count() }} أسئلة
                    </div>
                </div>
            </div>

            <!-- Exam Questions Form -->
            <form action="{{ route('assessment.submit', $attempt->id) }}" method="POST" class="space-y-6">
                @csrf
                @foreach ($questions as $qIndex => $question)
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-xl bg-[#123B68] text-white font-bold text-xs flex items-center justify-center shrink-0">
                                    {{ $qIndex + 1 }}
                                </span>
                                <h3 class="text-sm md:text-base font-bold text-[#071A36]">{{ $question->question_text_ar }}</h3>
                            </div>
                            <span class="text-xs font-semibold text-slate-400 shrink-0 bg-slate-50 px-2.5 py-1 rounded-lg">
                                {{ $question->points }} درجات
                            </span>
                        </div>

                        <!-- Options Container -->
                        <div class="space-y-2 pr-9">
                            @if (!empty($question->options))
                                @foreach ($question->options as $optIndex => $opt)
                                    <label class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 hover:border-[#D4AF37] hover:bg-slate-50 cursor-pointer transition">
                                        <input type="{{ $question->question_type === 'MULTIPLE_CHOICE' ? 'checkbox' : 'radio' }}"
                                            name="answers[{{ $question->id }}]{{ $question->question_type === 'MULTIPLE_CHOICE' ? '[]' : '' }}"
                                            value="{{ $opt['id'] ?? $opt['text_ar'] }}"
                                            class="text-[#D4AF37] focus:ring-[#D4AF37]">
                                        <span class="text-xs md:text-sm text-slate-700">{{ $opt['text_ar'] ?? '' }}</span>
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach

                <!-- Submit Button -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 flex items-center justify-between">
                    <span class="text-xs text-slate-500">تأكد من مراجعة كافة الإجابات قبل التأكيد النهائي.</span>
                    <button type="submit" onclick="return confirm('هل أنت متأكد من تسليم الإجابات وإنهاء الاختبار؟')" class="px-8 py-3 bg-gradient-to-r from-[#D4AF37] to-[#F3D98B] text-[#071A36] font-bold text-sm rounded-xl hover:opacity-95 shadow-lg shadow-[#D4AF37]/20 transition">
                        تسليم إجابات الاختبار
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.base>
