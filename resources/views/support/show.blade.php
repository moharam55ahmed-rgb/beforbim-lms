<x-layouts.base title="Ticket #{{ $ticket->ticket_number }} — Beforbim Academy">
    <!-- Header -->
    <header class="bg-white/95 dark:bg-[#071A36]/95 backdrop-blur-md text-slate-800 dark:text-white border-b border-slate-200 dark:border-white/10 sticky top-0 z-30 shadow-sm transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('support.index') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/branding/logo.png') }}" alt="Beforbim" class="w-8 h-8 object-contain">
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm sm:text-base font-black font-['Outfit'] text-slate-900 dark:text-white">Ticket #{{ $ticket->ticket_number }}</h1>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#D4AF37]/20 text-[#B38F24] dark:text-[#F3D98B] border border-[#D4AF37]/40">{{ $ticket->category }}</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate max-w-sm">{{ $ticket->subject }}</p>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('support.index') }}">
                    <x-button variant="outline" size="sm" class="border-slate-300 dark:border-white/20 text-slate-700 dark:text-slate-200">All Tickets</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6">
        @if (session('status'))
            <x-alert type="success" :message="session('status')" />
        @endif

        <!-- Ticket Summary Banner -->
        <div class="bg-white dark:bg-[#071A36]/80 rounded-3xl p-6 border border-slate-200 dark:border-white/10 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 transition-colors">
            <div>
                <span class="text-xs text-slate-400 block">Current Ticket Status</span>
                <div class="flex items-center gap-2 mt-1.5">
                    @if ($ticket->status === 'OPEN')
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-blue-100 dark:bg-blue-500/20 text-blue-800 dark:text-blue-300">Open — Being Assigned</span>
                    @elseif ($ticket->status === 'WAITING_FOR_STUDENT')
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300">Replied — Awaiting Your Response</span>
                    @elseif ($ticket->status === 'RESOLVED')
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-400">Resolved Successfully</span>
                    @elseif ($ticket->status === 'CLOSED')
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-slate-400">Ticket Closed</span>
                    @else
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-purple-100 dark:bg-purple-500/20 text-purple-800 dark:text-purple-300">In Progress</span>
                    @endif
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">Opened: {{ $ticket->created_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>

            @if ($ticket->course)
                <div class="text-left">
                    <span class="text-xs text-slate-400 block">Related Course</span>
                    <span class="text-xs font-bold text-slate-900 dark:text-white block mt-1">{{ $ticket->course->title_en ?: $ticket->course->title_ar }}</span>
                </div>
            @endif
        </div>

        <!-- Messages Thread -->
        <div class="space-y-4">
            @foreach ($ticket->messages as $msg)
                <div class="p-6 rounded-3xl border {{ $msg->is_staff_reply ? 'bg-amber-50/40 dark:bg-[#D4AF37]/5 border-[#D4AF37]/30' : 'bg-white dark:bg-[#071A36]/80 border-slate-200 dark:border-white/10' }} shadow-sm space-y-3 transition-colors">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-white/10 pb-2.5">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $msg->is_staff_reply ? 'bg-[#071A36] dark:bg-[#D4AF37] text-[#D4AF37] dark:text-[#040E1E]' : 'bg-slate-200 dark:bg-white/10 text-slate-700 dark:text-slate-300' }}">
                                {{ substr($msg->user?->name ?? 'U', 0, 1) }}
                            </span>
                            <div>
                                <span class="text-xs font-bold text-slate-800 dark:text-white">{{ $msg->user?->name ?? 'User' }}</span>
                                @if ($msg->is_staff_reply)
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#D4AF37]/20 text-[#B38F24] dark:text-[#F3D98B] ml-1.5">Support Team</span>
                                @endif
                            </div>
                        </div>
                        <span class="text-[11px] font-mono text-slate-400">{{ $msg->created_at->diffForHumans() }}</span>
                    </div>

                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">{{ $msg->message }}</p>

                    @if ($msg->attachments->isNotEmpty())
                        <div class="pt-2 border-t border-slate-100 dark:border-white/10 flex flex-wrap gap-2">
                            @foreach ($msg->attachments as $att)
                                <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 hover:border-[#D4AF37] text-xs text-slate-700 dark:text-slate-300 transition">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <span class="truncate max-w-[180px]">{{ $att->file_name }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Student Reply Form (if not closed) -->
        @if ($ticket->status !== 'CLOSED')
            <div class="bg-white dark:bg-[#071A36]/80 rounded-3xl p-6 border border-slate-200 dark:border-white/10 shadow-sm space-y-4 transition-colors">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white font-['Outfit']">Post a Reply</h3>
                <form action="{{ route('support.reply', $ticket) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <textarea name="message" rows="4" placeholder="Type your reply or additional information here..." required class="w-full px-4 py-3 rounded-2xl border border-slate-300 dark:border-white/20 bg-white dark:bg-[#071A36]/80 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-[#D4AF37] focus:outline-none placeholder:text-slate-400"></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <input type="file" name="attachment" class="text-xs text-slate-500 dark:text-slate-400 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 dark:file:bg-white/10 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-200 dark:hover:file:bg-white/20 file:cursor-pointer file:transition">
                        </div>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#D4AF37] text-[#040E1E] hover:bg-[#F3D98B] text-xs font-bold shadow-lg shadow-[#D4AF37]/20 transition">
                            Send Reply &rarr;
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="p-5 rounded-2xl bg-slate-100 dark:bg-white/5 text-center text-xs text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-white/10">
                This ticket is closed and no longer accepts replies. If you need further assistance, please <a href="{{ route('support.create') }}" class="text-[#D4AF37] font-bold hover:underline">open a new ticket</a>.
            </div>
        @endif
    </main>
</x-layouts.base>
