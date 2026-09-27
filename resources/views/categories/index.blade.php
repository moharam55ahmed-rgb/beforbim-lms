<x-layouts.base title="إدارة التصنيفات الهندسية — Beforbim">
    <!-- Admin Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg">
                        B
                    </div>
                    <div>
                        <span class="text-base font-black tracking-wider text-white font-['Tajawal']">التصنيفات والمجالات الهندسية</span>
                        <span class="block text-[10px] text-[#F3D98B] font-mono tracking-widest uppercase">إدارة شجرة التخصصات</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}">
                    <x-button variant="outline-gold" size="sm">العودة للوحة الإدارة</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6" x-data="{ createModal: false }">
        @if(session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif

        <div class="flex items-center justify-between p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-[#071A36] font-['Tajawal']">شجرة التخصصات الهندسية (BIM Domains)</h1>
                <p class="text-xs text-slate-500 mt-0.5">تصنيف البرامج التدريبية وفق التخصص: معماري، إنشائي، ميكانيكا، كهرباء، وتنسيق مشروعات</p>
            </div>
            <x-button @click="createModal = true" variant="gold" size="sm">
                + إضافة تصنيف جديد
            </x-button>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full text-right text-xs">
                <thead class="bg-[#F5F7FA] text-slate-600 uppercase font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-4">اسم التصنيف</th>
                        <th class="p-4">الاسم بالإنجليزية</th>
                        <th class="p-4">التصنيف الرئيسي (الأب)</th>
                        <th class="p-4">عدد الدورات</th>
                        <th class="p-4">الترتيب</th>
                        <th class="p-4 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-4 font-bold text-[#071A36]">
                                {{ $category->name_ar }}
                            </td>
                            <td class="p-4 font-mono text-slate-600">
                                {{ $category->name_en ?: '-' }}
                            </td>
                            <td class="p-4">
                                @if($category->parent)
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px]">
                                        {{ $category->parent->name_ar }}
                                    </span>
                                @else
                                    <span class="text-slate-400 font-bold">تصنيف رئيسي</span>
                                @endif
                            </td>
                            <td class="p-4 font-mono font-bold text-[#123B68]">
                                {{ $category->courses_count }} دورة
                            </td>
                            <td class="p-4 font-mono text-slate-500">
                                {{ $category->display_order }}
                            </td>
                            <td class="p-4 text-center">
                                <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" onsubmit="return confirm('هل تريد حذف هذا التصنيف؟');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">
                                لا توجد أي تصنيفات مسجلة حتى الآن.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Create Modal -->
        <div x-show="createModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-sm text-[#071A36]">إضافة تصنيف هندسي جديد</h3>
                    <button @click="createModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                    @csrf
                    <x-input name="name_ar" label="اسم التصنيف (بالعربية)" placeholder="مثال: نمذجة المنشآت الخرسانية والمعدنية" required />
                    <x-input name="name_en" label="الاسم بالإنجليزية (اختياري)" placeholder="e.g. Structural BIM" />

                    <div class="space-y-1.5 text-start">
                        <label for="parent_id" class="block text-xs font-semibold text-slate-700">التصنيف الرئيسي (إن وجد)</label>
                        <select name="parent_id" id="parent_id" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                            <option value="">-- تصنيف رئيسي (Root) --</option>
                            @foreach($rootCategories as $root)
                                <option value="{{ $root->id }}">{{ $root->name_ar }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5 text-start">
                        <label for="description_ar" class="block text-xs font-semibold text-slate-700">الوصف</label>
                        <textarea name="description_ar" id="description_ar" rows="2" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <x-button type="button" @click="createModal = false" variant="outline" size="sm">إلغاء</x-button>
                        <x-button type="submit" variant="gold" size="sm">حفظ التصنيف</x-button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</x-layouts.base>
