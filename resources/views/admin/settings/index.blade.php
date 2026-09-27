<x-layouts.base title="إعدادات المنصة وإدارة المحتوى (CMS) — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg hover:opacity-95 transition">
                    B
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold font-['Tajawal'] text-white">إعدادات المنصة وإدارة المحتوى (CMS)</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#D4AF37] text-[#071A36]">
                            الإدارة العامة
                        </span>
                    </div>
                    <p class="text-xs text-slate-300">تخصيص الهوية، نصوص الموقع، السيو، بوابات الدفع والأمان</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.media.index') }}">
                    <x-button variant="gold" size="sm">مكتبة الوسائط</x-button>
                </a>
                <a href="{{ route('admin.dashboard') }}">
                    <x-button variant="outline-gold" size="sm">العودة للوحة التحكم</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-8" dir="rtl">
        @if (session('status'))
            <x-alert type="success" :message="session('status')" />
        @endif

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden" x-data="{ activeTab: 'general' }">
            <!-- Tabs Navigation -->
            <div class="flex items-center border-b border-slate-200 bg-slate-50/50 px-6 overflow-x-auto gap-4">
                <button type="button" @click="activeTab = 'general'" :class="activeTab === 'general' ? 'border-[#071A36] text-[#071A36] font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="py-4 px-2 border-b-2 text-sm transition flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>الإعدادات العامة</span>
                </button>
                <button type="button" @click="activeTab = 'branding'" :class="activeTab === 'branding' ? 'border-[#071A36] text-[#071A36] font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="py-4 px-2 border-b-2 text-sm transition flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    <span>واجهة الموقع (Hero & About)</span>
                </button>
                <button type="button" @click="activeTab = 'seo'" :class="activeTab === 'seo' ? 'border-[#071A36] text-[#071A36] font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="py-4 px-2 border-b-2 text-sm transition flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>تهيئة محركات البحث (SEO)</span>
                </button>
                <button type="button" @click="activeTab = 'payment'" :class="activeTab === 'payment' ? 'border-[#071A36] text-[#071A36] font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="py-4 px-2 border-b-2 text-sm transition flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span>بوابات الدفع والمالية</span>
                </button>
                <button type="button" @click="activeTab = 'security'" :class="activeTab === 'security' ? 'border-[#071A36] text-[#071A36] font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="py-4 px-2 border-b-2 text-sm transition flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>الأمان ومكافحة القرصنة</span>
                </button>
            </div>

            <!-- Form Content -->
            <form action="{{ route('admin.settings.update') }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- General Tab -->
                <div x-show="activeTab === 'general'" class="space-y-6">
                    <h3 class="text-base font-bold text-[#071A36] border-b border-slate-100 pb-3">المعلومات الأساسية وقنوات التواصل</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">اسم المنصة بالعربية</label>
                            <input type="text" name="site_name_ar" value="{{ $groupedSettings['general']['site_name_ar']['raw_value'] ?? 'Beforbim — الأكاديمية الهندسية ونمذجة البناء' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">اسم المنصة بالإنجليزية</label>
                            <input type="text" name="site_name_en" value="{{ $groupedSettings['general']['site_name_en']['raw_value'] ?? 'Beforbim — Engineering & BIM LMS' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">البريد الإلكتروني الرسمي للدعم</label>
                            <input type="email" name="contact_email" value="{{ $groupedSettings['general']['contact_email']['raw_value'] ?? 'support@beforbim.com' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">رقم الهاتف / الواتساب الرسمي</label>
                            <input type="text" name="contact_phone" value="{{ $groupedSettings['general']['contact_phone']['raw_value'] ?? '+966 50 000 0000' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">عنوان المقر الرئيسي / الفروع</label>
                            <input type="text" name="contact_address" value="{{ $groupedSettings['general']['contact_address']['raw_value'] ?? 'المملكة العربية السعودية — الرياض / جمهورية مصر العربية — القاهرة' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">العملة الافتراضية</label>
                            <input type="text" name="default_currency" value="{{ $groupedSettings['general']['default_currency']['raw_value'] ?? 'SAR' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">الشعار النصي (Tagline)</label>
                            <input type="text" name="site_tagline" value="{{ $groupedSettings['general']['site_tagline']['raw_value'] ?? 'الأكاديمية الهندسية ونمذجة البناء' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Branding Tab -->
                <div x-show="activeTab === 'branding'" class="space-y-6">
                    <h3 class="text-base font-bold text-[#071A36] border-b border-slate-100 pb-3">نصوص الواجهة الرئيسية وصفحة عن الأكاديمية</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">شارة الترويسة العليا (Hero Badge)</label>
                            <input type="text" name="hero_badge" value="{{ $groupedSettings['branding']['hero_badge']['raw_value'] ?? 'الاعتماد الأكاديمي الدولي وفق مواصفة ISO 19650' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">عنوان الترويسة الرئيسية (Hero Title)</label>
                            <input type="text" name="hero_title_ar" value="{{ $groupedSettings['branding']['hero_title_ar']['raw_value'] ?? 'المنصة الهندسية الأولى المعتمدة لمهندسي الـ BIM وإدارة المشروعات الرقمية' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">الوصف الفرعي (Hero Subtitle)</label>
                            <textarea name="hero_subtitle_ar" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">{{ $groupedSettings['branding']['hero_subtitle_ar']['raw_value'] ?? 'اكتسب مهارات متقدمة في Revit, Navisworks, Civil 3D, Dynamo مع نخبة من الخبراء والاستشاريين المعتمدين.' }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">رسالة الأكاديمية (Mission)</label>
                                <textarea name="about_mission" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">{{ $groupedSettings['branding']['about_mission']['raw_value'] ?? 'جسر الفجوة بين التعليم الهندسي الأكاديمي والواقع العملي في المشروعات الضخمة.' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">رؤية الأكاديمية (Vision)</label>
                                <textarea name="about_vision" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">{{ $groupedSettings['branding']['about_vision']['raw_value'] ?? 'أن نكون المرجع الهندسي الرقمي الأول في الشرق الأوسط وإفريقيا لاعتماد وتأهيل مديري ومنسقي BIM.' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Tab -->
                <div x-show="activeTab === 'seo'" class="space-y-6">
                    <h3 class="text-base font-bold text-[#071A36] border-b border-slate-100 pb-3">إعدادات محركات البحث ووسوم OpenGraph</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">عنوان الميتا الافتراضي (Default Meta Title)</label>
                            <input type="text" name="seo_meta_title" value="{{ $groupedSettings['branding']['seo_meta_title']['raw_value'] ?? 'Beforbim — أكاديمية نمذجة معلومات البناء وهندسة التشييد الرقمي' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">وصف الميتا الافتراضي (Meta Description)</label>
                            <textarea name="seo_meta_description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">{{ $groupedSettings['branding']['seo_meta_description']['raw_value'] ?? 'أكاديمية Beforbim الرائدة في برامج دبلومات BIM المعتمدة، هندسة التشييد الرقمي، وتطبيقات Revit, Navisworks, Civil 3D, و Dynamo مع نخبة من الاستشاريين الدوليين.' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">الكلمات الدلالية المفتاحية (Meta Keywords)</label>
                            <input type="text" name="seo_meta_keywords" value="{{ $groupedSettings['branding']['seo_meta_keywords']['raw_value'] ?? 'BIM, Revit, Navisworks, Civil 3D, Dynamo, نمذجة معلومات البناء, هندسة مدنية, كورسات هندسية' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Payment Tab -->
                <div x-show="activeTab === 'payment'" class="space-y-6">
                    <h3 class="text-base font-bold text-[#071A36] border-b border-slate-100 pb-3">بوابات الدفع الإلكتروني والتحويل البنكي</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">نسبة عمولة المنصة الافتراضية (%)</label>
                            <input type="number" step="0.5" name="platform_commission_rate" value="{{ $groupedSettings['payment']['platform_commission_rate']['raw_value'] ?? '20' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">ضريبة القيمة المضافة VAT (%)</label>
                            <input type="number" step="0.5" name="tax_rate_percentage" value="{{ $groupedSettings['payment']['tax_rate_percentage']['raw_value'] ?? '15' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">بيانات الحساب البنكي للتحويل اليدوي</label>
                            <textarea name="bank_transfer_instructions" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">{{ $groupedSettings['payment']['bank_transfer_instructions']['raw_value'] ?? 'مصرف الراجحي — رقم الآيبان: SA0380000000000000000000 — المستفيد: شركة بيفور بيم للتعليم والتدريب' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div x-show="activeTab === 'security'" class="space-y-6">
                    <h3 class="text-base font-bold text-[#071A36] border-b border-slate-100 pb-3">إعدادات الأمان ومكافحة القرصنة</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">الحد الأقصى للأجهزة المتزامنة لكل طالب</label>
                            <input type="number" name="max_device_sessions" value="{{ $groupedSettings['security']['max_device_sessions']['raw_value'] ?? '1' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">الحد الأقصى لمخالفات الاختبار قبل الإغلاق التلقائي</label>
                            <input type="number" name="max_proctoring_violations" value="{{ $groupedSettings['security']['max_proctoring_violations']['raw_value'] ?? '3' }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-200 flex items-center justify-end gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#071A36] text-white hover:bg-[#123B68] text-sm font-bold shadow-md shadow-[#071A36]/20 transition">
                        <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>حفظ التعديلات في النظام</span>
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-layouts.base>
