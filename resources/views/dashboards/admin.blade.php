<x-layouts.base title="Executive Command Center — Beforbim">
    <!-- Admin Header (Deep Navy & Gold) -->
    <header class="bg-[#071A36]/95 backdrop-blur-md text-white border-b border-white/10 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img 
                        src="{{ asset('images/branding/logo.png') }}" 
                        alt="Beforbim" 
                        class="w-8 h-8 object-contain drop-shadow"
                        onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';"
                    >
                    <span class="font-black font-['Outfit'] text-base tracking-wider text-white hidden sm:inline">
                        BEFOR<span class="text-[#D4AF37]">BIM</span>
                    </span>
                </a>

                <div class="h-6 w-px bg-white/10 hidden sm:block"></div>

                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-sm sm:text-base font-bold font-['Outfit'] text-white">{{ $user->name }}</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#D4AF37] text-[#040E1E]">
                            Super Admin
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-400">Global Executive & Security Control Center</p><span class="sr-only">مركز التحكم الإداري العام</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.courses.pending') }}">
                    <button type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#F3D98B] text-[#040E1E] shadow-md shadow-[#D4AF37]/20 transition flex items-center gap-1.5">
                        <span>Audit Queue ({{ $stats['pending_course_approvals'] }})</span>
                    </button>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-white/5 hover:bg-red-500/20 text-slate-300 hover:text-red-400 border border-white/10 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-8">
        
        <!-- Header title -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-white font-['Outfit']">Platform Operations Overview</h2>
                <p class="text-xs text-slate-400 mt-1">Institutional metrics, financial transactions, ISO 19650 syllabus accreditation & system telemetry</p>
            </div>
            <div class="flex items-center gap-2 font-mono text-xs bg-[#040E1E] px-3.5 py-1.5 rounded-xl border border-white/10 text-slate-300">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>System Operational • Cairo Production Node</span>
            </div>
        </div>

        <!-- Admin Quick Actions Toolbar -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1">
            <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#071A36] border border-white/10 hover:border-[#D4AF37] text-xs font-bold text-white shadow-sm transition whitespace-nowrap">
                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Executive Reports</span>
            </a>
            <a href="{{ route('admin.support.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#071A36] border border-white/10 hover:border-[#D4AF37] text-xs font-bold text-white shadow-sm transition whitespace-nowrap">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>Support Helpdesk</span>
            </a>
            <a href="{{ route('admin.audit-logs.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#071A36] border border-white/10 hover:border-[#D4AF37] text-xs font-bold text-white shadow-sm transition whitespace-nowrap">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Security Audit Logs</span>
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#071A36] border border-white/10 hover:border-[#D4AF37] text-xs font-bold text-white shadow-sm transition whitespace-nowrap">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                <span>Course Reviews</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#071A36] border border-white/10 hover:border-[#D4AF37] text-xs font-bold text-white shadow-sm transition whitespace-nowrap">
                <span>CMS & Hero Settings</span>
            </a>
            <a href="{{ route('admin.media.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#071A36] border border-white/10 hover:border-[#D4AF37] text-xs font-bold text-white shadow-sm transition whitespace-nowrap">
                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Media Library</span>
            </a>
            <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#071A36] border border-white/10 hover:border-[#D4AF37] text-xs font-bold text-white shadow-sm transition whitespace-nowrap">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Payments & Orders</span>
            </a>
        </div>

        <!-- System Stats Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="rounded-3xl bg-[#071A36]/80 p-4 border border-white/10 shadow-xl">
                <span class="text-xs text-slate-400 block uppercase tracking-wider">Total Users</span>
                <span class="text-2xl font-black text-white font-['Outfit'] mt-1 block">{{ $stats['total_users'] }}</span>
                <span class="text-[10px] text-slate-400 mt-1 block">{{ $stats['active_students'] }} active engineers</span>
            </div>

            <div class="rounded-3xl bg-[#071A36]/80 p-4 border border-white/10 shadow-xl">
                <span class="text-xs text-slate-400 block uppercase tracking-wider">Faculty</span>
                <span class="text-2xl font-black text-white font-['Outfit'] mt-1 block">{{ $stats['instructors_count'] }}</span>
                <span class="text-[10px] text-amber-400 mt-1 block">{{ $stats['pending_instructor_profiles'] }} awaiting review</span>
            </div>

            <div class="rounded-3xl bg-[#071A36]/80 p-4 border border-[#D4AF37]/50 shadow-xl bg-gradient-to-b from-[#D4AF37]/10 to-transparent">
                <span class="text-xs font-bold text-[#F3D98B] block uppercase tracking-wider">Pending Courses</span>
                <span class="text-2xl font-black text-[#D4AF37] font-['Outfit'] mt-1 block">{{ $stats['pending_course_approvals'] }}</span>
                <span class="text-[10px] text-slate-400 mt-1 block">out of {{ $stats['total_courses'] }} courses</span>
            </div>

            <div class="rounded-3xl bg-[#071A36]/80 p-4 border border-white/10 shadow-xl">
                <span class="text-xs text-slate-400 block uppercase tracking-wider">Active Enrollments</span>
                <span class="text-2xl font-black text-emerald-400 font-['Outfit'] mt-1 block">{{ $stats['active_enrollments'] }}</span>
                <span class="text-[10px] text-slate-400 mt-1 block">Lifetime access</span>
            </div>

            <div class="rounded-3xl bg-[#071A36]/80 p-4 border border-white/10 shadow-xl">
                <span class="text-xs text-slate-400 block uppercase tracking-wider">Active Sessions</span>
                <span class="text-2xl font-black text-cyan-400 font-['Outfit'] mt-1 block">{{ $stats['active_device_sessions'] }}</span>
                <span class="text-[10px] text-emerald-400 mt-1 block">IP protected</span>
            </div>

            <div class="rounded-3xl bg-[#040E1E] p-4 border border-[#D4AF37]/40 shadow-xl">
                <span class="text-xs text-[#F3D98B] font-semibold block uppercase tracking-wider">Gross Revenue</span>
                <span class="text-xl font-black text-[#D4AF37] font-['Outfit'] mt-1 block">${{ number_format($stats['total_revenue'], 2) }}</span>
                <span class="text-[10px] text-slate-400 mt-1 block">{{ $stats['completed_transactions'] }} transactions</span>
            </div>
        </div>

        <!-- Pending Courses Table -->
        <div class="rounded-3xl bg-[#071A36]/80 border border-white/10 shadow-xl overflow-hidden">
            <div class="p-6 border-b border-white/10 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white font-['Outfit'] flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#D4AF37]"></span>
                        <span>Pending Course Accreditation Review</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Audit syllabus compliance, videos, and sample models prior to public publication</p>
                </div>
                <a href="{{ route('admin.courses.pending') }}" class="text-xs font-bold text-[#F3D98B] hover:underline">
                    View All &rarr;
                </a>
            </div>

            @if($pending_courses->isEmpty())
                <div class="p-8 text-center text-slate-400 text-xs font-light">
                    All submitted course curricula have been reviewed and approved. Queue is clear!
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#040E1E] text-slate-400 uppercase font-semibold border-b border-white/10">
                            <tr>
                                <th class="p-4">Course Title</th>
                                <th class="p-4">Instructor</th>
                                <th class="p-4">Category</th>
                                <th class="p-4">Tuition</th>
                                <th class="p-4">Submission Date</th>
                                <th class="p-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($pending_courses as $course)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="p-4 font-bold text-white">
                                        {{ $course->title_en ?: $course->title_ar ?: $course->title }}
                                    </td>
                                    <td class="p-4 text-slate-300">
                                        {{ $course->instructor?->name ?? 'Unassigned' }}
                                    </td>
                                    <td class="p-4">
                                        <span class="bg-[#040E1E] text-[#F3D98B] px-2.5 py-0.5 rounded-full text-[10px] font-bold border border-white/10">
                                            {{ $course->category?->name_en ?: ($course->category?->name_ar ?: 'BIM') }}
                                        </span>
                                    </td>
                                    <td class="p-4 font-mono font-bold text-white">
                                        ${{ number_format($course->effective_price, 0) }}
                                    </td>
                                    <td class="p-4 font-mono text-slate-400">
                                        {{ $course->updated_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <form method="POST" action="{{ route('admin.courses.approve', $course->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[11px] transition">
                                                    Approve & Publish
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.courses.reject', $course->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 rounded-lg bg-rose-600 hover:bg-rose-500 text-white font-bold text-[11px] transition">
                                                    Reject
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
            <div class="rounded-3xl bg-[#071A36]/80 border border-white/10 p-6 shadow-xl space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                    <h3 class="font-bold text-sm text-white font-['Outfit']">Recent Registrations</h3>
                    <span class="text-xs text-slate-400">Latest Engineers</span>
                </div>
                <div class="space-y-3">
                    @foreach($recent_users as $newUser)
                        <div class="flex items-center justify-between p-3.5 rounded-2xl bg-[#040E1E] border border-white/5">
                            <div>
                                <p class="text-xs font-bold text-white">{{ $newUser->name }}</p>
                                <p class="text-[11px] text-slate-400 font-mono">{{ $newUser->email }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-[#071A36] text-[#F3D98B] border border-white/10">
                                    {{ $newUser->roles->pluck('name')->join(', ') ?: 'Student' }}
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
            <div class="rounded-3xl bg-[#071A36]/80 border border-white/10 p-6 shadow-xl space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                    <h3 class="font-bold text-sm text-white font-['Outfit']">Recent Financial Orders</h3>
                    <span class="text-xs text-slate-400">Settlements</span>
                </div>
                @if($recent_payments->isEmpty())
                    <div class="text-center py-8 text-slate-400 text-xs font-light">
                        No transactions recorded in the recent window.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($recent_payments as $payment)
                            <div class="flex items-center justify-between p-3.5 rounded-2xl bg-[#040E1E] border border-white/5">
                                <div>
                                    <p class="text-xs font-bold text-white">{{ $payment->order?->user?->name ?? 'Engineer' }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $payment->payment_reference ?? 'Gateway Settled' }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-bold font-mono text-[#F3D98B]">
                                        ${{ number_format($payment->amount, 2) }}
                                    </span>
                                    <span class="block text-[10px] font-bold text-emerald-400">
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
