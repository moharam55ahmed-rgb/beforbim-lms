<x-layouts.base title="سلة المشتريات — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('courses.index') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg">
                        B
                    </div>
                    <div>
                        <span class="text-base font-black tracking-wider text-white font-['Tajawal']">سلة المشتريات</span>
                        <span class="block text-[10px] text-[#F3D98B] font-mono tracking-widest uppercase">التسجيل بالبرامج الهندسية</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('courses.index') }}">
                    <x-button variant="outline-gold" size="sm">مواصلة تصفح الدورات</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6">
        @if(session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif

        <h1 class="text-2xl font-bold text-[#071A36] font-['Tajawal']">سلة الدورات والبرامج المختارة</h1>

        @if($items_count === 0)
            <div class="bg-white rounded-2xl border-2 border-dashed border-slate-300 p-12 text-center">
                <div class="w-14 h-14 rounded-2xl bg-[#071A36]/5 text-[#D4AF37] flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-[#071A36]">سلة المشتريات فارغة</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">تصفح مسارات BIM ودبلومات النمذجة الهندسية وأضف البرامج التي تناسب تخصصك.</p>
                <div class="mt-4">
                    <a href="{{ route('courses.index') }}">
                        <x-button variant="gold">استعراض دليل الدورات</x-button>
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Items list (2 Cols) -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($items as $item)
                        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold bg-[#071A36]/10 text-[#071A36] px-2 py-0.5 rounded">
                                    {{ $item->course?->category?->name_ar ?? 'دورة BIM' }}
                                </span>
                                <h3 class="font-bold text-base text-[#071A36] font-['Tajawal']">
                                    {{ $item->course?->title_ar }}
                                </h3>
                                <p class="text-xs text-slate-500">
                                    المدرب: {{ $item->course?->instructor?->name }} • المستوى: {{ $item->course?->level }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-6 pt-2 sm:pt-0 border-t sm:border-0 border-slate-100">
                                <span class="font-mono font-bold text-lg text-[#071A36]">
                                    {{ $item->unit_price }} {{ $currency }}
                                </span>
                                <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Summary Box (1 Col) -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-5 shadow-sm h-fit">
                    <h3 class="font-bold text-base text-[#071A36] border-b border-slate-100 pb-3 flex items-center justify-between">
                        <span>ملخص الحساب</span>
                        <span class="text-xs font-mono text-slate-400">({{ $items_count }} دورات)</span>
                    </h3>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>المجموع الفرعي:</span>
                            <span class="font-mono font-bold">{{ number_format($subtotal, 2) }} {{ $currency }}</span>
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between text-emerald-600 font-bold">
                                <span>خصم ترويجي:</span>
                                <span class="font-mono">-{{ number_format($discount, 2) }} {{ $currency }}</span>
                            </div>
                        @endif
                        <div class="pt-3 border-t border-slate-100 flex justify-between text-base font-black text-[#071A36]">
                            <span>الإجمالي المستحق:</span>
                            <span class="font-mono text-[#D4AF37]">{{ number_format($total, 2) }} {{ $currency }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.show') }}" class="block">
                        <x-button variant="gold" class="w-full justify-center shadow-lg shadow-[#D4AF37]/20 py-3">
                            متابعة الدفع وتأكيد التسجيل &larr;
                        </x-button>
                    </a>
                </div>
            </div>
        @endif
    </main>
</x-layouts.base>
