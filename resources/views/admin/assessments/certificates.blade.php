<x-layouts.base :title="'إدارة واعتماد الشهادات الهندسية — Beforbim'">
    <div class="min-h-screen bg-[#F5F7FA] py-10 px-4 md:px-8">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <div>
                    <h1 class="text-xl md:text-2xl font-black text-[#071A36]">سجل الشهادات والاعتمادات الهندسية</h1>
                    <p class="text-xs md:text-sm text-slate-500 mt-1">مراجعة الشهادات الصادرة، التحقق من السجلات، سحب وإلغاء أو إعادة تفعيل الشهادات</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-bold">
                    <div class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200">
                        الشهادات السارية: {{ $totalActive }}
                    </div>
                    <div class="px-3 py-1.5 rounded-xl bg-rose-50 text-rose-700 border border-rose-200">
                        الشهادات الملغاة: {{ $totalRevoked }}
                    </div>
                </div>
            </div>

            <!-- Filter Bar -->
            <form action="{{ route('admin.certificates.index') }}" method="GET" class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 grid grid-cols-1 md:grid-cols-3 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث برقم الشهادة، كود التحقق، اسم الطالب أو الدورة..." class="px-3 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-[#123B68]">
                
                <select name="status" class="px-3 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-[#123B68]">
                    <option value="">جميع الحالات</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشطة وسارية (Active)</option>
                    <option value="revoked" {{ request('status') === 'revoked' ? 'selected' : '' }}>ملغاة ومسحوبة (Revoked)</option>
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-[#071A36] text-[#D4AF37] font-bold text-xs rounded-xl hover:bg-[#123B68] transition">
                        بحث وتصفية
                    </button>
                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('admin.certificates.index') }}" class="px-3 py-2 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 text-xs flex items-center">
                            إعادة ضبط
                        </a>
                    @endif
                </div>
            </form>

            <!-- Certificates Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs md:text-sm">
                        <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-4">بيانات الشهادة</th>
                                <th class="p-4">المهندس المتدرب</th>
                                <th class="p-4">الدورة الهندسية</th>
                                <th class="p-4">تاريخ الإصدار</th>
                                <th class="p-4">الحالة</th>
                                <th class="p-4 text-center">الإجراءات والتحكم</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($certificates as $cert)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="p-4">
                                        <div class="font-mono font-bold text-[#071A36]">{{ $cert->certificate_number }}</div>
                                        <div class="text-[11px] font-mono text-slate-400 mt-0.5">كود: {{ $cert->verification_code }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-slate-800">{{ $cert->student_name_snapshot }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $cert->user?->email }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-[#123B68]">{{ $cert->course_title_snapshot_ar }}</div>
                                        <div class="text-[11px] text-slate-400">المدرب: {{ $cert->instructor_name_snapshot }}</div>
                                    </td>
                                    <td class="p-4 font-mono text-slate-600">
                                        {{ $cert->issued_at ? $cert->issued_at->format('Y-m-d') : '-' }}
                                    </td>
                                    <td class="p-4">
                                        @if($cert->isActive())
                                            <span class="px-2.5 py-1 text-xs rounded-full bg-emerald-100 text-emerald-800 font-bold">معتمدة وسارية</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs rounded-full bg-rose-100 text-rose-800 font-bold">ملغاة ومسحوبة</span>
                                            @if($cert->revocation_reason)
                                                <div class="text-[10px] text-rose-600 mt-1 max-w-[200px] truncate" title="{{ $cert->revocation_reason }}">
                                                    السبب: {{ $cert->revocation_reason }}
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('certificate.public.download', $cert->verification_code) }}" class="p-1.5 text-slate-600 hover:text-[#071A36] hover:bg-slate-100 rounded-lg transition" title="تحميل PDF">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            </a>
                                            <a href="{{ route('certificate.verify', ['code' => $cert->verification_code]) }}" target="_blank" class="p-1.5 text-slate-600 hover:text-[#123B68] hover:bg-slate-100 rounded-lg transition" title="صفحة التحقق">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>

                                            @if($cert->isActive())
                                                <!-- Revoke Form -->
                                                <form action="{{ route('admin.certificates.revoke', $cert->id) }}" method="POST" onsubmit="var r = prompt('أدخل سبب سحب الشهادة:'); if(!r) return false; this.reason.value = r;" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="reason">
                                                    <button type="submit" class="px-2.5 py-1 text-xs bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-lg font-bold transition">
                                                        سحب
                                                    </button>
                                                </form>
                                            @else
                                                <!-- Reissue Form -->
                                                <form action="{{ route('admin.certificates.reissue', $cert->id) }}" method="POST" onsubmit="return confirm('هل تريد إعادة تفعيل وإصدار هذه الشهادة؟')" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-2.5 py-1 text-xs bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg font-bold transition">
                                                        إعادة تفعيل
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-slate-400 text-sm">لا توجد شهادات مسجلة حالياً.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($certificates->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $certificates->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.base>
