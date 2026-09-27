<x-layouts.base title="Invoice {{ $order->order_number }} — Beforbim Academy">
    <!-- Header -->
    <header class="bg-white/95 dark:bg-[#071A36]/95 backdrop-blur-md text-slate-800 dark:text-white border-b border-slate-200 dark:border-white/10 sticky top-0 z-30 shadow-sm transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('orders.index') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/branding/logo.png') }}" alt="Beforbim" class="w-8 h-8 object-contain">
                    <div>
                        <span class="text-base font-black tracking-wider text-slate-900 dark:text-white font-['Outfit']">{{ $order->order_number }}</span>
                        <span class="block text-[10px] text-[#B38F24] dark:text-[#F3D98B] font-mono tracking-widest uppercase">Official Tax Invoice</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('orders.index') }}">
                    <x-button variant="outline" size="sm" class="border-slate-300 dark:border-white/20 text-slate-700 dark:text-slate-200">Order History</x-button>
                </a>
                @if($order->status === 'COMPLETED')
                    <a href="{{ route('student.dashboard') }}">
                        <x-button variant="gold" size="sm">Start Learning &rarr;</x-button>
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
            <x-alert type="danger">{{ session('error') }}</x-alert>
        @endif

        <div class="bg-white dark:bg-[#071A36]/90 rounded-3xl border border-slate-200 dark:border-white/10 p-8 shadow-sm space-y-8 transition-colors">
            <!-- Invoice Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-100 dark:border-white/10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#B38F24] dark:text-[#D4AF37]">Official Academic Invoice</span>
                    <h1 class="text-2xl font-black font-mono text-slate-900 dark:text-white mt-1">{{ $order->order_number }}</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-mono mt-0.5">Issued: {{ $order->created_at->format('Y-m-d H:i:s') }}</p>
                </div>

                <div class="text-start sm:text-end">
                    @if($order->status === 'COMPLETED')
                        <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-500/30">
                            ✓ Paid & Fully Enrolled
                        </span>
                    @elseif($order->status === 'PROCESSING')
                        <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-500/30">
                            Under Bank Verification
                        </span>
                    @else
                        <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 dark:bg-white/10 text-slate-700 dark:text-slate-300">
                            {{ $order->status }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Customer & Platform Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs text-slate-600 dark:text-slate-300">
                <div class="space-y-1">
                    <span class="font-bold text-slate-900 dark:text-white block">Client Details:</span>
                    <p>{{ $order->user->name }}</p>
                    <p class="font-mono">{{ $order->user->email }}</p>
                    <p>{{ $order->user->engineering_title ?: 'Engineering Student' }}</p>
                </div>

                <div class="space-y-1 sm:text-end">
                    <span class="font-bold text-slate-900 dark:text-white block">Accredited Institution:</span>
                    <p>Beforbim BIM Engineering Academy</p>
                    <p class="font-mono">info@beforbim.com</p>
                    <p>New Cairo, Cairo, Egypt</p>
                </div>
            </div>

            <!-- Items Table -->
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-[#040E1E] text-slate-500 dark:text-slate-400 uppercase font-semibold border-b border-slate-200 dark:border-white/10">
                    <tr>
                        <th class="p-3">Course Program</th>
                        <th class="p-3">Instructor</th>
                        <th class="p-3 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="p-3 font-bold text-slate-900 dark:text-white">
                                {{ $item->title_snapshot }}
                            </td>
                            <td class="p-3 text-slate-500 dark:text-slate-400">
                                {{ $item->course?->instructor?->name ?? 'Beforbim Faculty' }}
                            </td>
                            <td class="p-3 font-mono font-bold text-right text-slate-800 dark:text-slate-200">
                                ${{ number_format($item->total_price, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-slate-200 dark:border-white/10 font-bold text-sm">
                    <tr>
                        <td colspan="2" class="p-3 text-slate-800 dark:text-white">Grand Total:</td>
                        <td class="p-3 text-right font-mono text-base text-[#B38F24] dark:text-[#D4AF37]">
                            ${{ number_format($order->total_amount, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            <!-- Payments History on this Order -->
            <div class="pt-4 border-t border-slate-100 dark:border-white/10 space-y-3">
                <h4 class="font-bold text-xs text-slate-900 dark:text-white font-['Outfit']">Payment Transaction Log:</h4>
                @foreach($order->payments as $payment)
                    <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/10 text-xs flex items-center justify-between">
                        <div>
                            <span class="font-bold text-slate-900 dark:text-white">{{ $payment->payment_method }}</span>
                            <span class="text-slate-400 font-mono ml-2">{{ $payment->created_at->format('Y-m-d H:i') }}</span>
                            @if($payment->verification_notes)
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">{{ $payment->verification_notes }}</p>
                            @endif
                        </div>
                        <div class="text-right font-mono">
                            <span class="font-bold text-sm text-slate-900 dark:text-white">${{ number_format($payment->amount, 2) }}</span>
                            <span class="block text-[10px] font-bold {{ $payment->status === 'COMPLETED' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                {{ $payment->status }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
</x-layouts.base>
