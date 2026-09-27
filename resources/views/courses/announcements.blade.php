<x-layouts.base title="إعلانات وتحديثات الدورة — {{ $course->title_ar }}">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('learn.player', ['course' => $course->slug]) }}" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg hover:opacity-95 transition">
                    B
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold font-['Tajawal'] text-white">إعلانات وتحديثات الدورة</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#D4AF37] text-[#071A36]">
                            تواصل مباشر
                        </span>
                    </div>
                    <p class="text-xs text-slate-300">{{ $course->title_ar }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('learn.player', ['course' => $course->slug]) }}">
                    <x-button variant="outline-gold" size="sm">العودة لمشاهدة الدروس</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6" dir="rtl">
        @if (session('status'))
            <x-alert type="success" :message="session('status')" />
        @endif

        <!-- Instructor Form to post announcement (if instructor or admin) -->
        @if (auth()->check() && (auth()->id() === $course->instructor_id || auth()->user()->hasAnyRole(['admin', 'super_admin'])))
            <div class="bg-white rounded-3xl p-6 border border-[#D4AF37]/40 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-[#071A36] flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <span>نشر إعلان جديد لطلاب هذا المسار</span>
                </h3>

                <form action="{{ route('courses.announcements.store', $course) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <input type="text" name="title" placeholder="عنوان الإعلان أو التحديث المهم..." required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                    </div>
                    <div>
                        <textarea name="content" rows="3" placeholder="اكتب تفاصيل الإعلان هنا... سيتم إشعار جميع الطلاب المسجلين بالدورة فوراً." required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-[#071A36] focus:outline-none"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#071A36] text-white hover:bg-[#123B68] text-xs font-bold transition">
                            نشر وإشعار الطلاب
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Announcements List -->
        <div class="space-y-4">
            @forelse ($announcements as $announcement)
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-[#071A36] text-[#D4AF37] flex items-center justify-center text-xs font-bold">
                                {{ substr($announcement->instructor?->name ?? 'I', 0, 1) }}
                            </span>
                            <div>
                                <span class="text-xs font-bold text-slate-800">{{ $announcement->instructor?->name }}</span>
                                <span class="text-[10px] text-slate-400 mr-1">مدرب الدورة</span>
                            </div>
                        </div>
                        <span class="text-[11px] font-mono text-slate-400">{{ $announcement->published_at?->diffForHumans() }}</span>
                    </div>

                    <h4 class="text-sm font-bold text-[#071A36]">{{ $announcement->title }}</h4>
                    <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line">{{ $announcement->content }}</p>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center text-slate-400 border border-slate-200">
                    <p class="text-sm font-bold text-slate-600">لا توجد إعلانات منشورة لهذه الدورة بعد.</p>
                    <p class="text-xs text-slate-400 mt-1">يقوم المدرب بنشر التحديثات ومواعيد المحاضرات هنا بشكل دوري.</p>
                </div>
            @endforelse
        </div>

        <div class="p-4">
            {{ $announcements->links() }}
        </div>
    </main>
</x-layouts.base>
