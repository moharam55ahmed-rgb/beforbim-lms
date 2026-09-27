<x-layouts.base title="معالجة تذكرة #{{ $ticket->ticket_number }} — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.support.index') }}" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg hover:opacity-95 transition">
                    B
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold font-['Tajawal'] text-white">معالجة تذكرة #{{ $ticket->ticket_number }}</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#D4AF37] text-[#071A36]">
                            {{ $ticket->category }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-300">طالب: {{ $ticket->user?->name }} • {{ $ticket->subject }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.support.index') }}">
                    <x-button variant="outline-gold" size="sm">العودة للتذاكر</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6" dir="rtl">
        @if (session('status'))
            <x-alert type="success" :message="session('status')" />
        @endif

        <!-- Controls Grid: Assignment & Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Assignment Card -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm space-y-3">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">المشرف المسؤول عن المتابعة</h4>
                <form action="{{ route('admin.support.assign', $ticket) }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    <select name="assigned_to_user_id" class="flex-1 px-3 py-2 rounded-xl border border-slate-300 text-xs">
                        <option value="">اختر مشرفاً...</option>
                        @foreach ($staffMembers as $staff)
                            <option value="{{ $staff->id }}" {{ $ticket->assigned_to_user_id == $staff->id ? 'selected' : '' }}>
                                {{ $staff->name }} ({{ $staff->email }})
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#071A36] text-white hover:bg-[#123B68] text-xs font-bold transition">
                        تعيين
                    </button>
                </form>
            </div>

            <!-- Status Card -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm space-y-3">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">تحديث مرحلة التذكرة</h4>
                <form action="{{ route('admin.support.status', $ticket) }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    <select name="status" class="flex-1 px-3 py-2 rounded-xl border border-slate-300 text-xs font-bold">
                        <option value="OPEN" {{ $ticket->status === 'OPEN' ? 'selected' : '' }}>مفتوحة (OPEN)</option>
                        <option value="IN_PROGRESS" {{ $ticket->status === 'IN_PROGRESS' ? 'selected' : '' }}>قيد المعالجة (IN_PROGRESS)</option>
                        <option value="WAITING_FOR_STUDENT" {{ $ticket->status === 'WAITING_FOR_STUDENT' ? 'selected' : '' }}>بانتظار رد الطالب (WAITING_FOR_STUDENT)</option>
                        <option value="RESOLVED" {{ $ticket->status === 'RESOLVED' ? 'selected' : '' }}>تم الحل (RESOLVED)</option>
                        <option value="CLOSED" {{ $ticket->status === 'CLOSED' ? 'selected' : '' }}>إغلاق نهائي (CLOSED)</option>
                    </select>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-500 text-xs font-bold transition">
                        حفظ الحالة
                    </button>
                </form>
            </div>
        </div>

        <!-- Thread Messages -->
        <div class="space-y-4">
            @foreach ($ticket->messages as $msg)
                <div class="p-6 rounded-3xl border {{ $msg->is_internal_note ? 'bg-amber-100/60 border-amber-300' : ($msg->is_staff_reply ? 'bg-blue-50/50 border-blue-200' : 'bg-white border-slate-200/80') }} shadow-sm space-y-3">
                    <div class="flex items-center justify-between border-b {{ $msg->is_internal_note ? 'border-amber-200' : 'border-slate-100' }} pb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold {{ $msg->is_internal_note ? 'bg-amber-500 text-white' : ($msg->is_staff_reply ? 'bg-[#071A36] text-[#D4AF37]' : 'bg-slate-200 text-slate-700') }}">
                                {{ substr($msg->user?->name ?? 'U', 0, 1) }}
                            </span>
                            <div>
                                <span class="text-xs font-bold text-slate-800">{{ $msg->user?->name ?? 'مستخدم' }}</span>
                                @if ($msg->is_internal_note)
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500 text-white mr-1">ملاحظة داخلية خاصة (غير مرئية للطالب)</span>
                                @elseif ($msg->is_staff_reply)
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-[#D4AF37] text-[#071A36] mr-1">رد المشرف</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 mr-1">الطالب</span>
                                @endif
                            </div>
                        </div>
                        <span class="text-[11px] font-mono text-slate-400">{{ $msg->created_at->format('Y-m-d H:i') }}</span>
                    </div>

                    <p class="text-xs text-slate-800 leading-relaxed whitespace-pre-line">{{ $msg->message }}</p>

                    @if ($msg->attachments->isNotEmpty())
                        <div class="pt-2 border-t {{ $msg->is_internal_note ? 'border-amber-200' : 'border-slate-100' }} flex flex-wrap gap-2">
                            @foreach ($msg->attachments as $att)
                                <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200 hover:border-[#D4AF37] text-xs text-slate-700 transition">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <span class="truncate max-w-[180px]">{{ $att->file_name }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Staff Reply Form -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4" x-data="{ isInternal: false }">
            <h3 class="text-sm font-bold text-[#071A36]">إرسال رد أو إضافة ملاحظة داخلية</h3>
            <form action="{{ route('admin.support.reply', $ticket) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="flex items-center gap-4 bg-slate-50 p-3 rounded-2xl border border-slate-200 text-xs">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_internal_note" value="0" x-model="isInternal" checked class="text-[#071A36] focus:ring-[#071A36]">
                        <span class="font-bold text-slate-800">رد عام يظهر للطالب</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_internal_note" value="1" x-model="isInternal" class="text-amber-500 focus:ring-amber-500">
                        <span class="font-bold text-amber-700">ملاحظة داخلية سرية (لفريق العمل فقط)</span>
                    </label>
                </div>

                <div>
                    <textarea name="message" rows="4" :placeholder="isInternal ? 'اكتب ملاحظتك التنسيقية للفريق هنا...' : 'اكتب ردك ومساعدتك الهندسية للطالب هنا...'" required class="w-full px-4 py-3 rounded-2xl border text-xs focus:ring-2 focus:outline-none" :class="isInternal ? 'border-amber-300 bg-amber-50/20 focus:ring-amber-400' : 'border-slate-300 focus:ring-[#071A36]'"></textarea>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <input type="file" name="attachment" class="text-xs text-slate-500 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 file:cursor-pointer">
                    </div>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold transition shadow-md" :class="isInternal ? 'bg-amber-600 hover:bg-amber-500 text-white shadow-amber-600/20' : 'bg-[#071A36] hover:bg-[#123B68] text-white shadow-[#071A36]/20'">
                        <span x-text="isInternal ? 'تسجيل الملاحظة الداخلية' : 'إرسال الرد للطالب'"></span>
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-layouts.base>
