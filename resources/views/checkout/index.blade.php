<x-layouts.base title="إتمام الشراء والدفع — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('cart.index') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg">
                        B
                    </div>
                    <div>
                        <span class="text-base font-black tracking-wider text-white font-['Tajawal']">بوابة السداد الهندسي</span>
                        <span class="block text-[10px] text-[#F3D98B] font-mono tracking-widest uppercase">سداد آمن ومشفر</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('cart.index') }}">
                    <x-button variant="outline-gold" size="sm">&rarr; العودة للسلة</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6" x-data="{ method: 'CARD' }">
        @if(session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif

        <h1 class="text-2xl font-bold text-[#071A36] font-['Tajawal']">إتمام الطلب وتفعيل الاشتراكات</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payment Form (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                <form method="POST" action="{{ route('checkout.process') }}" class="space-y-6">
                    @csrf

                    <!-- Payment Method Selection Tabs -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-sm">
                        <h3 class="text-sm font-bold text-[#071A36] border-b border-slate-100 pb-2">اختر وسيلة الدفع المفضلة</h3>

                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer border-2 rounded-xl p-4 flex flex-col items-center justify-center gap-2 transition-all" :class="method === 'CARD' ? 'border-[#D4AF37] bg-[#D4AF37]/5' : 'border-slate-200 hover:border-slate-300'">
                                <input type="radio" name="payment_method" value="CARD" x-model="method" class="sr-only">
                                <span class="font-bold text-sm text-[#071A36]">بطاقة بنكية / فيزا / مدى</span>
                                <span class="text-[10px] text-slate-500">تفعيل فوري للاشتراك</span>
                            </label>

                            <label class="cursor-pointer border-2 rounded-xl p-4 flex flex-col items-center justify-center gap-2 transition-all" :class="method === 'BANK_TRANSFER' ? 'border-[#D4AF37] bg-[#D4AF37]/5' : 'border-slate-200 hover:border-slate-300'">
                                <input type="radio" name="payment_method" value="BANK_TRANSFER" x-model="method" class="sr-only">
                                <span class="font-bold text-sm text-[#071A36]">تحويل بنكي مباشر</span>
                                <span class="text-[10px] text-slate-500">يتطلب مراجعة إيصال التحويل</span>
                            </label>
                        </div>

                        <!-- Card Fields Mock -->
                        <div x-show="method === 'CARD'" class="space-y-4 pt-4 border-t border-slate-100">
                            <div class="p-4 rounded-xl bg-blue-50/60 border border-blue-100 text-xs text-[#123B68] flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>سيتم محاكاة خصم المبلغ واعتماد الاشتراك فوراً في بيئة التطوير.</span>
                            </div>
                        </div>

                        <!-- Bank Transfer Fields -->
                        <div x-show="method === 'BANK_TRANSFER'" style="display: none;" class="space-y-4 pt-4 border-t border-slate-100">
                            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-1">
                                <p class="font-bold">بيانات الحساب البنكي لمنصة بيفوربيم:</p>
                                <p>بنك الراجحي: SA00 0000 0000 0000 0000 0000</p>
                                <p>الاسم: شركة بيفوربيم للتعليم الهندسي</p>
                            </div>

                            <x-input
                                name="receipt_url"
                                label="رابط صورة إيصال التحويل البنكي"
                                placeholder="https://... أو مسار الملف المرفوع"
                            />
                        </div>

                        <div class="space-y-1.5 text-start pt-2">
                            <label for="notes" class="block text-xs font-semibold text-slate-700">ملاحظات إضافية على الطلب</label>
                            <textarea name="notes" id="notes" rows="2" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs" placeholder="أية تفاصيل إضافية تريد تزويد الإدارة بها..."></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <x-button type="submit" variant="gold" class="px-8 py-3 text-base shadow-lg shadow-[#D4AF37]/20">
                            تأكيد الطلب ودفع {{ number_format($total, 2) }} {{ $currency }} &larr;
                        </x-button>
                    </div>
                </form>
            </div>

            <!-- Order Review (1 Col) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-sm h-fit">
                <h3 class="font-bold text-sm text-[#071A36] border-b border-slate-100 pb-2">الدورات في هذا الطلب</h3>

                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($items as $item)
                        <div class="py-2.5 flex items-center justify-between">
                            <span class="font-semibold text-slate-800 line-clamp-1 max-w-[180px]">{{ $item->course?->title_ar }}</span>
                            <span class="font-mono font-bold text-[#071A36]">{{ $item->unit_price }} {{ $currency }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-between font-bold text-sm text-[#071A36]">
                    <span>المبلغ الإجمالي:</span>
                    <span class="font-mono text-base text-[#D4AF37]">{{ number_format($total, 2) }} {{ $currency }}</span>
                </div>
            </div>
        </div>
    </main>
</x-layouts.base>
