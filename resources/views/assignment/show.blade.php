<x-layouts.base :title="$assignment->title_ar . ' — ' . $course->title_ar">
    <div class="min-h-screen bg-[#F5F7FA] py-12 px-4 md:px-8">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Assignment Header -->
            <div class="bg-gradient-to-r from-[#071A36] to-[#123B68] rounded-3xl p-8 text-white shadow-xl">
                <span class="inline-block bg-[#D4AF37]/20 text-[#D4AF37] px-3 py-1 rounded-full text-xs font-bold mb-3">
                    مشروع تطبيقي هندسي / واجب
                </span>
                <h1 class="text-2xl md:text-3xl font-extrabold mb-2">{{ $assignment->title_ar }}</h1>
                <p class="text-sm text-slate-300">{{ $course->title_ar }}</p>
            </div>

            <!-- Instructions & Requirements -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200/80 space-y-6">
                <div>
                    <h3 class="text-base font-bold text-[#071A36] mb-3">مواصفات ومطلوبات المشروع:</h3>
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 text-sm text-slate-700 leading-relaxed">
                        {!! nl2br(e($assignment->instructions_ar)) !!}
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs">
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <span class="text-slate-400 block mb-1">الدرجة الكلية</span>
                        <span class="text-base font-bold text-[#071A36]">{{ $assignment->total_points }} درجة</span>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <span class="text-slate-400 block mb-1">تاريخ التسليم الأخير</span>
                        <span class="text-base font-bold text-[#123B68]">{{ $assignment->due_date ? $assignment->due_date->format('Y-m-d') : 'مفتوح' }}</span>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <span class="text-slate-400 block mb-1">الصيغ المدعومة</span>
                        <span class="text-base font-bold text-slate-700 uppercase">{{ implode(', ', $assignment->allowed_file_types ?? ['rvt', 'dwg', 'pdf', 'zip']) }}</span>
                    </div>
                </div>

                <!-- Submission Status / Feedback -->
                @if ($submission)
                    <div class="border-t border-slate-100 pt-6">
                        <h4 class="text-sm font-bold text-[#071A36] mb-3">حالة تسليمك:</h4>
                        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-bold text-blue-950">الملف المسلّم: {{ $submission->file_name }}</span>
                                    <span class="text-blue-600 mr-2">({{ $submission->submitted_at->format('Y-m-d H:i') }})</span>
                                </div>
                                <span class="px-2.5 py-1 rounded-full font-bold {{ $submission->status === 'GRADED' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $submission->status === 'GRADED' ? 'تم التقييم' : 'قيد مراجعة المدرب' }}
                                </span>
                            </div>

                            @if ($submission->status === 'GRADED')
                                <div class="bg-white rounded-xl p-4 border border-blue-100 flex items-center justify-between">
                                    <div>
                                        <span class="text-xs text-slate-500 block">درجة التقييم:</span>
                                        <span class="text-2xl font-black text-emerald-600">{{ $submission->grade }} / {{ $assignment->total_points }}</span>
                                    </div>
                                    @if ($submission->instructor_feedback)
                                        <div class="text-left text-xs text-slate-600 max-w-sm">
                                            <span class="font-bold text-slate-800 block">ملاحظات المدرب:</span>
                                            {{ $submission->instructor_feedback }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Submission Form -->
                    <form action="{{ route('assignment.submit', [$course->slug, $assignment->id]) }}" method="POST" enctype="multipart/form-data" class="border-t border-slate-100 pt-6 space-y-4">
                        @csrf
                        <h4 class="text-sm font-bold text-[#071A36]">تسليم المشروع الهندسي:</h4>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">ملاحظاتك الهندسية على الحل (اختياري):</label>
                            <textarea name="notes" rows="3" placeholder="اكتب أي ملاحظات أو افتراضات تصميمية تم استخدامها..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-[#D4AF37] resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">رفع ملف المشروع (RVT, DWG, PDF, ZIP):</label>
                            <input type="file" name="file" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#123B68] file:text-white hover:file:bg-[#071A36] cursor-pointer">
                        </div>
                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-[#D4AF37] to-[#F3D98B] text-[#071A36] font-bold text-xs rounded-xl hover:opacity-95 shadow-lg shadow-[#D4AF37]/20 transition">
                                إرسال المشروع للتقييم
                            </button>
                        </div>
                    </form>
                @endif

                <div class="pt-4 border-t border-slate-100">
                    <a href="{{ route('learn.player', $course->slug) }}" class="text-xs text-slate-500 hover:text-slate-800">
                        &larr; العودة إلى مشغل الدورة
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.base>
