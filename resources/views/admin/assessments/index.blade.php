<x-layouts.base :title="'إدارة الاختبارات والتقييمات الهندسية — Beforbim'">
    <div class="min-h-screen bg-[#F5F7FA] py-10 px-4 md:px-8">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <div>
                    <h1 class="text-xl md:text-2xl font-black text-[#071A36]">مركز تدقيق ومراجعة الاختبارات الهندسية</h1>
                    <p class="text-xs md:text-sm text-slate-500 mt-1">متابعة واعتماد اختبارات الدورات ونسب النجاح وبنك الأسئلة</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.assessments.questions') }}" class="px-4 py-2 bg-[#123B68] text-white hover:bg-[#071A36] text-xs font-bold rounded-xl transition flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>بنك الأسئلة الهندسية</span>
                    </a>
                    <a href="{{ route('admin.certificates.index') }}" class="px-4 py-2 bg-gradient-to-r from-[#D4AF37] to-[#F3D98B] text-[#071A36] text-xs font-black rounded-xl hover:opacity-95 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        <span>إدارة وسحب الشهادات</span>
                    </a>
                </div>
            </div>

            <!-- Assessments Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs md:text-sm">
                        <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-4">الاختبار / الدورة</th>
                                <th class="p-4">عدد الأسئلة</th>
                                <th class="p-4">الدرجة الكلية</th>
                                <th class="p-4">نسبة النجاح</th>
                                <th class="p-4">إجمالي المحاولات</th>
                                <th class="p-4">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($assessments as $assessment)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="p-4">
                                        <div class="font-bold text-[#071A36]">{{ $assessment->title_ar }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $assessment->course?->title_ar }}</div>
                                    </td>
                                    <td class="p-4 font-mono font-bold text-slate-700">{{ $assessment->questions_count }}</td>
                                    <td class="p-4 font-mono font-bold text-[#123B68]">{{ $assessment->total_points }} نقطة</td>
                                    <td class="p-4 font-mono text-emerald-600 font-bold">{{ $assessment->passing_percentage }}%</td>
                                    <td class="p-4 font-mono text-slate-600">{{ $assessment->attempts_count }}</td>
                                    <td class="p-4">
                                        <span class="px-2.5 py-1 text-xs rounded-full bg-emerald-100 text-emerald-800 font-bold">نشط ومفعل</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-slate-400 text-sm">لا توجد اختبارات مسجلة حالياً.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($assessments->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $assessments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.base>
