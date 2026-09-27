<x-layouts.base title="Support & Help Center — Beforbim Academy">
    <!-- Header -->
    <header class="bg-white/95 dark:bg-[#071A36]/95 backdrop-blur-md text-slate-800 dark:text-white border-b border-slate-200 dark:border-white/10 sticky top-0 z-30 shadow-sm transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/branding/logo.png') }}" alt="Beforbim" class="w-8 h-8 object-contain">
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm sm:text-base font-black font-['Outfit'] text-slate-900 dark:text-white">Engineering Support Center</h1>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#D4AF37]/20 text-[#B38F24] dark:text-[#F3D98B] border border-[#D4AF37]/40">Student Help</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Track your technical and academic support requests</p>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('support.create') }}">
                    <x-button variant="gold" size="sm">+ New Support Ticket</x-button>
                </a>
                <a href="{{ route('student.dashboard') }}" class="hidden sm:inline-block">
                    <x-button variant="outline" size="sm" class="border-slate-300 dark:border-white/20 text-slate-700 dark:text-slate-200">My Dashboard</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6">
        @if (session('status'))
            <x-alert type="success" :message="session('status')" />
        @endif

        <div class="bg-white dark:bg-[#071A36]/80 rounded-3xl border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden transition-colors">
            <div class="p-6 border-b border-slate-100 dark:border-white/10 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white font-['Outfit']">My Support Tickets</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Track instructor and support team responses at any time</p>
                </div>
                <span class="text-xs font-mono text-slate-400">{{ $tickets->total() }} {{ Str::plural('ticket', $tickets->total()) }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-[#040E1E] text-slate-500 dark:text-slate-400 font-bold border-y border-slate-200 dark:border-white/10">
                        <tr>
                            <th class="py-3 px-4">Ticket #</th>
                            <th class="py-3 px-4">Subject</th>
                            <th class="py-3 px-4">Category</th>
                            <th class="py-3 px-4">Related Course</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Last Updated</th>
                            <th class="py-3 px-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse ($tickets as $ticket)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-900 dark:text-white">{{ $ticket->ticket_number }}</td>
                                <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-slate-200">{{ $ticket->subject }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-white/10 text-slate-700 dark:text-slate-300">
                                        {{ $ticket->category }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 dark:text-slate-400">{{ $ticket->course?->title_en ?: ($ticket->course?->title_ar ?? 'General') }}</td>
                                <td class="py-3.5 px-4">
                                    @if ($ticket->status === 'OPEN')
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 dark:bg-blue-500/20 text-blue-800 dark:text-blue-300">Open</span>
                                    @elseif ($ticket->status === 'WAITING_FOR_STUDENT')
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300">Awaiting Your Reply</span>
                                    @elseif ($ticket->status === 'RESOLVED')
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-400">Resolved</span>
                                    @elseif ($ticket->status === 'CLOSED')
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-slate-400">Closed</span>
                                    @else
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 dark:bg-purple-500/20 text-purple-800 dark:text-purple-300">In Progress</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 font-mono text-slate-400">{{ $ticket->updated_at->diffForHumans() }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    <a href="{{ route('support.show', $ticket) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-900 dark:bg-[#D4AF37] text-white dark:text-[#040E1E] hover:bg-slate-700 dark:hover:bg-[#F3D98B] text-xs font-bold transition">
                                        View Ticket &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center">
                                    <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-white/5 text-[#D4AF37] flex items-center justify-center mx-auto mb-4 border border-slate-200 dark:border-white/10">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200">No open support tickets</p>
                                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Having a technical issue or question about course content? Our engineering support team is here to help.</p>
                                    <div class="mt-5">
                                        <a href="{{ route('support.create') }}">
                                            <x-button variant="gold" size="sm">+ Create New Support Ticket</x-button>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100 dark:border-white/10">
                {{ $tickets->links() }}
            </div>
        </div>
    </main>
</x-layouts.base>
