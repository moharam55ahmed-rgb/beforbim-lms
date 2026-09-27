<x-layouts.base title="Orders & Invoices — Beforbim Academy">
    <!-- Header -->
    <header class="bg-white/95 dark:bg-[#071A36]/95 backdrop-blur-md text-slate-800 dark:text-white border-b border-slate-200 dark:border-white/10 sticky top-0 z-30 shadow-sm transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/branding/logo.png') }}" alt="Beforbim" class="w-8 h-8 object-contain">
                    <div>
                        <span class="text-base font-black tracking-wider text-slate-900 dark:text-white font-['Outfit']">BEFOR<span class="text-[#D4AF37]">BIM</span></span>
                        <span class="block text-[10px] text-[#B38F24] dark:text-[#F3D98B] font-mono tracking-widest uppercase">Billing & Order History</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <!-- Theme Toggle Button -->
                <button 
                    type="button" 
                    @click="toggleTheme()" 
                    class="p-2 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-slate-200 transition"
                    title="Toggle Theme"
                    aria-label="Toggle Theme"
                >
                    <template x-if="theme === 'dark'">
                        <svg class="w-4 h-4 text-[#F3D98B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </template>
                    <template x-if="theme === 'light'">
                        <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                    </template>
                </button>

                <a href="{{ route('student.dashboard') }}">
                    <x-button variant="outline" size="sm" class="border-slate-300 dark:border-white/20 text-slate-700 dark:text-slate-200">
                        My Studio Dashboard
                    </x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white font-['Outfit']">Order History & Invoices</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Track your past transactions, tax invoices, and enrollment settlements</p>
        </div>

        @if($orders->isEmpty())
            <div class="bg-white dark:bg-[#071A36]/60 rounded-3xl border border-slate-200 dark:border-white/10 p-12 text-center shadow-sm">
                <p class="text-xs text-slate-500 dark:text-slate-400">No orders recorded on this account yet.</p>
                <div class="mt-4">
                    <a href="{{ route('courses.index') }}">
                        <x-button variant="gold" size="sm">Browse BIM Courses</x-button>
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-[#071A36]/80 rounded-3xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden transition-colors">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-[#040E1E] text-slate-500 dark:text-slate-400 uppercase font-semibold border-b border-slate-200 dark:border-white/10">
                        <tr>
                            <th class="p-4">Order #</th>
                            <th class="p-4">Date</th>
                            <th class="p-4">Courses Included</th>
                            <th class="p-4">Amount</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center">Invoice</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @foreach($orders as $order)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                <td class="p-4 font-mono font-bold text-slate-900 dark:text-white">
                                    {{ $order->order_number }}
                                </td>
                                <td class="p-4 font-mono text-slate-500 dark:text-slate-400">
                                    {{ $order->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="p-4 text-slate-700 dark:text-slate-300">
                                    {{ $order->items->pluck('title_snapshot')->join(', ') }}
                                </td>
                                <td class="p-4 font-mono font-bold text-slate-900 dark:text-white">
                                    ${{ number_format($order->total_amount, 2) }}
                                </td>
                                <td class="p-4">
                                    @if($order->status === 'COMPLETED')
                                        <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-500/30">Completed & Enrolled</span>
                                    @elseif($order->status === 'PROCESSING')
                                        <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-500/30">Bank Review</span>
                                    @elseif($order->status === 'FAILED')
                                        <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-rose-100 dark:bg-rose-500/20 text-rose-800 dark:text-rose-400 border border-rose-300 dark:border-rose-500/30">Failed</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-slate-300">Pending</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    <a href="{{ route('orders.show', $order->order_number) }}" class="text-xs font-bold text-[#B38F24] dark:text-[#F3D98B] hover:underline">
                                        View Invoice &rarr;
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
