<x-layouts.base title="تدقيق المدفوعات والتحويلات البنكية — Beforbim">
    <!-- Admin Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg">
                        B
                    </div>
                    <div>
                        <span class="text-base font-black tracking-wider text-white font-['Tajawal']">التدقيق المالي للتحويلات</span>
                        <span class="block text-[10px] text-[#F3D98B] font-mono tracking-widest uppercase">مطابقة وتفعيل الاشتراكات</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}">
                    <x-button variant="outline-gold" size="sm">العودة للوحة الإدارة</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6">
        @if(session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#071A36] font-['Tajawal']">الحوالات البنكية بانتظار التأكيد والاعتماد</h1>
                <p class="text-xs text-slate-500 mt-1">تأكد من مطابقة كشف الحساب البنكي قبل تفعيل الوصول إلى المناهج الهندسية</p>
            </div>
            <span class="px-3 py-1 rounded-xl text-xs font-bold font-mono bg-amber-100 text-amber-900 border border-amber-300">
                {{ $payments->total() }} عملية بانتظار المطابقة
            </span>
        </div>

        @if($payments->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <svg class="w-12 h-12 text-emerald-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-base font-bold text-[#071A36]">لا توجد حوالات بنكية معلقة حالياً</h3>
                <p class="text-xs text-slate-500 mt-1">تمت مراجعة جميع العمليات وتفعيل اشتراكات الطلاب.</p>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <table class="w-full text-right text-xs">
                    <thead class="bg-[#F5F7FA] text-slate-600 uppercase font-semibold border-b border-slate-200">
                        <tr>
                            <th class="p-4">رقم الطلب</th>
                            <th class="p-4">الطالب</th>
                            <th class="p-4">المبلغ</th>
                            <th class="p-4">الدورات المطلوبة</th>
                            <th class="p-4">تاريخ الإرسال</th>
                            <th class="p-4">الإيصال المرفق</th>
                            <th class="p-4 text-center">القرار</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-4 font-mono font-bold text-[#071A36]">
                                    {{ $payment->order?->order_number }}
                                </td>
                                <td class="p-4">
                                    <p class="font-bold text-slate-900">{{ $payment->order?->user?->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $payment->order?->user?->email }}</p>
                                </td>
                                <td class="p-4 font-mono font-bold text-base text-[#D4AF37]">
                                    {{ $payment->amount }} {{ $payment->currency }}
                                </td>
                                <td class="p-4 text-slate-600">
                                    {{ $payment->order?->items->pluck('title_snapshot')->join(', ') }}
                                </td>
                                <td class="p-4 font-mono text-slate-400">
                                    {{ $payment->created_at->diffForHumans() }}
                                </td>
                                <td class="p-4">
                                    @if($payment->receipt_attachment_url)
                                        <a href="{{ $payment->receipt_attachment_url }}" target="_blank" class="text-blue-600 underline font-bold">
                                            معاينة الإيصال &nearr;
                                        </a>
                                    @else
                                        <span class="text-slate-400">لا يوجد إيصال مرفق</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <form method="POST" action="{{ route('admin.payments.verify', $payment->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] transition-colors">
                                                تأكيد وتفعيل الاشتراك
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.payments.reject', $payment->id) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="reason" value="عدم وضوح صورة الإيصال أو عدم تطابق المبلغ بالحساب البنكي.">
                                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-[11px] transition-colors">
                                                رفض
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $payments->links() }}
            </div>
        @endif
    </main>
</x-layouts.base>
