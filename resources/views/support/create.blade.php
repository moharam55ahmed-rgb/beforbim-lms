<x-layouts.base title="New Support Ticket — Beforbim Academy">
    <!-- Header -->
    <header class="bg-white/95 dark:bg-[#071A36]/95 backdrop-blur-md text-slate-800 dark:text-white border-b border-slate-200 dark:border-white/10 sticky top-0 z-30 shadow-sm transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('support.index') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/branding/logo.png') }}" alt="Beforbim" class="w-8 h-8 object-contain">
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-sm sm:text-base font-black font-['Outfit'] text-slate-900 dark:text-white">New Support Ticket</h1>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#D4AF37]/20 text-[#B38F24] dark:text-[#F3D98B] border border-[#D4AF37]/40">Engineering Help</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Submit your query with screenshots or supporting documentation</p>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('support.index') }}">
                    <x-button variant="outline" size="sm" class="border-slate-300 dark:border-white/20 text-slate-700 dark:text-slate-200">My Tickets</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6">
        @if ($errors->any())
            <x-alert type="danger" message="Please review the form fields and correct any errors." />
        @endif

        <div class="bg-white dark:bg-[#071A36]/80 rounded-3xl p-8 border border-slate-200 dark:border-white/10 shadow-sm transition-colors">
            <form action="{{ route('support.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Ticket Subject <span class="text-rose-500">*</span></label>
                    <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Brief, clear description of your issue or question" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-white/20 bg-white dark:bg-[#071A36]/80 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-[#D4AF37] focus:outline-none placeholder:text-slate-400 dark:placeholder:text-slate-500">
                    @error('subject') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Issue Category <span class="text-rose-500">*</span></label>
                        <select name="category" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-white/20 bg-white dark:bg-[#071A36]/80 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-[#D4AF37] focus:outline-none">
                            <option value="TECHNICAL">Technical & Platform Support</option>
                            <option value="ACADEMIC_CONTENT">Academic Course Content</option>
                            <option value="BILLING">Billing & Payments</option>
                            <option value="CERTIFICATE">Certificate Issuance</option>
                            <option value="OTHER">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Priority Level</label>
                        <select name="priority" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-white/20 bg-white dark:bg-[#071A36]/80 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-[#D4AF37] focus:outline-none">
                            <option value="LOW">Low</option>
                            <option value="NORMAL" selected>Normal</option>
                            <option value="HIGH">High</option>
                            <option value="URGENT">Urgent</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Related Course (Optional)</label>
                    <select name="course_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-white/20 bg-white dark:bg-[#071A36]/80 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-[#D4AF37] focus:outline-none">
                        <option value="">Not related to a specific course</option>
                        @foreach ($enrolledCourses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title_en ?: $course->title_ar ?: $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Detailed Description <span class="text-rose-500">*</span></label>
                    <textarea name="message" rows="5" placeholder="Describe your issue in detail — what happened, steps you took, any error messages..." required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-white/20 bg-white dark:bg-[#071A36]/80 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-[#D4AF37] focus:outline-none placeholder:text-slate-400 dark:placeholder:text-slate-500">{{ old('message') }}</textarea>
                    @error('message') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Attach File or Screenshot (Max: 10 MB)</label>
                    <input type="file" name="attachment" class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-900 dark:file:bg-[#D4AF37] file:text-white dark:file:text-[#040E1E] hover:file:bg-slate-700 dark:hover:file:bg-[#F3D98B] file:cursor-pointer file:transition">
                    @error('attachment') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-white/10 flex items-center justify-end gap-3">
                    <a href="{{ route('support.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 dark:border-white/20 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 text-sm font-bold transition">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#D4AF37] text-[#040E1E] hover:bg-[#F3D98B] text-sm font-bold shadow-lg shadow-[#D4AF37]/20 transition">
                        <span>Submit Support Ticket</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-layouts.base>
