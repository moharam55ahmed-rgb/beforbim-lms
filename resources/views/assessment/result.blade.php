<x-layouts.base :title="'نتيجة الاختبار — ' . $assessment->title_ar">
    <div class="min-h-screen bg-[#F5F7FA] py-12 px-4">
        <div class="max-w-xl mx-auto space-y-6">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200/80 text-center">
                <!-- Status Icon -->
                @if ($attempt->status === 'DISQUALIFIED')
                    <div class="w-20 h-20 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h2 class="text-2xl font-black text-rose-700 mb-2">تم استبعاد المحاولة</h2>
                    <p class="text-xs text-rose-600 mb-6">{{ $attempt->audit_notes }}</p>
                @elseif ($attempt->passed)
                    <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-2xl font-black text-emerald-700 mb-1">تهانينا! لقد اجتزت الاختبار بنجاح</h2>
                    <p class="text-xs text-slate-500 mb-6">{{ $assessment->title_ar }}</p>
                @else
                    <div class="w-20 h-20 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-2xl font-black text-slate-800 mb-1">لم توفق في اجتياز الاختبار</h2>
                    <p class="text-xs text-slate-500 mb-6">يمكنك مراجعة الدروس والمحاولة مرة أخرى</p>
                @endif

                <!-- Score Pill -->
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 mb-6">
                    <span class="text-xs text-slate-400 block mb-1">النتيجة النهائية المحققة</span>
                    <span class="text-4xl font-black {{ $attempt->passed ? 'text-emerald-600' : 'text-slate-800' }}">
                        {{ $attempt->score_percentage }}%
                    </span>
                    <div class="flex items-center justify-center gap-6 mt-4 pt-4 border-t border-slate-200 text-xs text-slate-500">
                        <span>النقاط: <strong>{{ $attempt->total_points_earned }}</strong> من {{ $attempt->total_points_possible }}</span>
                        <span>نسبة النجاح المطلوبة: <strong>{{ $assessment->passing_score_percentage }}%</strong></span>
                    </div>
                </div>

                <!-- Navigation Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('learn.player', $course->slug) }}" class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-[#071A36] to-[#123B68] text-white font-bold text-xs rounded-xl hover:opacity-95 transition">
                        العودة إلى مشغل الدورة
                    </a>
                    <a href="{{ route('student.dashboard') }}" class="w-full sm:w-auto px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                        لوحة التحكم
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.base>
