<x-layouts.base title="سجل التدقيق الإداري العام — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg hover:opacity-95 transition">
                    B
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold font-['Tajawal'] text-white">سجل التدقيق الإداري والعمليات غير القابل للتعديل</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#D4AF37] text-[#071A36]">
                            الأمان والامتثال
                        </span>
                    </div>
                    <p class="text-xs text-slate-300">سجل تدقيق رقابي غير قابل للتغيير لكافة العمليات الحساسة في النظام</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}">
                    <x-button variant="outline-gold" size="sm">العودة للوحة التحكم</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-8" dir="rtl">
        <!-- Filter Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">الموديول / القطاع</label>
                    <select name="module" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-[#071A36]">
                        <option value="">جميع القطاعات</option>
                        @foreach ($modules as $m)
                            <option value="{{ $m }}" {{ ($filters['module'] ?? '') === $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">اسم العملية / الحدث</label>
                    <input type="text" name="action" value="{{ $filters['action'] ?? '' }}" placeholder="مثل: PAYMENT_VERIFIED" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-[#071A36]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">من تاريخ</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">إلى تاريخ</label>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-[#071A36] text-white text-xs font-bold hover:bg-[#123B68] transition">
                        تصفية النتائج
                    </button>
                    @if (array_filter($filters))
                        <a href="{{ route('admin.audit-logs.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition">
                            إعادة ضبط
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden space-y-4">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-[#071A36]">سجل الحركات الإدارية والأمنية</h3>
                    <p class="text-xs text-slate-400 mt-0.5">تسجيل فوري مصحوب بعنوان IP، بصمة المتصفح، والقيم قبل وبعد التعديل</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold border-y border-slate-200">
                        <tr>
                            <th class="py-3 px-4">#</th>
                            <th class="py-3 px-4">القطاع</th>
                            <th class="py-3 px-4">العملية</th>
                            <th class="py-3 px-4">المنفّذ</th>
                            <th class="py-3 px-4">الدور</th>
                            <th class="py-3 px-4">عنوان IP</th>
                            <th class="py-3 px-4">التاريخ والوقت</th>
                            <th class="py-3 px-4 text-center">التفاصيل</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-sans">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3.5 px-4 font-mono text-[11px] text-slate-400">{{ $log->id }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-[#123B68]/10 text-[#123B68]">
                                        {{ $log->module }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-[#071A36]">{{ $log->action }}</td>
                                <td class="py-3.5 px-4 font-bold text-slate-800">{{ $log->actor?->name ?? 'النظام الآلي / زائر' }}</td>
                                <td class="py-3.5 px-4 text-slate-500">{{ $log->actor_role ?? '—' }}</td>
                                <td class="py-3.5 px-4 font-mono text-slate-500">{{ $log->ip_address }}</td>
                                <td class="py-3.5 px-4 font-mono text-slate-400">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    <a href="{{ route('admin.audit-logs.show', $log) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-[#071A36] text-[11px] font-bold transition">
                                        عرض
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-slate-400">لا توجد سجلات تدقيق مطابقة.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        </div>
    </main>
</x-layouts.base>
