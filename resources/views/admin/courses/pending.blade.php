<x-layouts.base title="تدقيق واعتماد الدورات — Beforbim">
    <!-- Admin Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg">
                        B
                    </div>
                    <div>
                        <span class="text-base font-black tracking-wider text-white font-['Tajawal']">مركز التدقيق الأكاديمي</span>
                        <span class="block text-[10px] text-[#F3D98B] font-mono tracking-widest uppercase">مراجعة واعتماد المناهج</span>
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

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6">
        @if(session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#071A36] font-['Tajawal']">الدورات المقدمة بانتظار الاعتماد الأكاديمي</h1>
                <p class="text-xs text-slate-500 mt-1">راجع محتوى الدورات والأقسام وملفات الـ BIM قبل إتاحتها للمشتركين</p>
            </div>
            <span class="px-3 py-1 rounded-xl text-xs font-bold font-mono bg-amber-100 text-amber-900 border border-amber-300">
                {{ $courses->total() }} دورة قيد المراجعة
            </span>
        </div>

        @if($courses->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <svg class="w-12 h-12 text-emerald-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-base font-bold text-[#071A36]">لا توجد دورات بانتظار الاعتماد حالياً</h3>
                <p class="text-xs text-slate-500 mt-1">تمت مراجعة جميع الدورات المقدمة واعتمادها أو إرجاعها للمدربين.</p>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead class="bg-[#F5F7FA] text-slate-600 uppercase font-semibold border-b border-slate-200">
                            <tr>
                                <th class="p-4">عنوان الدورة</th>
                                <th class="p-4">المدرب</th>
                                <th class="p-4">التصنيف</th>
                                <th class="p-4">الأقسام والدروس</th>
                                <th class="p-4">السعر</th>
                                <th class="p-4">تاريخ التقديم</th>
                                <th class="p-4 text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($courses as $course)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 font-bold text-[#071A36]">
                                        {{ $course->title_ar }}
                                    </td>
                                    <td class="p-4 text-slate-600">
                                        {{ $course->instructor?->name ?? 'غير محدد' }}
                                    </td>
                                    <td class="p-4">
                                        <span class="bg-blue-50 text-[#123B68] px-2 py-0.5 rounded font-medium">
                                            {{ $course->category?->name_ar ?? 'عام' }}
                                        </span>
                                    </td>
                                    <td class="p-4 font-mono text-slate-600">
                                        {{ $course->sections->count() }} أقسام ({{ $course->lessons->count() }} درس)
                                    </td>
                                    <td class="p-4 font-mono font-bold text-[#071A36]">
                                        {{ $course->effective_price }} {{ $course->currency }}
                                    </td>
                                    <td class="p-4 font-mono text-slate-400">
                                        {{ $course->submitted_at?->diffForHumans() ?? $course->updated_at->diffForHumans() }}
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.courses.review', $course->id) }}" class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#123B68] font-bold text-[11px] transition-colors">
                                                معاينة وتدقيق
                                            </a>
                                            <form method="POST" action="{{ route('admin.courses.approve', $course->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] transition-colors">
                                                    اعتماد ونشر
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $courses->links() }}
            </div>
        @endif
    </main>
</x-layouts.base>
