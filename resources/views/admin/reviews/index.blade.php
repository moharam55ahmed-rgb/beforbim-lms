<x-layouts.base title="تدقيق تقييمات الدورات — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg hover:opacity-95 transition">
                    B
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold font-['Tajawal'] text-white">إدارة وتدقيق تقييمات الطلاب</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#D4AF37] text-[#071A36]">
                            الجودة والسمعة
                        </span>
                    </div>
                    <p class="text-xs text-slate-300">مراجعة آراء الطلاب واعتماد التقييمات الحقيقية واستبعاد المخالفة</p>
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

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-4">
            <a href="{{ route('admin.reviews.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ empty($currentStatus) ? 'bg-[#071A36] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                كافة التقييمات
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'pending' ? 'bg-[#071A36] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                بانتظار المراجعة (Pending)
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'approved' ? 'bg-[#071A36] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                التقييمات المعتمدة (Approved)
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $currentStatus === 'rejected' ? 'bg-[#071A36] text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                المرفوضة (Rejected)
            </a>
        </div>

        <!-- Reviews Table -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden space-y-4">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold border-y border-slate-200">
                        <tr>
                            <th class="py-3 px-4">الدورة التدريبية</th>
                            <th class="py-3 px-4">الطالب</th>
                            <th class="py-3 px-4">التقييم</th>
                            <th class="py-3 px-4">نص التقييم والملاحظات</th>
                            <th class="py-3 px-4">الحالة</th>
                            <th class="py-3 px-4">التاريخ</th>
                            <th class="py-3 px-4 text-center">إجراء الإدارة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($reviews as $review)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3.5 px-4 font-bold text-[#071A36]">{{ $review->course?->title_ar ?? '—' }}</td>
                                <td class="py-3.5 px-4 font-bold text-slate-800">{{ $review->student?->name ?? 'طالب' }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center text-amber-400">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                        <span class="mr-1 text-[11px] font-mono font-bold text-slate-600">({{ $review->rating }}/5)</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-700 max-w-sm">
                                    <p class="truncate">{{ $review->review_text ?? 'بدون تعليق نصي' }}</p>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if ($review->status === 'approved')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">معتمد</span>
                                    @elseif ($review->status === 'rejected')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">مرفوض</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">قيد المراجعة</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-400 font-mono">{{ $review->created_at->format('Y-m-d') }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        @if ($review->status !== 'approved')
                                            <form action="{{ route('admin.reviews.moderate', $review) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-[11px] font-bold transition">
                                                    اعتماد
                                                </button>
                                            </form>
                                        @endif

                                        @if ($review->status !== 'rejected')
                                            <form action="{{ route('admin.reviews.moderate', $review) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-600 hover:bg-rose-500 text-white text-[11px] font-bold transition">
                                                    رفض
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">لا توجد تقييمات مطابقة للشروط.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $reviews->links() }}
            </div>
        </div>
    </main>
</x-layouts.base>
