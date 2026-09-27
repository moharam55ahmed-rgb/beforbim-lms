<x-layouts.base title="Checkout & Enrollment — Beforbim Academy">
    <!-- Header -->
    <header class="bg-white/95 dark:bg-[#071A36]/95 backdrop-blur-md text-slate-800 dark:text-white border-b border-slate-200 dark:border-white/10 sticky top-0 z-30 shadow-sm transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/branding/logo.png') }}" alt="Beforbim" class="w-8 h-8 object-contain">
                    <div>
                        <span class="text-base font-black tracking-wider text-slate-900 dark:text-white font-['Outfit']">BEFOR<span class="text-[#D4AF37]">BIM</span></span>
                        <span class="block text-[10px] text-[#B38F24] dark:text-[#F3D98B] font-mono tracking-widest uppercase">Secure Academic Checkout</span>
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

                <a href="{{ route('cart.index') }}">
                    <x-button variant="outline" size="sm" class="border-slate-300 dark:border-white/20 text-slate-700 dark:text-slate-200">
                        &larr; Back to Cart
                    </x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6" x-data="{ method: 'CARD' }">
        @if(session('error'))
            <x-alert type="danger">{{ session('error') }}</x-alert>
        @endif

        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white font-['Outfit']">Complete Order & Activate Enrollment</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Choose your preferred payment method to instantly unlock your engineering courses</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payment Form (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                <form method="POST" action="{{ route('checkout.process') }}" class="space-y-6">
                    @csrf

                    <!-- Payment Method Selection Tabs -->
                    <div class="bg-white dark:bg-[#071A36]/80 rounded-3xl border border-slate-200 dark:border-white/10 p-6 space-y-4 shadow-sm transition-colors">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white border-b border-slate-100 dark:border-white/10 pb-3 font-['Outfit']">Select Payment Method</h3>

                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer border-2 rounded-2xl p-4 flex flex-col items-center justify-center gap-1.5 transition-all text-center" :class="method === 'CARD' ? 'border-[#D4AF37] bg-[#D4AF37]/5 dark:bg-[#D4AF37]/10' : 'border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20'">
                                <input type="radio" name="payment_method" value="CARD" x-model="method" class="sr-only">
                                <span class="font-bold text-sm text-slate-900 dark:text-white font-['Outfit']">Credit / Debit Card</span>
                                <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold">Instant Automatic Activation</span>
                            </label>

                            <label class="cursor-pointer border-2 rounded-2xl p-4 flex flex-col items-center justify-center gap-1.5 transition-all text-center" :class="method === 'BANK_TRANSFER' ? 'border-[#D4AF37] bg-[#D4AF37]/5 dark:bg-[#D4AF37]/10' : 'border-slate-200 dark:border-white/10 hover:border-slate-300 dark:hover:border-white/20'">
                                <input type="radio" name="payment_method" value="BANK_TRANSFER" x-model="method" class="sr-only">
                                <span class="font-bold text-sm text-slate-900 dark:text-white font-['Outfit']">Direct Bank Wire</span>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400">Manual Wire Transfer Review</span>
                            </label>
                        </div>

                        <!-- Card Fields Mock -->
                        <div x-show="method === 'CARD'" class="space-y-4 pt-4 border-t border-slate-100 dark:border-white/10">
                            <div class="p-4 rounded-2xl bg-blue-50/60 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-500/20 text-xs text-blue-900 dark:text-blue-300 flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                                <span>Immediate sandbox activation enabled. Card will be processed securely with 256-bit encryption.</span>
                            </div>
                        </div>

                        <!-- Bank Transfer Fields (Cairo, Egypt Corporate Bank Account) -->
                        <div x-show="method === 'BANK_TRANSFER'" style="display: none;" class="space-y-4 pt-4 border-t border-slate-100 dark:border-white/10">
                            <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-500/30 text-xs text-amber-900 dark:text-amber-200 space-y-1.5">
                                <p class="font-bold">Beforbim Academy Corporate Bank Account (Cairo, Egypt):</p>
                                <p class="font-mono">Bank: Commercial International Bank (CIB) — New Cairo Branch</p>
                                <p class="font-mono">Account Name: Beforbim Engineering Education Ltd.</p>
                                <p class="font-mono">IBAN: EG45 0010 0045 0000 1234 5678 901</p>
                                <p class="font-mono">Swift Code: CIBEEGCX</p>
                            </div>

                            <x-input
                                name="receipt_url"
                                label="Wire Transfer Receipt URL or Reference"
                                placeholder="https://... or wire transfer transaction number"
                            />
                        </div>

                        <div class="space-y-1.5 text-start pt-2">
                            <label for="notes" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Order Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="2" class="w-full rounded-2xl border border-slate-300 dark:border-white/20 bg-white dark:bg-[#071A36]/80 text-slate-900 dark:text-white px-3 py-2 text-xs focus:ring-2 focus:ring-[#D4AF37] focus:outline-none" placeholder="Any special corporate billing or tax registration notes..."></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <x-button type="submit" variant="gold" class="px-8 py-3 text-sm font-bold shadow-lg shadow-[#D4AF37]/20">
                            Confirm Order & Pay ${{ number_format($total, 2) }} &rarr;
                        </x-button>
                    </div>
                </form>
            </div>

            <!-- Order Review (1 Col) -->
            <div class="bg-white dark:bg-[#071A36]/90 rounded-3xl border border-slate-200 dark:border-white/10 p-6 space-y-4 shadow-sm h-fit transition-colors">
                <h3 class="font-bold text-sm text-slate-900 dark:text-white border-b border-slate-100 dark:border-white/10 pb-3 font-['Outfit']">Courses in This Order</h3>

                <div class="divide-y divide-slate-100 dark:divide-white/10 text-xs">
                    @foreach($items as $item)
                        <div class="py-2.5 flex items-center justify-between">
                            <span class="font-semibold text-slate-800 dark:text-slate-200 line-clamp-1 max-w-[180px]">
                                {{ $item->course?->title_en ?: $item->course?->title_ar ?: $item->course?->title }}
                            </span>
                            <span class="font-mono font-bold text-slate-900 dark:text-white">${{ number_format($item->unit_price, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-slate-200 dark:border-white/10 flex items-center justify-between font-bold text-sm text-slate-900 dark:text-white">
                    <span>Total Amount:</span>
                    <span class="font-mono text-base text-[#B38F24] dark:text-[#D4AF37]">${{ number_format($total, 2) }}</span>
                </div>

                <div class="text-[11px] text-slate-400 text-center pt-2">
                    Official VAT invoice & receipt generated automatically upon settlement.
                </div>
            </div>
        </div>
    </main>
</x-layouts.base>
