<x-layouts.base title="إنشاء دورة تدريبية جديدة — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('instructor.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg">
                        B
                    </div>
                    <div>
                        <span class="text-base font-black tracking-wider text-white font-['Tajawal']">استوديو المدرب</span>
                        <span class="block text-[10px] text-[#F3D98B] font-mono tracking-widest uppercase">إعداد وتصميم المناهج</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('instructor.dashboard') }}">
                    <x-button variant="outline-gold" size="sm">&rarr; العودة للوحة التحكم</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-[#071A36] font-['Tajawal']">إنشاء دورة تدريبية جديدة</h1>
            <p class="text-xs text-slate-500 mt-1">أدخل البيانات الأساسية للدورة ومستوى التدريب قبل الانتقال لبناء المنهج</p>
        </div>

        @if($errors->any())
            <x-alert type="error">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <form method="POST" action="{{ route('courses.store') }}" class="space-y-6">
            @csrf

            <x-card class="space-y-5">
                <h3 class="text-sm font-bold text-[#071A36] border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#D4AF37]"></span>
                    البيانات الهندسية الأساسية
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input
                        name="title_ar"
                        label="عنوان الدورة (باللغة العربية)"
                        placeholder="مثال: الدبلومة المتكاملة في نمذجة Revit و Navisworks"
                        :value="old('title_ar')"
                        required
                    />

                    <x-input
                        name="title_en"
                        label="العنوان باللغة الإنجليزية (اختياري)"
                        placeholder="e.g. Master BIM Coordination & Modeling"
                        :value="old('title_en')"
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5 text-start">
                        <label for="category_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                            التصنيف الهندسي <span class="text-[#D4AF37]">*</span>
                        </label>
                        <select name="category_id" id="category_id" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm transition-all focus:border-[#D4AF37] focus:ring-2 focus:ring-[#D4AF37]/20 text-[#071A36] bg-white">
                            <option value="">اختر التصنيف</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                            <option value="ALL_LEVELS" {{ old('level') == 'ALL_LEVELS' ? 'selected' : '' }}>جميع المستويات (شامل)</option>
                            <option value="BEGINNER" {{ old('level') == 'BEGINNER' ? 'selected' : '' }}>مبتدئ (أساسيات BIM)</option>
                            <option value="INTERMEDIATE" {{ old('level') == 'INTERMEDIATE' ? 'selected' : '' }}>متوسط (تطبيقات عملية)</option>
                            <option value="ADVANCED" {{ old('level') == 'ADVANCED' ? 'selected' : '' }}>متقدم (تنسيق وإدارة مشروعات)</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1.5 text-start">
                    <label for="short_description_ar" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                        الوصف المختصر للدورة
                    </label>
                    <textarea name="short_description_ar" id="short_description_ar" rows="2" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-[#D4AF37] focus:ring-2 focus:ring-[#D4AF37]/20 text-[#071A36]" placeholder="موجز تعليمي يظهر في بطاقات الدورات وقوائم التصفح...">{{ old('short_description_ar') }}</textarea>
                </div>

                <div class="space-y-1.5 text-start">
                    <label for="description_ar" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                        الوصف التفصيلي وأهداف المنهج
                    </label>
                    <textarea name="description_ar" id="description_ar" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm focus:border-[#D4AF37] focus:ring-2 focus:ring-[#D4AF37]/20 text-[#071A36]" placeholder="تفاصيل المنهج، المخرجات الهندسية المستهدفة، والمشروع العملي الذي سينفذه المتدرب...">{{ old('description_ar') }}</textarea>
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
                        placeholder="199.00"
                        :value="old('price', '0')"
                        required
                    />

                    <x-input
                        name="sale_price"
                        type="number"
                        step="0.01"
                        label="سعر العرض الترويجي (اختياري)"
                        placeholder="149.00"
                        :value="old('sale_price')"
                    />

                    <div class="space-y-1.5 text-start">
                        <label for="currency" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                            العملة
                        </label>
                        <select name="currency" id="currency" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm transition-all focus:border-[#D4AF37] focus:ring-2 focus:ring-[#D4AF37]/20 text-[#071A36] bg-white">
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="SAR" {{ old('currency') == 'SAR' ? 'selected' : '' }}>SAR (ر.س)</option>
                            <option value="EGP" {{ old('currency') == 'EGP' ? 'selected' : '' }}>EGP (ج.م)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input
                        name="thumbnail_url"
                        label="رابط صورة الغلاف (Thumbnail)"
                        placeholder="https://... أو مسار الصورة"
                        :value="old('thumbnail_url')"
                    />

                    <x-input
                        name="promo_video_url"
                        label="رابط الفيديو التعريفي (Promo Video)"
                        placeholder="https://vimeo.com/... أو يوتيوب"
                        :value="old('promo_video_url')"
                    />
                </div>
            </x-card>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('instructor.dashboard') }}">
                    <x-button type="button" variant="outline">إلغاء</x-button>
                </a>
                <x-button type="submit" variant="gold" class="px-8 shadow-lg shadow-[#D4AF37]/20">
                    حفظ ومتابعة لبناء المنهج &larr;
                </x-button>
            </div>
        </form>
    </main>
</x-layouts.base>
