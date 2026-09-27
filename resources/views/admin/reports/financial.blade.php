<x-layouts.base title="الدفتر المالي والتحصيلات — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.reports.index') }}" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg hover:opacity-95 transition">
                    B
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold font-['Tajawal'] text-white">الدفتر المالي ومطابقة بوابات الدفع</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#D4AF37] text-[#071A36]">
                            الإدارة المالية
                        </span>
                    </div>
                    <p class="text-xs text-slate-300">سجل المعاملات والمدفوعات والتحويلات البنكية المكتملة</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.reports.export_csv') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>تحميل كشف الحساب (CSV)</span>
                </a>
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
            <a href="{{ route('admin.reports.financial') }}" class="px-4 py-2 rounded-xl bg-[#071A36] text-white text-xs font-bold shadow-sm">
                الدفتر المالي والتحصيلات
            </a>
            <a href="{{ route('admin.reports.academic') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                تحليلات الإنجاز ومسارات التعلم
            </a>
        </div>

        <!-- Payment Gateways Distribution -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <h3 class="text-base font-bold text-[#071A36]">توزيع المدفوعات حسب البوابات وطرق الدفع</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 pt-2">
                @forelse ($financial['payment_methods'] as $pm)
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <span class="text-xs font-bold text-[#071A36] block">{{ $pm['method'] }}</span>
                        <span class="text-xl font-black text-[#123B68] font-mono mt-1 block">{{ number_format($pm['total'], 2) }} <span class="text-[10px] text-slate-400 font-sans">ر.س</span></span>
                        <span class="text-[10px] text-slate-400 mt-1 block">{{ $pm['count'] }} معاملة ناجحة</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 col-span-full py-2">لا توجد بيانات طرق دفع مسجلة.</p>
                @endforelse
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden space-y-4">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-[#071A36]">سجل العمليات المالية والتحصيلات</h3>
                    <p class="text-xs text-slate-400 mt-0.5">تفاصيل العمليات المستلمة عبر البوابات الإلكترونية والتحويل البنكي</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold border-y border-slate-200">
                        <tr>
                            <th class="py-3 px-4">رقم المعاملة</th>
                            <th class="py-3 px-4">الطلب</th>
                            <th class="py-3 px-4">العميل</th>
                            <th class="py-3 px-4">المبلغ</th>
                            <th class="py-3 px-4">طريقة الدفع</th>
                            <th class="py-3 px-4">الحالة</th>
                            <th class="py-3 px-4">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($payments as $payment)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-4 font-mono text-[11px] text-slate-600">{{ substr($payment->uuid, 0, 13) }}...</td>
                                <td class="py-3 px-4 font-mono font-bold text-[#071A36]">{{ $payment->order?->order_number ?? '—' }}</td>
                                <td class="py-3 px-4 font-bold text-slate-800">{{ $payment->order?->user?->name ?? 'مستخدم محذوف' }}</td>
                                <td class="py-3 px-4 font-mono font-black text-emerald-600">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                        {{ $payment->payment_method }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    @if ($payment->status === 'SUCCESS')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">مكتمل</span>
                                    @elseif ($payment->status === 'REFUNDED')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800">مسترد</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">{{ $payment->status }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-slate-400 font-mono">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">لا توجد عمليات دفع مسجلة.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $payments->links() }}
            </div>
        </div>
    </main>
</x-layouts.base>
