<x-layouts.base title="Your Cart — Beforbim Academy">
    <!-- Header -->
    <header class="bg-white/95 dark:bg-[#071A36]/95 backdrop-blur-md text-slate-800 dark:text-white border-b border-slate-200 dark:border-white/10 sticky top-0 z-30 shadow-sm transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/branding/logo.png') }}" alt="Beforbim" class="w-8 h-8 object-contain">
                    <div>
                        <span class="text-base font-black tracking-wider text-slate-900 dark:text-white font-['Outfit']">BEFOR<span class="text-[#D4AF37]">BIM</span></span>
                        <span class="block text-[10px] text-[#B38F24] dark:text-[#F3D98B] font-mono tracking-widest uppercase">Shopping Cart & Registration</span>
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

                <a href="{{ route('courses.index') }}">
                    <x-button variant="outline" size="sm" class="border-slate-300 dark:border-white/20 text-slate-700 dark:text-slate-200">
                        Continue Browsing Courses
                    </x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6">
        @if(session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert type="danger">{{ session('error') }}</x-alert>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white font-['Outfit']">Selected Programs Cart</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Review your selected BIM engineering masterclasses and proceed to secure checkout</p>
            </div>
            <span class="text-xs font-mono font-bold text-[#B38F24] dark:text-[#F3D98B] bg-slate-100 dark:bg-white/5 px-3 py-1 rounded-xl border border-slate-200 dark:border-white/10">
                {{ $items_count }} {{ Str::plural('Course', $items_count) }}
            </span>
        </div>

        @if($items_count === 0)
            <div class="bg-white dark:bg-[#071A36]/60 rounded-3xl border border-slate-200 dark:border-white/10 p-12 text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-white/5 text-[#D4AF37] flex items-center justify-center mx-auto mb-4 border border-slate-200 dark:border-white/10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white font-['Outfit']">Your Cart is Currently Empty</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto mt-1">
                    Explore our accredited ISO 19650 diplomas in Revit, Navisworks 4D, and Dynamo computational design.
                </p>
                <div class="mt-5">
                    <a href="{{ route('courses.index') }}">
                        <x-button variant="gold">Explore Course Directory</x-button>
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Items list (2 Cols) -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($items as $item)
                        <div class="bg-white dark:bg-[#071A36]/80 rounded-3xl border border-slate-200 dark:border-white/10 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-colors">
                            <div class="space-y-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-white/10 text-slate-700 dark:text-slate-200 px-2.5 py-0.5 rounded-full border border-slate-200 dark:border-white/10">
                                    {{ $item->course?->category?->name_en ?: ($item->course?->category?->name_ar ?: 'BIM') }}
                                </span>
                                <h3 class="font-bold text-base text-slate-900 dark:text-white font-['Outfit']">
                                    {{ $item->course?->title_en ?: $item->course?->title_ar ?: $item->course?->title }}
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    Instructor: {{ $item->course?->instructor?->name ?? 'Beforbim Faculty' }} • Level: {{ $item->course?->level ?? 'All Levels' }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-6 pt-3 sm:pt-0 border-t sm:border-0 border-slate-100 dark:border-white/10">
                                <span class="font-mono font-bold text-xl text-slate-900 dark:text-white">
                                    ${{ number_format($item->unit_price, 2) }}
                                </span>
                                <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 text-slate-400 hover:text-rose-500 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors" title="Remove Course">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Summary Box (1 Col) -->
                <div class="bg-white dark:bg-[#071A36]/90 rounded-3xl border border-slate-200 dark:border-white/10 p-6 space-y-5 shadow-sm h-fit">
                    <h3 class="font-bold text-base text-slate-900 dark:text-white border-b border-slate-100 dark:border-white/10 pb-3 flex items-center justify-between font-['Outfit']">
                        <span>Order Summary</span>
                        <span class="text-xs font-mono text-slate-400">({{ $items_count }} {{ Str::plural('Item', $items_count) }})</span>
                    </h3>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between text-slate-600 dark:text-slate-300">
                            <span>Subtotal:</span>
                            <span class="font-mono font-bold">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between text-emerald-600 dark:text-emerald-400 font-bold">
                                <span>Academic Discount:</span>
                                <span class="font-mono">-${{ number_format($discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="pt-3 border-t border-slate-100 dark:border-white/10 flex justify-between text-base font-black text-slate-900 dark:text-white">
                            <span>Total Due:</span>
                            <span class="font-mono text-[#B38F24] dark:text-[#D4AF37]">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.show') }}" class="block pt-2">
                        <x-button variant="gold" class="w-full justify-center shadow-lg shadow-[#D4AF37]/20 py-3 text-sm">
                            Proceed to Checkout &rarr;
                        </x-button>
                    </a>

                    <div class="text-[11px] text-slate-400 text-center space-y-1 pt-2">
                        <p>🔒 256-bit SSL Encrypted Transaction</p>
                        <p>Instant access to courseware upon confirmation</p>
                    </div>
                </div>
            </div>
        @endif
    </main>
</x-layouts.base>
