<x-layouts.base :title="'التحقق من الشهادات والاعتمادات — Beforbim'">
    <div class="min-h-screen bg-[#F5F7FA] py-16 px-4">
        <div class="max-w-xl mx-auto space-y-8">
            <!-- Header -->
            <div class="text-center space-y-2">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#071A36] to-[#123B68] text-[#D4AF37] flex items-center justify-center mx-auto shadow-lg shadow-[#071A36]/10 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-black text-[#071A36]">بوابة التحقق من الشهادات والاعتمادات</h1>
                <p class="text-xs md:text-sm text-slate-500">التحقق الرسمي من مصداقية شهادات إتمام دورات نمذجة معلومات البناء BIM الصادرة عن Beforbim</p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('certificate.verify') }}" method="GET" class="bg-white rounded-2xl p-3 shadow-sm border border-slate-200 flex gap-2">
                <input type="text" name="code" value="{{ $searchCode }}" required placeholder="أدخل كود التحقق أو رقم الشهادة (مثال: BFB-CERT-2026-...)" class="flex-1 px-4 py-2 text-xs md:text-sm text-slate-800 focus:outline-none">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#D4AF37] to-[#F3D98B] text-[#071A36] font-bold text-xs rounded-xl hover:opacity-95 transition">
                    تحقق الآن
                </button>
            </form>

            <!-- Verification Card -->
            @if ($certificate)
                <div class="bg-white rounded-3xl p-8 shadow-xl border-2 {{ $certificate->isActive() ? 'border-emerald-500' : 'border-rose-500' }} relative overflow-hidden">
                    <!-- Status Badge -->
                    <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full {{ $certificate->isActive() ? 'bg-emerald-500' : 'bg-rose-500' }} animate-ping"></span>
                            <span class="text-xs font-bold {{ $certificate->isActive() ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $certificate->isActive() ? 'شهادة معتمدة وسارية وموثقة' : 'شهادة ملغاة أو مسحوبة' }}
                            </span>
                        </div>
                        <span class="text-xs text-slate-400 font-mono">{{ $certificate->certificate_number }}</span>
                    </div>

                    <!-- Certificate Metadata -->
                    <div class="space-y-4 text-center">
                        <span class="text-xs text-slate-400 uppercase tracking-widest block">تشهد منصة Beforbim بأن المهندس/ة:</span>
                        <h2 class="text-2xl font-black text-[#071A36]">{{ $certificate->student_name_snapshot }}</h2>
                        <span class="text-xs text-slate-400 block">قد أتم/ت بنجاح متطلبات الدورة الهندسية التخصصية:</span>
                        <h3 class="text-lg font-bold text-[#123B68]">{{ $certificate->course_title_snapshot_ar }}</h3>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-4 mt-8 pt-6 border-t border-slate-100 text-xs">
                        <div class="bg-slate-50 p-3 rounded-xl text-center">
                            <span class="text-slate-400 block mb-0.5">تاريخ الإصدار</span>
                            <strong class="text-slate-700">{{ $certificate->issued_at ? $certificate->issued_at->format('Y-m-d') : '2026' }}</strong>
                        </div>
                        <div class="bg-slate-50 p-3 rounded-xl text-center">
                            <span class="text-slate-400 block mb-0.5">المدرب المعتمد</span>
                            <strong class="text-slate-700">{{ $certificate->instructor_name_snapshot }}</strong>
                        </div>
                    </div>

                    @if ($certificate->isActive())
                        <div class="mt-6 pt-4 border-t border-slate-100 flex justify-center">
                            <a href="{{ route('certificate.public.download', $certificate->verification_code) }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#071A36] text-[#D4AF37] hover:bg-[#123B68] text-xs font-bold transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>تحميل الشهادة الرقمية المعتمدة (PDF)</span>
                            </a>
                        </div>
                    @endif

                    @if ($certificate->is_revoked)
                        <div class="mt-4 p-3 bg-rose-50 rounded-xl text-xs text-rose-700 text-center">
                            <strong>سبب الإلغاء:</strong> {{ $certificate->revocation_reason }}
                        </div>
                    @endif
                </div>
            @elseif ($searchCode)
                <div class="bg-white rounded-3xl p-8 text-center border border-slate-200 shadow-sm space-y-2">
                    <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-[#071A36]">لم يتم العثور على شهادة بهذا الكود</h3>
                    <p class="text-xs text-slate-500">يرجى التأكد من كتابة كود التحقق بشكل صحيح أو التواصل مع الدعم الفني.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.base>
