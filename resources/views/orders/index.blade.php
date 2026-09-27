<x-layouts.base title="سجل الطلبات والمدفوعات — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg">
                        B
                    </div>
                    <div>
                        <span class="text-base font-black tracking-wider text-white font-['Tajawal']">سجل المعاملات والطلبات</span>
                        <span class="block text-[10px] text-[#F3D98B] font-mono tracking-widest uppercase">الفواتير وحالة الدفع</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('student.dashboard') }}">
                    <x-button variant="outline-gold" size="sm">لوحتي التعليمية</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6">
        <h1 class="text-2xl font-bold text-[#071A36] font-['Tajawal']">طلبات الشراء السابقة والفواتير</h1>

        @if($orders->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <p class="text-xs text-slate-500">لا توجد طلبات شراء مسجلة بحسابك حتى الآن.</p>
                <div class="mt-4">
                    <a href="{{ route('courses.index') }}">
                        <x-button variant="gold" size="sm">استعراض الدورات</x-button>
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <table class="w-full text-right text-xs">
                    <thead class="bg-[#F5F7FA] text-slate-600 uppercase font-semibold border-b border-slate-200">
                        <tr>
                            <th class="p-4">رقم الطلب</th>
                            <th class="p-4">تاريخ الطلب</th>
                            <th class="p-4">الدورات المشمولة</th>
                            <th class="p-4">الإجمالي</th>
                            <th class="p-4">الحالة</th>
                            <th class="p-4 text-center">التفاصيل</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($orders as $order)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-4 font-mono font-bold text-[#071A36]">
                                    {{ $order->order_number }}
                                </td>
                                <td class="p-4 font-mono text-slate-500">
                                    {{ $order->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="p-4 text-slate-700">
                                    {{ $order->items->pluck('title_snapshot')->join(', ') }}
                                </td>
                                <td class="p-4 font-mono font-bold text-[#071A36]">
                                    {{ $order->total_amount }} {{ $order->currency }}
                                </td>
                                <td class="p-4">
                                    @if($order->status === 'COMPLETED')
                                        <span class="px-2.5 py-0.5 rounded font-bold text-[10px] bg-emerald-100 text-emerald-800">مكتمل ومفعل</span>
                                    @elseif($order->status === 'PROCESSING')
                                        <span class="px-2.5 py-0.5 rounded font-bold text-[10px] bg-amber-100 text-amber-800">قيد التدقيق البنكي</span>
                                    @elseif($order->status === 'FAILED')
                                        <span class="px-2.5 py-0.5 rounded font-bold text-[10px] bg-rose-100 text-rose-800">ملغي / مرفوض</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded font-bold text-[10px] bg-slate-100 text-slate-600">بانتظار السداد</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    <a href="{{ route('orders.show', $order->order_number) }}" class="text-xs font-bold text-[#123B68] hover:underline">
                                        عرض الفاتورة &larr;
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </main>
</x-layouts.base>
