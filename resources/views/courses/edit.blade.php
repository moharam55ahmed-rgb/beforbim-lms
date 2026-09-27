<x-layouts.base title="تعديل بيانات الدورة — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('instructor.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg">
                        B
                    </div>
                    <div>
                        <span class="text-base font-black tracking-wider text-white font-['Tajawal']">{{ $course->title_ar }}</span>
                        <span class="block text-[10px] text-[#F3D98B] font-mono tracking-widest uppercase">تعديل بيانات المنهج</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('courses.curriculum', $course->id) }}">
                    <x-button variant="gold" size="sm">بناء المنهج والأقسام &larr;</x-button>
                </a>
                <a href="{{ route('courses.index') }}">
                    <x-button variant="outline-gold" size="sm">العودة للدورات</x-button>
                </a>
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

        <!-- Status Card -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-400 font-semibold block">حالة الدورة الحالية:</span>
                <div class="flex items-center gap-2 mt-1">
                    @if($course->status === 'APPROVED')
                        <span class="px-3 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-800">معتمدة ومنشورة للجمهور</span>
                    @elseif($course->status === 'SUBMITTED')
                        <span class="px-3 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-800">قيد التدقيق والمراجعة الأكاديمية</span>
                    @elseif($course->status === 'REJECTED')
                        <span class="px-3 py-1 rounded-lg text-xs font-bold bg-rose-100 text-rose-800">مرفوضة / تتطلب تعديلات</span>
                    @else
                        <span class="px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">مسودة غير منشورة</span>
                    @endif
                </div>
            </div>

            @if($course->status === 'DRAFT' || $course->status === 'REJECTED')
                <form method="POST" action="{{ route('courses.submit', $course->id) }}">
                    @csrf
                    <x-button type="submit" variant="gold">
                        إرسال للاعتماد الأكاديمي &uarr;
                    </x-button>
                </form>
            @endif
        </div>

        @if($course->status === 'REJECTED' && $course->rejection_feedback)
            <div class="p-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider block text-rose-700">ملاحظات الإدارة الهندسية للتعديل:</span>
                <p class="text-sm leading-relaxed">{{ $course->rejection_feedback }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('courses.update', $course->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <x-card class="space-y-5">
                <h3 class="text-sm font-bold text-[#071A36] border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#D4AF37]"></span>
                    البيانات الهندسية الأساسية
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input
                        name="title_ar"
                        label="عنوان الدورة (باللغة العربية)"
                        :value="old('title_ar', $course->title_ar)"
                        required
                    />

                    <x-input
                        name="title_en"
                        label="العنوان باللغة الإنجليزية"
                        :value="old('title_en', $course->title_en)"
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5 text-start">
                        <label for="category_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                            التصنيف الهندسي <span class="text-[#D4AF37]">*</span>
                        </label>
                        <select name="category_id" id="category_id" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm transition-all focus:border-[#D4AF37] focus:ring-2 focus:ring-[#D4AF37]/20 text-[#071A36] bg-white">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5 text-start">
                        <label for="level" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                            المستوى الفني <span class="text-[#D4AF37]">*</span>
                        </label>
                        <select name="level" id="level" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm transition-all focus:border-[#D4AF37] focus:ring-2 focus:ring-[#D4AF37]/20 text-[#071A36] bg-white">
                            <option value="ALL_LEVELS" {{ old('level', $course->level) == 'ALL_LEVELS' ? 'selected' : '' }}>جميع المستويات</option>
                            <option value="BEGINNER" {{ old('level', $course->level) == 'BEGINNER' ? 'selected' : '' }}>مبتدئ</option>
                            <option value="INTERMEDIATE" {{ old('level', $course->level) == 'INTERMEDIATE' ? 'selected' : '' }}>متوسط</option>
                            <option value="ADVANCED" {{ old('level', $course->level) == 'ADVANCED' ? 'selected' : '' }}>متقدم</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1.5 text-start">
                    <label for="short_description_ar" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                        الوصف المختصر للدورة
                    </label>
                    <textarea name="short_description_ar" id="short_description_ar" rows="2" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-[#D4AF37] focus:ring-2 focus:ring-[#D4AF37]/20 text-[#071A36]">{{ old('short_description_ar', $course->short_description_ar) }}</textarea>
                </div>

                <div class="space-y-1.5 text-start">
                    <label for="description_ar" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                        الوصف التفصيلي وأهداف المنهج
                    </label>
                    <textarea name="description_ar" id="description_ar" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-[#D4AF37] focus:ring-2 focus:ring-[#D4AF37]/20 text-[#071A36]">{{ old('description_ar', $course->description_ar) }}</textarea>
                </div>
            </x-card>

            <!-- Pricing & Media -->
            <x-card class="space-y-5">
                <h3 class="text-sm font-bold text-[#071A36] border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#123B68]"></span>
                    التسعير والوسائط الترويجية
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-input
                        name="price"
                        type="number"
                        step="0.01"
                        label="السعر الأصلي"
                        :value="old('price', $course->price)"
                        required
                    />

                    <x-input
                        name="sale_price"
                        type="number"
                        step="0.01"
                        label="سعر العرض الترويجي"
                        :value="old('sale_price', $course->sale_price)"
                    />

                    <div class="space-y-1.5 text-start">
                        <label for="currency" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                            العملة
                        </label>
                        <select name="currency" id="currency" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm transition-all focus:border-[#D4AF37] focus:ring-2 focus:ring-[#D4AF37]/20 text-[#071A36] bg-white">
                            <option value="USD" {{ old('currency', $course->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="SAR" {{ old('currency', $course->currency) == 'SAR' ? 'selected' : '' }}>SAR (ر.س)</option>
                            <option value="EGP" {{ old('currency', $course->currency) == 'EGP' ? 'selected' : '' }}>EGP (ج.م)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input
                        name="thumbnail_url"
                        label="رابط صورة الغلاف"
                        :value="old('thumbnail_url', $course->thumbnail_url)"
                    />

                    <x-input
                        name="promo_video_url"
                        label="رابط الفيديو التعريفي"
                        :value="old('promo_video_url', $course->promo_video_url)"
                    />
                </div>
            </x-card>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('courses.curriculum', $course->id) }}">
                    <x-button type="button" variant="outline-gold">الانتقال لبناء المنهج</x-button>
                </a>
                <x-button type="submit" variant="gold" class="px-8 shadow-lg shadow-[#D4AF37]/20">
                    حفظ التغييرات
                </x-button>
            </div>
        </form>
    </main>
</x-layouts.base>
