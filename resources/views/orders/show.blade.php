<x-layouts.base title="فاتورة الطلب {{ $order->order_number }} — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('orders.index') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg">
                        B
                    </div>
                    <div>
                        <span class="text-base font-black tracking-wider text-white font-['Tajawal']">{{ $order->order_number }}</span>
                        <span class="block text-[10px] text-[#F3D98B] font-mono tracking-widest uppercase">تفاصيل الفاتورة الرسمية</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('orders.index') }}">
                    <x-button variant="outline-gold" size="sm">سجل الفواتير</x-button>
                </a>
                @if($order->status === 'COMPLETED')
                    <a href="{{ route('student.dashboard') }}">
                        <x-button variant="gold" size="sm">بدء دراسة الدورات &larr;</x-button>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6">
        @if(session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm space-y-8">
            <!-- Invoice Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-100">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#D4AF37]">فاتورة ضريبية رسمية</span>
                    <h1 class="text-2xl font-bold font-mono text-[#071A36] mt-1">{{ $order->order_number }}</h1>
                    <p class="text-xs text-slate-500 font-mono mt-0.5">تاريخ الإصدار: {{ $order->created_at->format('Y-m-d H:i:s') }}</p>
                </div>

                <div class="text-start sm:text-end">
                    @if($order->status === 'COMPLETED')
                        <span class="px-3 py-1 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                            ✓ مدفوعة ومفعلة بالكامل
                        </span>
                    @elseif($order->status === 'PROCESSING')
                        <span class="px-3 py-1 rounded-xl text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                            قيد التدقيق والمراجعة البنكية
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700">
                            {{ $order->status }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Customer & Platform Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs text-slate-600">
                <div class="space-y-1">
                    <span class="font-bold text-slate-900 block">بيانات العميل:</span>
                    <p>{{ $order->user->name }}</p>
                    <p class="font-mono">{{ $order->user->email }}</p>
                    <p>{{ $order->user->engineering_title ?: 'مهندس متدرب' }}</p>
                </div>

                <div class="space-y-1 sm:text-end">
                    <span class="font-bold text-[#071A36] block">الجهة التعليمية المعتمدة:</span>
                    <p>منصة بيفوربيم الرقمية لتعليم الـ BIM</p>
                    <p class="font-mono">info@beforbim.com</p>
                </div>
            </div>

            <!-- Items Table -->
            <table class="w-full text-right text-xs">
                <thead class="bg-[#F5F7FA] text-slate-600 uppercase font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3">الدورة التدريبية</th>
                        <th class="p-3">المدرب</th>
                        <th class="p-3 text-left">السعر</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="p-3 font-bold text-[#071A36]">
                                {{ $item->title_snapshot }}
                            </td>
                            <td class="p-3 text-slate-500">
                                {{ $item->course?->instructor?->name ?? 'طاقم بيفوربيم' }}
                            </td>
                            <td class="p-3 font-mono font-bold text-left text-slate-800">
                                {{ $item->total_price }} {{ $order->currency }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-slate-200 font-bold text-sm">
                    <tr>
                        <td colspan="2" class="p-3 text-slate-800">المجموع الكلي:</td>
                        <td class="p-3 text-left font-mono text-base text-[#D4AF37]">
                            {{ number_format($order->total_amount, 2) }} {{ $order->currency }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            <!-- Payments History on this Order -->
            <div class="pt-4 border-t border-slate-100 space-y-3">
                <h4 class="font-bold text-xs text-[#071A36]">سجل عمليات الدفع المسجلة:</h4>
                @foreach($order->payments as $payment)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs flex items-center justify-between">
                        <div>
                            <span class="font-bold text-[#071A36]">{{ $payment->payment_method }}</span>
                            <span class="text-slate-400 font-mono mr-2">{{ $payment->created_at->format('Y-m-d H:i') }}</span>
                            @if($payment->verification_notes)
                                <p class="text-[11px] text-slate-500 mt-1">{{ $payment->verification_notes }}</p>
                            @endif
                        </div>
                        <div class="text-left font-mono">
                            <span class="font-bold text-sm text-[#071A36]">{{ $payment->amount }} {{ $payment->currency }}</span>
                            <span class="block text-[10px] font-bold {{ $payment->status === 'COMPLETED' ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $payment->status }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
</x-layouts.base>
