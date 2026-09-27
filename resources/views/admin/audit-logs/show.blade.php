<x-layouts.base title="تفاصيل حركة التدقيق #{{ $log->id }} — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.audit-logs.index') }}" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg hover:opacity-95 transition">
                    B
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold font-['Tajawal'] text-white">تفاصيل حركة التدقيق #{{ $log->id }}</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#D4AF37] text-[#071A36]">
                            {{ $log->module }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-300">{{ $log->action }} • سجل غير قابل للتعديل</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.audit-logs.index') }}">
                    <x-button variant="outline-gold" size="sm">العودة للسجل</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6" dir="rtl">
        <!-- Overview Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-[#071A36] border-b border-slate-100 pb-3">بيانات العملية والمنفّذ</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-slate-400 block mb-1">المنفّذ (Actor)</span>
                    <span class="font-bold text-slate-800">{{ $log->actor?->name ?? 'النظام الآلي / زائر' }} (ID: {{ $log->actor_id ?? 'N/A' }})</span>
                </div>
                <div>
                    <span class="text-slate-400 block mb-1">الدور الوظيفي</span>
                    <span class="font-bold text-slate-800">{{ $log->actor_role ?? '—' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block mb-1">الهدف المتأثر (Target)</span>
                    <span class="font-mono text-slate-800">{{ $log->target_type ?? 'N/A' }} #{{ $log->target_id ?? '—' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block mb-1">وقت التنفيذ</span>
                    <span class="font-mono text-slate-800">{{ $log->created_at->format('Y-m-d H:i:s') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block mb-1">عنوان IP</span>
                    <span class="font-mono text-slate-800">{{ $log->ip_address }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block mb-1">بصمة المتصفح (User Agent)</span>
                    <span class="font-mono text-[11px] text-slate-600 truncate block">{{ $log->user_agent ?? 'N/A' }}</span>
                </div>
                @if ($log->reason)
                    <div class="md:col-span-2">
                        <span class="text-slate-400 block mb-1">سبب العملية / ملاحظات إدارية</span>
                        <p class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-slate-700">{{ $log->reason }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Values Diff Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-3">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    <span>القيم السابقة (Old Values)</span>
                </h4>
                <div class="p-4 bg-slate-900 rounded-2xl overflow-x-auto text-left font-mono text-xs text-rose-300">
                    <pre>{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?? 'null' }}</pre>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-3">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>القيم الجديدة (New Values)</span>
                </h4>
                <div class="p-4 bg-slate-900 rounded-2xl overflow-x-auto text-left font-mono text-xs text-emerald-300">
                    <pre>{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?? 'null' }}</pre>
                </div>
            </div>
        </div>
    </main>
</x-layouts.base>
