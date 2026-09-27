<x-layouts.base title="مركز التحكم الإداري العام — Beforbim">
    <!-- Admin Header (Deep Navy & Gold) -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg">
                    B
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold font-['Tajawal'] text-white">{{ $user->name }}</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#D4AF37] text-[#071A36]">
                            إدارة عليا
                        </span>
                    </div>
                    <p class="text-xs text-slate-300">مركز التحكم العام والأمان المالي والأكاديمي</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.courses.pending') }}">
                    <x-button variant="gold" size="sm">
                        تدقيق الدورات ({{ $stats['pending_course_approvals'] }})
                    </x-button>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <x-button type="submit" variant="outline-gold" size="sm">تسجيل الخروج</x-button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-8">
        
        <!-- Header title -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-[#071A36] font-['Tajawal']">نظرة عامة على المنصة</h2>
                <p class="text-xs text-slate-500 mt-1">مؤشرات الأداء المؤسسي، الأمان، الاعتمادات الأكاديمية وحركة العمليات المالية</p>
            </div>
            <div class="flex items-center gap-2 font-mono text-xs bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>النظام يعمل بكفاءة: MySQL 8.4 • Laravel 13</span>
            </div>
        </div>

        <!-- System Stats Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
                <span class="text-xs text-slate-500 block">إجمالي المستخدمين</span>
                <span class="text-2xl font-black text-[#071A36] font-mono mt-1 block">{{ $stats['total_users'] }}</span>
                <span class="text-[10px] text-slate-400 mt-1 block">{{ $stats['active_students'] }} طالب نشط</span>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
                <span class="text-xs text-slate-500 block">طاقم المدربين</span>
                <span class="text-2xl font-black text-[#123B68] font-mono mt-1 block">{{ $stats['instructors_count'] }}</span>
                <span class="text-[10px] text-amber-600 mt-1 block">{{ $stats['pending_instructor_profiles'] }} بانتظار الاعتماد</span>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-[#D4AF37]/50 shadow-sm bg-gradient-to-b from-[#D4AF37]/5 to-transparent">
                <span class="text-xs font-bold text-[#071A36] block">دورات بانتظار الموافقة</span>
                <span class="text-2xl font-black text-[#D4AF37] font-mono mt-1 block">{{ $stats['pending_course_approvals'] }}</span>
                <span class="text-[10px] text-slate-500 mt-1 block">من أصل {{ $stats['total_courses'] }} دورة</span>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
                <span class="text-xs text-slate-500 block">الاشتراكات الفعالة</span>
                <span class="text-2xl font-black text-emerald-600 font-mono mt-1 block">{{ $stats['active_enrollments'] }}</span>
                <span class="text-[10px] text-slate-400 mt-1 block">وصول غير محدود</span>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
                <span class="text-xs text-slate-500 block">الجلسات النشطة</span>
                <span class="text-2xl font-black text-blue-600 font-mono mt-1 block">{{ $stats['active_device_sessions'] }}</span>
                <span class="text-[10px] text-emerald-600 mt-1 block">حماية متزامنة</span>
            </div>

            <div class="bg-[#071A36] text-white rounded-2xl p-4 border border-[#D4AF37]/40 shadow-xl shadow-black/10">
                <span class="text-xs text-[#F3D98B] font-semibold block">إجمالي التحصيلات</span>
                <span class="text-xl font-black text-[#D4AF37] font-mono mt-1 block">{{ number_format($stats['total_revenue'], 2) }}</span>
                <span class="text-[10px] text-slate-300 mt-1 block">{{ $stats['completed_transactions'] }} عملية مكتملة</span>
            </div>
        </div>

        <!-- Pending Courses Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-[#071A36] font-['Tajawal'] flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#D4AF37]"></span>
                        الدورات التدريبية المقدمة بانتظار الاعتماد الأكاديمي
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">تدقيق المناهج، الفصول والملفات المرفقة قبل النشر العام</p>
                </div>
                <a href="{{ route('admin.courses.pending') }}" class="text-xs font-bold text-[#123B68] hover:underline">
                    عرض الكل &larr;
                </a>
            </div>

            @if($pending_courses->isEmpty())
                <div class="p-8 text-center text-slate-400 text-sm">
                    لا توجد دورات بانتظار الاعتماد حالياً. جميع الدورات تمت مراجعتها!
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead class="bg-[#F5F7FA] text-slate-600 uppercase font-semibold border-b border-slate-200">
                            <tr>
                                <th class="p-4">عنوان الدورة</th>
                                <th class="p-4">المدرب</th>
                                <th class="p-4">التصنيف</th>
                                <th class="p-4">السعر</th>
                                <th class="p-4">تاريخ التقديم</th>
                                <th class="p-4 text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($pending_courses as $course)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 font-bold text-[#071A36]">
                                        {{ $course->title_ar ?? $course->title }}
                                    </td>
                                    <td class="p-4 text-slate-600">
                                        {{ $course->instructor?->name ?? 'غير محدد' }}
                                    </td>
                                    <td class="p-4">
                                        <span class="bg-blue-50 text-[#123B68] px-2 py-0.5 rounded font-medium">
                                            {{ $course->category?->name_ar ?? 'عام' }}
                                        </span>
                                    </td>
                                    <td class="p-4 font-mono font-bold text-slate-800">
                                        {{ $course->effective_price }} {{ $course->currency }}
                                    </td>
                                    <td class="p-4 font-mono text-slate-400">
                                        {{ $course->updated_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <form method="POST" action="{{ route('admin.courses.approve', $course->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] transition-colors">
                                                    اعتماد ونشر
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.courses.reject', $course->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-[11px] transition-colors">
                                                    رفض
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- 2 Column Overview (Recent Users & Recent Payments) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Users -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-sm text-[#071A36]">المستخدمون الجدد</h3>
                    <span class="text-xs text-slate-400">آخر المسجلين</span>
                </div>
                <div class="space-y-3">
                    @foreach($recent_users as $newUser)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <div>
                                <p class="text-xs font-bold text-[#071A36]">{{ $newUser->name }}</p>
                                <p class="text-[11px] text-slate-400 font-mono">{{ $newUser->email }}</p>
                            </div>
                            <div class="text-left">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-[#123B68]">
                                    {{ $newUser->roles->pluck('name')->join(', ') ?: 'طالب' }}
                                </span>
                                <span class="block text-[10px] text-slate-400 font-mono mt-1">
                                    {{ $newUser->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-sm text-[#071A36]">آخر العمليات المالية</h3>
                    <span class="text-xs text-slate-400">المعاملات البنكية</span>
                </div>
                @if($recent_payments->isEmpty())
                    <div class="text-center py-8 text-slate-400 text-xs">
                        لا توجد عمليات دفع حديثة مسجلة.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($recent_payments as $payment)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <div>
                                    <p class="text-xs font-bold text-[#071A36]">{{ $payment->order?->user?->name ?? 'عميل' }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $payment->payment_reference ?? 'تحويل بنكي' }}</p>
                                </div>
                                <div class="text-left">
                                    <span class="text-xs font-bold font-mono text-[#D4AF37]">
                                        {{ number_format($payment->amount, 2) }} {{ $payment->currency }}
                                    </span>
                                    <span class="block text-[10px] font-bold text-emerald-600">
                                        {{ $payment->status }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </main>
</x-layouts.base>
