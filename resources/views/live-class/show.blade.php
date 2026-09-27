<x-layouts.base :title="$liveClass->title_ar . ' — حصة مباشرة — Beforbim'">
    <div class="min-h-screen bg-[#F5F7FA] py-10 px-4 md:px-8">
        <div class="max-w-4xl mx-auto space-y-6">

            <!-- Live Class Card -->
            <div class="bg-white rounded-2xl shadow-sm border {{ $liveClass->status === 'SCHEDULED' ? 'border-[#D4AF37]/50' : ($liveClass->status === 'COMPLETED' ? 'border-emerald-300' : 'border-rose-200') }} overflow-hidden">
                <!-- Header Bar -->
                <div class="bg-gradient-to-r from-[#071A36] to-[#123B68] p-6 text-white">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-[#D4AF37]/20 border border-[#D4AF37]/40 flex items-center justify-center text-[#D4AF37] flex-shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-[#F3D98B] text-xs font-mono uppercase tracking-wider mb-1">
                                    {{ $course->title_ar }} • {{ $liveClass->provider }}
                                </p>
                                <h1 class="text-xl md:text-2xl font-black">{{ $liveClass->title_ar }}</h1>
                            </div>
                        </div>
                        <span class="px-4 py-1.5 text-xs font-black rounded-full self-start md:self-auto
                            @if($liveClass->status === 'SCHEDULED') bg-amber-400 text-amber-900 animate-pulse
                            @elseif($liveClass->status === 'LIVE') bg-red-500 text-white animate-pulse
                            @elseif($liveClass->status === 'COMPLETED') bg-emerald-400 text-emerald-900
                            @else bg-slate-400 text-slate-900 @endif">
                            {{ match($liveClass->status) {
                                'SCHEDULED' => '⏰ مجدولة',
                                'LIVE' => '🔴 يبث الآن',
                                'COMPLETED' => '✅ مكتملة',
                                default => '❌ ملغاة'
                            } }}
                        </span>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-4 border-b border-slate-100">
                    <div class="text-center">
                        <p class="text-xs text-slate-400 mb-0.5">موعد الحصة</p>
                        <p class="text-sm font-bold text-[#071A36]">{{ $liveClass->scheduled_start_time->format('Y-m-d') }}</p>
                        <p class="text-xs text-slate-600">{{ $liveClass->scheduled_start_time->format('H:i') }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-slate-400 mb-0.5">المدة الزمنية</p>
                        <p class="text-sm font-bold text-[#071A36]">{{ $liveClass->duration_minutes }} دقيقة</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-slate-400 mb-0.5">المنصة</p>
                        <p class="text-sm font-bold text-[#123B68]">{{ $liveClass->provider }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-slate-400 mb-0.5">عدد المشتركين</p>
                        <p class="text-sm font-bold text-[#071A36]">{{ $attendeesCount }}</p>
                    </div>
                </div>

                <!-- Description -->
                @if($liveClass->description_ar)
                    <div class="px-6 py-4 border-b border-slate-100">
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $liveClass->description_ar }}</p>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="p-6 flex flex-col sm:flex-row gap-3">
                    @if($liveClass->status === 'SCHEDULED' || $liveClass->status === 'LIVE')
                        <a href="{{ route('live-class.join', $liveClass->id) }}"
                           class="flex-1 inline-flex items-center justify-center gap-2 py-3 px-6 bg-gradient-to-r from-[#D4AF37] to-[#F3D98B] text-[#071A36] font-black text-sm rounded-xl hover:opacity-95 transition shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span>الانضمام إلى الحصة المباشرة</span>
                        </a>
                    @endif

                    @if($liveClass->recording_url && $liveClass->status === 'COMPLETED')
                        <a href="{{ $liveClass->recording_url }}" target="_blank"
                           class="flex-1 inline-flex items-center justify-center gap-2 py-3 px-6 bg-[#071A36] text-white font-bold text-sm rounded-xl hover:bg-[#123B68] transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>مشاهدة التسجيل</span>
                        </a>
                    @endif

                    @if($user && ($user->id === $liveClass->instructor_id || in_array($user->role, ['admin', 'super_admin'])))
                        @if($liveClass->status === 'SCHEDULED')
                            <form action="{{ route('live-class.complete', $liveClass->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-3 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-xl font-bold text-sm transition">
                                    إغلاق كمكتملة
                                </button>
                            </form>
                            <form action="{{ route('live-class.cancel', $liveClass->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من إلغاء الحصة؟')" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-3 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-xl font-bold text-sm transition">
                                    إلغاء الحصة
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Instructor & Meeting Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                    <h3 class="text-xs font-black text-[#071A36] uppercase tracking-wider mb-3">المدرب الهندسي</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-[#071A36] to-[#123B68] flex items-center justify-center text-[#D4AF37] font-black text-sm">
                            {{ mb_substr($liveClass->instructor?->name ?? 'M', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-[#071A36] text-sm">{{ $liveClass->instructor?->name }}</p>
                            <p class="text-xs text-slate-400">BIM Lead Instructor</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                    <h3 class="text-xs font-black text-[#071A36] uppercase tracking-wider mb-3">بيانات الاجتماع</h3>
                    <div class="space-y-1.5 text-xs">
                        <div class="flex items-center gap-2 text-slate-600">
                            <span class="font-bold text-slate-800">Meeting ID:</span>
                            <span class="font-mono">{{ $liveClass->meeting_id }}</span>
                        </div>
                        @if($liveClass->meeting_password)
                            <div class="flex items-center gap-2 text-slate-600">
                                <span class="font-bold text-slate-800">Password:</span>
                                <span class="font-mono">{{ $liveClass->meeting_password }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Back Link -->
            <div class="text-center">
                <a href="{{ route('live-class.index', $course->slug) }}" class="text-xs text-slate-400 hover:text-[#123B68] transition font-medium">
                    ← العودة إلى قائمة الحصص المباشرة
                </a>
            </div>
        </div>
    </div>
</x-layouts.base>
