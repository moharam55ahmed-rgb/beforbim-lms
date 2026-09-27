<x-layouts.base title="مكتب الدعم والمساعدة الفنية (Helpdesk) — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg hover:opacity-95 transition">
                    B
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold font-['Tajawal'] text-white">مكتب الدعم والمساعدة الهندسية (Helpdesk)</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#D4AF37] text-[#071A36]">
                            إدارة العمليات
                        </span>
                    </div>
                    <p class="text-xs text-slate-300">فرز وتوزيع استفسارات الطلاب والمدربين ومتابعة مستوى الخدمة (SLA)</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}">
                    <x-button variant="outline-gold" size="sm">العودة للوحة التحكم</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6" dir="rtl">
        @if (session('status'))
            <x-alert type="success" :message="session('status')" />
        @endif

        <!-- Filter Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <form action="{{ route('admin.support.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">حالة التذكرة</label>
                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                        <option value="">جميع الحالات</option>
                        <option value="OPEN" {{ ($filters['status'] ?? '') === 'OPEN' ? 'selected' : '' }}>مفتوحة</option>
                        <option value="IN_PROGRESS" {{ ($filters['status'] ?? '') === 'IN_PROGRESS' ? 'selected' : '' }}>قيد المعالجة</option>
                        <option value="WAITING_FOR_STUDENT" {{ ($filters['status'] ?? '') === 'WAITING_FOR_STUDENT' ? 'selected' : '' }}>بانتظار الطالب</option>
                        <option value="RESOLVED" {{ ($filters['status'] ?? '') === 'RESOLVED' ? 'selected' : '' }}>تم الحل</option>
                        <option value="CLOSED" {{ ($filters['status'] ?? '') === 'CLOSED' ? 'selected' : '' }}>مغلقة</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">التصنيف</label>
                    <select name="category" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                        <option value="">جميع التصنيفات</option>
                        <option value="TECHNICAL" {{ ($filters['category'] ?? '') === 'TECHNICAL' ? 'selected' : '' }}>دعم فني</option>
                        <option value="ACADEMIC_CONTENT" {{ ($filters['category'] ?? '') === 'ACADEMIC_CONTENT' ? 'selected' : '' }}>محتوى أكاديمي</option>
                        <option value="BILLING" {{ ($filters['category'] ?? '') === 'BILLING' ? 'selected' : '' }}>المدفوعات والفواتير</option>
                        <option value="CERTIFICATE" {{ ($filters['category'] ?? '') === 'CERTIFICATE' ? 'selected' : '' }}>الشهادات</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">الأولوية</label>
                    <select name="priority" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                        <option value="">جميع الأولويات</option>
                        <option value="LOW" {{ ($filters['priority'] ?? '') === 'LOW' ? 'selected' : '' }}>منخفضة</option>
                        <option value="NORMAL" {{ ($filters['priority'] ?? '') === 'NORMAL' ? 'selected' : '' }}>عادية</option>
                        <option value="HIGH" {{ ($filters['priority'] ?? '') === 'HIGH' ? 'selected' : '' }}>عالية</option>
                        <option value="URGENT" {{ ($filters['priority'] ?? '') === 'URGENT' ? 'selected' : '' }}>طارئة</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">بحث برقم أو موضوع</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="ابحث..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-[#071A36] text-white text-xs font-bold hover:bg-[#123B68] transition">
                        تصفية
                    </button>
                    @if (array_filter($filters))
                        <a href="{{ route('admin.support.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition">
                            إلغاء
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Helpdesk Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden space-y-4">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-[#071A36]">قائمة التذاكر والبلاغات</h3>
                    <p class="text-xs text-slate-400 mt-0.5">متابعة زمن الاستجابة، تعيين المشرفين، وإغلاق التذاكر المكتملة</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold border-y border-slate-200">
                        <tr>
                            <th class="py-3 px-4">رقم التذكرة</th>
                            <th class="py-3 px-4">الطالب</th>
                            <th class="py-3 px-4">الموضوع</th>
                            <th class="py-3 px-4">التصنيف</th>
                            <th class="py-3 px-4">الأولوية</th>
                            <th class="py-3 px-4">المشرف المسؤول</th>
                            <th class="py-3 px-4">الحالة</th>
                            <th class="py-3 px-4 text-center">إجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($tickets as $ticket)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3.5 px-4 font-mono font-bold text-[#071A36]">{{ $ticket->ticket_number }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-slate-800 block">{{ $ticket->user?->name ?? 'طالب محذوف' }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $ticket->user?->email ?? '' }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-700 max-w-xs truncate">{{ $ticket->subject }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                        {{ $ticket->category }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if ($ticket->priority === 'URGENT')
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">طارئة</span>
                                    @elseif ($ticket->priority === 'HIGH')
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">عالية</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">{{ $ticket->priority }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">
                                    {{ $ticket->assignedStaff?->name ?? 'غير مسندة' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @if ($ticket->status === 'OPEN')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">مفتوحة</span>
                                    @elseif ($ticket->status === 'WAITING_FOR_STUDENT')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">بانتظار الطالب</span>
                                    @elseif ($ticket->status === 'RESOLVED')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">تم الحل</span>
                                    @elseif ($ticket->status === 'CLOSED')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">مغلقة</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800">قيد المعالجة</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <a href="{{ route('admin.support.show', $ticket) }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-[#071A36] text-white hover:bg-[#123B68] text-xs font-bold transition">
                                        معالجة
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-slate-400">لا توجد تذاكر دعم مطابقة للشروط.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $tickets->links() }}
            </div>
        </div>
    </main>
</x-layouts.base>
