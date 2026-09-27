<x-layouts.base title="تقارير الأرباح والمستحقات — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('instructor.dashboard') }}" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg hover:opacity-95 transition">
                    B
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold font-['Tajawal'] text-white">تقرير المستحقات ومبيعات المقررات</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#D4AF37] text-[#071A36]">
                            لوحة المدرب
                        </span>
                    </div>
                    <p class="text-xs text-slate-300">متابعة العوائد المالية، نسب التحصيل، وتسجيلات الطلاب في دوراتك</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('instructor.dashboard') }}">
                    <x-button variant="outline-gold" size="sm">العودة للاستوديو</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-8" dir="rtl">
        <!-- Earnings KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 left-0 h-1 bg-emerald-500"></div>
                <span class="text-xs text-slate-500 block">صافي أرباحك المستحقة</span>
                <div class="flex items-baseline gap-1 mt-2">
                    <span class="text-3xl font-black text-emerald-600 font-mono">{{ number_format($earnings['instructor_earnings'], 2) }}</span>
                    <span class="text-xs font-bold text-slate-500">ر.س</span>
                </div>
                <span class="text-[10px] text-slate-400 block mt-1">نسبتك: {{ $earnings['instructor_share_rate'] }}% من إجمالي المبيعات</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 left-0 h-1 bg-[#D4AF37]"></div>
                <span class="text-xs text-slate-500 block">إجمالي مبيعات دوراتك</span>
                <div class="flex items-baseline gap-1 mt-2">
                    <span class="text-3xl font-black text-[#071A36] font-mono">{{ number_format($earnings['total_sales_amount'], 2) }}</span>
                    <span class="text-xs font-bold text-[#D4AF37]">ر.س</span>
                </div>
                <span class="text-[10px] text-slate-400 block mt-1">المبيعات الإجمالية المحصلة</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 left-0 h-1 bg-blue-500"></div>
                <span class="text-xs text-slate-500 block">إجمالي المبيعات الفردية</span>
                <div class="flex items-baseline gap-1 mt-2">
                    <span class="text-3xl font-black text-[#123B68] font-mono">{{ $earnings['total_sales_count'] }}</span>
                    <span class="text-xs font-bold text-slate-500">عملية شراء</span>
                </div>
                <span class="text-[10px] text-slate-400 block mt-1">عبر كافة دوراتك المنشورة</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 left-0 h-1 bg-slate-300"></div>
                <span class="text-xs text-slate-500 block">عمولة تشغيل المنصة</span>
                <div class="flex items-baseline gap-1 mt-2">
                    <span class="text-3xl font-black text-slate-500 font-mono">{{ number_format($earnings['platform_commission'], 2) }}</span>
                    <span class="text-xs font-bold text-slate-500">ر.س</span>
                </div>
                <span class="text-[10px] text-slate-400 block mt-1">نسبة المنصة: {{ $earnings['platform_commission_rate'] }}%</span>
            </div>
        </div>

        <!-- Breakdown by Course -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-base font-bold text-[#071A36]">تفاصيل العوائد لكل دورة تدريبية</h3>
                <p class="text-xs text-slate-400 mt-0.5">تفصيل الحصص والمبيعات لجميع مسارات الـ BIM الخاصة بك</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold border-y border-slate-200">
                        <tr>
                            <th class="py-3 px-4">عنوان الدورة الهندسية</th>
                            <th class="py-3 px-4">عدد الطلاب المسجلين</th>
                            <th class="py-3 px-4">إجمالي المبيعات</th>
                            <th class="py-3 px-4">صافي حصتك</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($earnings['courses_breakdown'] as $c)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3.5 px-4 font-bold text-[#071A36]">{{ $c['title'] }}</td>
                                <td class="py-3.5 px-4 font-mono">{{ $c['sales_count'] }} طالب</td>
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-700">{{ number_format($c['gross_revenue'], 2) }} ر.س</td>
                                <td class="py-3.5 px-4 font-mono font-black text-emerald-600">{{ number_format($c['instructor_share'], 2) }} ر.س</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400">لا توجد مبيعات مسجلة لدوراتك بعد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</x-layouts.base>
