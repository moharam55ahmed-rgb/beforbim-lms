<x-layouts.base :title="'الحصص المباشرة — ' . $course->title_ar . ' — Beforbim'">
    <div class="min-h-screen bg-[#F5F7FA] py-10 px-4 md:px-8">
        <div class="max-w-5xl mx-auto space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <p class="text-xs text-slate-400 font-mono uppercase tracking-wider mb-1">{{ $course->title_ar }}</p>
                        <h1 class="text-xl md:text-2xl font-black text-[#071A36]">الحصص والمحاضرات المباشرة التفاعلية</h1>
                        <p class="text-xs text-slate-500 mt-1">حصص بث مباشر مع المدرب الهندسي عبر Zoom / Google Meet</p>
                    </div>
                    @if($user && in_array($user->role, ['instructor', 'admin', 'super_admin']))
                        <button
                            x-data
                            x-on:click="$dispatch('open-modal', 'schedule-live-class')"
                            class="px-5 py-2.5 bg-gradient-to-r from-[#D4AF37] to-[#F3D98B] text-[#071A36] text-xs font-black rounded-xl hover:opacity-95 transition shadow flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>جدولة حصة مباشرة جديدة</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Upcoming Live Classes -->
            @if($upcoming->isNotEmpty())
                <div class="space-y-3">
                    <h2 class="text-sm font-black text-[#071A36] px-1">الحصص القادمة المجدولة</h2>
                    @foreach($upcoming as $lc)
                        <a href="{{ route('live-class.show', $lc->id) }}" class="block bg-white rounded-2xl p-5 border-2 border-[#D4AF37]/40 hover:border-[#D4AF37] shadow-sm transition group">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#071A36] to-[#123B68] flex items-center justify-center text-[#D4AF37] shadow-md flex-shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="font-black text-[#071A36] group-hover:text-[#123B68] transition text-sm">{{ $lc->title_ar }}</h3>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $lc->scheduled_start_time->format('Y-m-d الساعة H:i') }} • {{ $lc->duration_minutes }} دقيقة • {{ $lc->provider }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-700 animate-pulse">قادمة</span>
                                    <svg class="w-4 h-4 text-slate-400 group-hover:text-[#D4AF37] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- All Live Classes -->
            <div class="space-y-3">
                <h2 class="text-sm font-black text-[#071A36] px-1">جميع الحصص والمحاضرات</h2>
                @forelse($liveClasses as $lc)
                    <a href="{{ route('live-class.show', $lc->id) }}" class="block bg-white rounded-xl p-4 border border-slate-200 hover:border-slate-300 shadow-sm transition group">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0
                                    @if($lc->status === 'SCHEDULED') bg-amber-100 text-amber-600
                                    @elseif($lc->status === 'COMPLETED') bg-emerald-100 text-emerald-600
                                    @else bg-rose-100 text-rose-400 @endif">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-[#071A36] group-hover:text-[#123B68] transition">{{ $lc->title_ar }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $lc->scheduled_start_time->format('Y-m-d H:i') }} • {{ $lc->duration_minutes }}د • {{ $lc->provider }}</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 text-[11px] font-bold rounded-full
                                @if($lc->status === 'SCHEDULED') bg-amber-100 text-amber-700
                                @elseif($lc->status === 'COMPLETED') bg-emerald-100 text-emerald-700
                                @else bg-rose-100 text-rose-700 @endif">
                                {{ match($lc->status) { 'SCHEDULED' => 'مجدولة', 'COMPLETED' => 'مكتملة', default => 'ملغاة' } }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 text-slate-400 text-sm">
                        لا توجد حصص مباشرة مجدولة لهذه الدورة حالياً.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Schedule Modal -->
    @if($user && in_array($user->role, ['instructor', 'admin', 'super_admin']))
        <div x-data="{ open: false }"
             x-on:open-modal.window="if ($event.detail === 'schedule-live-class') open = true"
             x-show="open"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#071A36]/60 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 space-y-5" @click.outside="open = false">
                <h2 class="text-lg font-black text-[#071A36]">جدولة حصة مباشرة جديدة</h2>
                <form action="{{ route('live-class.store', $course->slug) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">عنوان الحصة (بالعربي) *</label>
                        <input type="text" name="title_ar" required class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-[#123B68]" placeholder="مثال: الجلسة الأولى — مقدمة في نمذجة Revit Architecture">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">عنوان الحصة (بالإنجليزي)</label>
                        <input type="text" name="title_en" class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-[#123B68]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">وصف الحصة</label>
                        <textarea name="description_ar" rows="2" class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-[#123B68]"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">منصة البث المباشر *</label>
                            <select name="provider" class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-[#123B68]">
                                <option value="ZOOM">Zoom Meetings</option>
                                <option value="GOOGLE_MEET">Google Meet</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">مدة الحصة (دقيقة) *</label>
                            <input type="number" name="duration_minutes" value="90" min="15" max="480" class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-[#123B68]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">موعد بداية الحصة *</label>
                        <input type="datetime-local" name="scheduled_start_time" required class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:border-[#123B68]">
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="flex-1 py-2.5 bg-[#071A36] text-[#D4AF37] font-black text-sm rounded-xl hover:bg-[#123B68] transition">
                            جدولة الحصة وإشعار الطلاب
                        </button>
                        <button type="button" @click="open = false" class="px-4 py-2.5 bg-slate-100 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-200 transition">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-layouts.base>
