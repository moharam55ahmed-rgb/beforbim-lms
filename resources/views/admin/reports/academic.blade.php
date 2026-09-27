<x-layouts.base title="تحليلات الإنجاز ومسارات التعلم — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.reports.index') }}" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg hover:opacity-95 transition">
                    B
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold font-['Tajawal'] text-white">تحليلات الإنجاز ومسارات الـ BIM</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#D4AF37] text-[#071A36]">
                            الإدارة الأكاديمية
                        </span>
                    </div>
                    <p class="text-xs text-slate-300">متابعة انخراط الطلاب، الاختبارات الهندسية، ومؤشرات التخرج</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.reports.index') }}">
                    <x-button variant="outline-gold" size="sm">العودة لمركز التقارير</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-8" dir="rtl">
        <!-- Sub Navigation Bar -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-4">
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                النظرة التنفيذية العامة
            </a>
            <a href="{{ route('admin.reports.financial') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                الدفتر المالي والتحصيلات
            </a>
            <a href="{{ route('admin.reports.academic') }}" class="px-4 py-2 rounded-xl bg-[#071A36] text-white text-xs font-bold shadow-sm">
                تحليلات الإنجاز ومسارات التعلم
            </a>
        </div>

        <!-- Academic Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <span class="text-xs text-slate-500 block">إجمالي التسجيلات الأكاديمية</span>
                <span class="text-3xl font-black text-[#071A36] font-mono mt-2 block">{{ $academic['total_enrollments'] }}</span>
                <span class="text-[10px] text-emerald-600 block mt-1">{{ $academic['active_enrollments'] }} اشتراك نشط حالياً</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <span class="text-xs text-slate-500 block">متوسط تقدم الطلاب في المناهج</span>
                <span class="text-3xl font-black text-blue-600 font-mono mt-2 block">{{ $academic['average_progress'] }}%</span>
                <span class="text-[10px] text-slate-400 block mt-1">عبر كافة المحتويات التفاعلية</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <span class="text-xs text-slate-500 block">محاولات الاختبارات التقييمية</span>
                <span class="text-3xl font-black text-[#123B68] font-mono mt-2 block">{{ $academic['total_assessment_attempts'] }}</span>
                <span class="text-[10px] text-emerald-600 block mt-1">نسبة النجاح: {{ $academic['exam_pass_rate'] }}%</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                <span class="text-xs text-slate-500 block">الشهادات المستحقة (100% إتمام)</span>
                <span class="text-3xl font-black text-emerald-600 font-mono mt-2 block">{{ $academic['completed_enrollments'] }}</span>
                <span class="text-[10px] text-slate-400 block mt-1">معدل الإنجاز العام: {{ $academic['overall_completion_rate'] }}%</span>
            </div>
        </div>

        <!-- Funnel and Top Enrolled Courses -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-[#071A36]">تحليل قمع الانسحاب والتقدم (Drop-off Funnel)</h3>
                <p class="text-xs text-slate-500">يساعد هذا المؤشر في تحديد المحطات الدراسية التي يواجه فيها مهندسو الـ BIM صعوبات أو انخفاضاً في المتابعة.</p>
                
                <div class="space-y-4 pt-2">
                    <div class="flex items-center gap-4">
                        <span class="w-24 text-xs font-bold text-slate-600">البداية (0-25%)</span>
                        <div class="flex-1 bg-slate-100 h-3 rounded-full overflow-hidden">
                            <div class="bg-slate-400 h-full rounded-full" style="width: {{ $academic['total_enrollments'] > 0 ? ($academic['progress_funnel']['0_25'] / $academic['total_enrollments']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="font-mono text-xs font-bold w-12 text-left">{{ $academic['progress_funnel']['0_25'] }}</span>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="w-24 text-xs font-bold text-slate-600">المرحلة 2 (26-50%)</span>
                        <div class="flex-1 bg-slate-100 h-3 rounded-full overflow-hidden">
                            <div class="bg-blue-400 h-full rounded-full" style="width: {{ $academic['total_enrollments'] > 0 ? ($academic['progress_funnel']['26_50'] / $academic['total_enrollments']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="font-mono text-xs font-bold w-12 text-left">{{ $academic['progress_funnel']['26_50'] }}</span>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="w-24 text-xs font-bold text-slate-600">المرحلة 3 (51-75%)</span>
                        <div class="flex-1 bg-slate-100 h-3 rounded-full overflow-hidden">
                            <div class="bg-amber-400 h-full rounded-full" style="width: {{ $academic['total_enrollments'] > 0 ? ($academic['progress_funnel']['51_75'] / $academic['total_enrollments']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="font-mono text-xs font-bold w-12 text-left">{{ $academic['progress_funnel']['51_75'] }}</span>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="w-24 text-xs font-bold text-slate-600">المرحلة 4 (76-99%)</span>
                        <div class="flex-1 bg-slate-100 h-3 rounded-full overflow-hidden">
                            <div class="bg-[#123B68] h-full rounded-full" style="width: {{ $academic['total_enrollments'] > 0 ? ($academic['progress_funnel']['76_99'] / $academic['total_enrollments']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="font-mono text-xs font-bold w-12 text-left">{{ $academic['progress_funnel']['76_99'] }}</span>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="w-24 text-xs font-bold text-emerald-700">التخرج (100%)</span>
                        <div class="flex-1 bg-slate-100 h-3 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $academic['total_enrollments'] > 0 ? ($academic['progress_funnel']['100'] / $academic['total_enrollments']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="font-mono text-xs font-bold w-12 text-left text-emerald-600">{{ $academic['progress_funnel']['100'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Top Courses by Enrollment -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-[#071A36]">أعلى المقررات إقبالاً وتسجيلاً</h3>
                <div class="space-y-3 pt-2">
                    @forelse ($academic['top_enrolled_courses'] as $tc)
                        <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 rounded-lg bg-[#071A36] text-white flex items-center justify-center font-bold text-xs">
                                    {{ $loop->iteration }}
                                </span>
                                <div>
                                    <h4 class="text-xs font-bold text-[#071A36]">{{ $tc['title'] }}</h4>
                                    <span class="text-[10px] text-slate-400">كود المقرر: #{{ $tc['id'] }}</span>
                                </div>
                            </div>
                            <div class="text-left font-mono">
                                <span class="text-sm font-black text-[#123B68]">{{ $tc['enrollments_count'] }}</span>
                                <span class="text-[10px] text-slate-400">طالب</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">لا توجد بيانات تسجيلات حتى الآن.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</x-layouts.base>
