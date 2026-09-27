<x-layouts.base title="التقارير التنفيذية ومؤشرات الأداء — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg hover:opacity-95 transition">
                    B
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold font-['Tajawal'] text-white">مركز التقارير التنفيذية والتحليلات الأكاديمية</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#D4AF37] text-[#071A36]">
                            الإدارة العليا
                        </span>
                    </div>
                    <p class="text-xs text-slate-300">مؤشرات الإيرادات، سلوك المتعلمين، ومعدلات إتمام مسارات الـ BIM</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.reports.export_csv') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>تصدير CSV مالي</span>
                </a>
                <a href="{{ route('admin.dashboard') }}">
                    <x-button variant="outline-gold" size="sm">العودة للوحة التحكم</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-8" dir="rtl">
        <!-- Sub Navigation Bar -->
        <div class="flex items-center justify-between border-b border-slate-200 pb-4">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 rounded-xl bg-[#071A36] text-white text-xs font-bold shadow-sm">
                    النظرة التنفيذية العامة
                </a>
                <a href="{{ route('admin.reports.financial') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                    الدفتر المالي والتحصيلات
                </a>
                <a href="{{ route('admin.reports.academic') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                    تحليلات الإنجاز ومسارات التعلم
                </a>
            </div>

            <!-- Date Filter -->
            <form action="{{ route('admin.reports.index') }}" method="GET" class="flex items-center gap-2">
                <input type="date" name="start_date" value="{{ $financial['start_date'] }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs text-slate-700">
                <span class="text-xs text-slate-400">إلى</span>
                <input type="date" name="end_date" value="{{ $financial['end_date'] }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs text-slate-700">
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-[#123B68] text-white text-xs font-bold hover:bg-[#071A36] transition">
                    تصفية
                </button>
            </form>
        </div>

        <!-- Financial KPI Grid -->
        <div>
            <h2 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3">مؤشرات الأداء المالي (30 يوماً الأخيرة)</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 left-0 h-1 bg-[#D4AF37]"></div>
                    <span class="text-xs text-slate-500 block">إجمالي الإيرادات المحصلة</span>
                    <div class="flex items-baseline gap-1 mt-2">
                        <span class="text-3xl font-black text-[#071A36] font-mono">{{ number_format($financial['gross_revenue'], 2) }}</span>
                        <span class="text-xs font-bold text-[#D4AF37]">ر.س</span>
                    </div>
                    <span class="text-[10px] text-emerald-600 block mt-1">مدفوعات مؤكدة عبر البوابات</span>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 left-0 h-1 bg-emerald-500"></div>
                    <span class="text-xs text-slate-500 block">صافي الإيرادات (بعد الاستردادات)</span>
                    <div class="flex items-baseline gap-1 mt-2">
                        <span class="text-3xl font-black text-emerald-600 font-mono">{{ number_format($financial['net_revenue'], 2) }}</span>
                        <span class="text-xs font-bold text-slate-500">ر.س</span>
                    </div>
                    <span class="text-[10px] text-slate-400 block mt-1">الاستردادات: {{ number_format($financial['refunded_amount'], 2) }} ر.س</span>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 left-0 h-1 bg-blue-500"></div>
                    <span class="text-xs text-slate-500 block">الطلبات المكتملة</span>
                    <div class="flex items-baseline gap-1 mt-2">
                        <span class="text-3xl font-black text-[#123B68] font-mono">{{ $financial['completed_orders_count'] }}</span>
                        <span class="text-xs font-bold text-slate-500">طلب</span>
                    </div>
                    <span class="text-[10px] text-slate-400 block mt-1">عمليات شراء ناجحة</span>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 left-0 h-1 bg-amber-500"></div>
                    <span class="text-xs text-slate-500 block">متوسط قيمة السلة (AOV)</span>
                    <div class="flex items-baseline gap-1 mt-2">
                        <span class="text-3xl font-black text-amber-600 font-mono">{{ number_format($financial['average_order_value'], 2) }}</span>
                        <span class="text-xs font-bold text-slate-500">ر.س</span>
                    </div>
                    <span class="text-[10px] text-slate-400 block mt-1">لكل عملية شراء فردية</span>
                </div>
            </div>
        </div>

        <!-- Academic & Progression Highlights -->
        <div>
            <h2 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3">مؤشرات المشاركة والأداء الأكاديمي</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                    <span class="text-xs text-slate-500 block">الاشتراكات الفعالة</span>
                    <span class="text-2xl font-black text-[#071A36] font-mono mt-2 block">{{ $academic['active_enrollments'] }}</span>
                    <span class="text-[10px] text-slate-400 block mt-1">من إجمالي {{ $academic['total_enrollments'] }} تسجيل</span>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                    <span class="text-xs text-slate-500 block">نسبة إتمام المقررات الإجمالية</span>
                    <span class="text-2xl font-black text-emerald-600 font-mono mt-2 block">{{ $academic['overall_completion_rate'] }}%</span>
                    <span class="text-[10px] text-slate-400 block mt-1">{{ $academic['completed_enrollments'] }} طالب أتم 100% من المحتوى</span>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                    <span class="text-xs text-slate-500 block">متوسط درجات الاختبارات</span>
                    <span class="text-2xl font-black text-blue-600 font-mono mt-2 block">{{ $academic['average_exam_score'] }}%</span>
                    <span class="text-[10px] text-slate-400 block mt-1">نسبة النجاح: {{ $academic['exam_pass_rate'] }}%</span>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                    <span class="text-xs text-slate-500 block">مخالفات الأمان المراوغة</span>
                    <span class="text-2xl font-black text-rose-600 font-mono mt-2 block">{{ $academic['total_proctoring_violations'] }}</span>
                    <span class="text-[10px] text-rose-500 block mt-1">تم رصدها بواسطة محرك المراقبة</span>
                </div>
            </div>
        </div>

        <!-- Progress Funnel & Top Courses Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Progress Funnel -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-[#071A36] flex items-center justify-between">
                    <span>قمع إنجاز مسارات الـ BIM (Progression Funnel)</span>
                    <span class="text-xs font-normal text-slate-400">توزيع الطلاب حسب نسبة التقدم</span>
                </h3>
                <div class="space-y-3 pt-2">
                    <div>
                        <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                            <span>0% - 25% (مستوى البداية)</span>
                            <span class="font-mono">{{ $academic['progress_funnel']['0_25'] }} طالب</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-slate-400 h-full rounded-full" style="width: {{ $academic['total_enrollments'] > 0 ? ($academic['progress_funnel']['0_25'] / $academic['total_enrollments']) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                            <span>26% - 50% (المرحلة الأساسية)</span>
                            <span class="font-mono">{{ $academic['progress_funnel']['26_50'] }} طالب</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-blue-400 h-full rounded-full" style="width: {{ $academic['total_enrollments'] > 0 ? ($academic['progress_funnel']['26_50'] / $academic['total_enrollments']) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                            <span>51% - 75% (المرحلة المتقدمة)</span>
                            <span class="font-mono">{{ $academic['progress_funnel']['51_75'] }} طالب</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-amber-400 h-full rounded-full" style="width: {{ $academic['total_enrollments'] > 0 ? ($academic['progress_funnel']['51_75'] / $academic['total_enrollments']) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                            <span>76% - 99% (مشروع التخرج والتقييم)</span>
                            <span class="font-mono">{{ $academic['progress_funnel']['76_99'] }} طالب</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-[#123B68] h-full rounded-full" style="width: {{ $academic['total_enrollments'] > 0 ? ($academic['progress_funnel']['76_99'] / $academic['total_enrollments']) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-bold text-emerald-700 mb-1">
                            <span>100% (إتمام واستحقاق الشهادة)</span>
                            <span class="font-mono">{{ $academic['progress_funnel']['100'] }} طالب</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $academic['total_enrollments'] > 0 ? ($academic['progress_funnel']['100'] / $academic['total_enrollments']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Courses by Revenue -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-base font-bold text-[#071A36] flex items-center justify-between">
                    <span>أعلى الدورات الهندسية مبيعاً</span>
                    <span class="text-xs font-normal text-slate-400">إجمالي المبيعات المحققة</span>
                </h3>
                <div class="space-y-3 pt-2">
                    @forelse ($financial['top_courses'] as $tc)
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <div>
                                <h4 class="text-xs font-bold text-[#071A36]">{{ $tc['title'] }}</h4>
                                <span class="text-[10px] text-slate-400">{{ $tc['sales_count'] }} عملية تسجيل</span>
                            </div>
                            <div class="text-left font-mono">
                                <span class="text-sm font-black text-[#D4AF37]">{{ number_format($tc['total_revenue'], 2) }}</span>
                                <span class="text-[10px] text-slate-400">ر.س</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">لا توجد مبيعات مسجلة في النطاق الزمني المحدد.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</x-layouts.base>
