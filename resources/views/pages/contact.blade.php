<x-layouts.base 
    :title="'Contact Engineering Advisors & Support — ' . $cms->get('site_name_en', 'Beforbim')"
    :description="'Connect with Beforbim academic advisors, enterprise corporate training coordinators, and support in New Cairo, Egypt.'"
>
    <x-public-header />

    <!-- Hero Header -->
    <section class="relative bg-gradient-to-b from-slate-950 via-[#071A36] to-[#040E1E] text-white py-16 lg:py-20 overflow-hidden border-b border-slate-200 dark:border-white/10">
        <div class="absolute inset-0 bg-blueprint-navy opacity-30 pointer-events-none"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#D4AF37]/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-[#D4AF37]/15 text-[#F3D98B] border border-[#D4AF37]/30">
                <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-pulse"></span>
                <span>Beforbim Engineering Advisory Desk • Cairo, Egypt</span>
            </div>

            <h1 class="text-3xl sm:text-5xl font-black font-['Outfit'] text-white tracking-tight">
                Connect With Our <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#F3D98B] via-[#D4AF37] to-amber-200">BIM Consultants</span>
            </h1>

            <p class="text-xs sm:text-sm text-slate-300 max-w-xl mx-auto leading-relaxed font-light">
                Have questions regarding syllabus accreditation, diploma schedules, or corporate on-site team training? Our senior engineers are ready to assist.
            </p>
        </div>
    </section>

    <!-- Main Content (Light & Dark Responsive) -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 flex-1 w-full space-y-12">
        @if(session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            <!-- Form Left (7 Cols) -->
            <div class="lg:col-span-7 rounded-3xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 p-8 sm:p-10 shadow-sm dark:shadow-2xl backdrop-blur-md space-y-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center justify-between">
                        <span>Send an Engineering Inquiry</span>
                        <span class="sr-only">أرسل لنا استفسارك</span>
                    </h3>
                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 font-light">
                        Our technical team will review your submission and respond within one business day.
                    </p>
                </div>

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Full Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                value="{{ old('name') }}" 
                                placeholder="Eng. Ahmed Hassan" 
                                required 
                                class="w-full bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37]"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Work Email</label>
                            <input 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                placeholder="engineer@domain.com" 
                                required 
                                class="w-full bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37]"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Phone / WhatsApp</label>
                            <input 
                                type="text" 
                                name="phone" 
                                value="{{ old('phone') }}" 
                                placeholder="+20 100 000 0000" 
                                class="w-full bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37]"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Subject</label>
                            <input 
                                type="text" 
                                name="subject" 
                                value="{{ old('subject') }}" 
                                placeholder="Inquiry about BIM Diploma or Corporate Training" 
                                required 
                                class="w-full bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37]"
                            >
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Inquiry Details</label>
                        <textarea 
                            name="message" 
                            rows="5" 
                            required 
                            placeholder="Please provide details regarding your required learning path, current tools used, or team size..." 
                            class="w-full bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37]"
                        >{{ old('message') }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="w-full sm:w-auto px-8 py-3 rounded-xl text-xs font-bold bg-[#D4AF37] hover:bg-[#C59B27] text-[#040E1E] shadow-sm transition flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <span>Submit Technical Inquiry</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Contact Info Right (5 Cols) -->
            <div class="lg:col-span-5 space-y-6">
                <div class="rounded-3xl bg-slate-900 dark:bg-[#071A36]/90 border border-slate-800 dark:border-[#D4AF37]/30 p-8 shadow-xl backdrop-blur-md space-y-6 text-white">
                    <h3 class="text-lg font-bold text-white font-['Outfit'] border-b border-white/10 pb-3">
                        Headquarters & Direct Channels
                    </h3>

                    <div class="space-y-5 text-xs text-slate-300">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-2xl bg-white/10 dark:bg-[#040E1E] text-[#D4AF37] border border-white/10 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase tracking-wider">Official Email</span>
                                <span class="font-bold text-white">{{ $cms->get('contact_email', 'support@beforbim.com') }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-2xl bg-white/10 dark:bg-[#040E1E] text-[#D4AF37] border border-white/10 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase tracking-wider">Phone & WhatsApp</span>
                                <span class="font-bold text-white font-mono">{{ $cms->get('contact_phone', '+20 2 2456 7890') }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-2xl bg-white/10 dark:bg-[#040E1E] text-[#D4AF37] border border-white/10 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase tracking-wider">Cairo Headquarters</span>
                                <span class="text-slate-300 leading-relaxed block">{{ $cms->get('contact_address', 'Beforbim Engineering Center, New Cairo, Cairo, Egypt') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Corporate Training Note -->
                <div class="rounded-3xl bg-white dark:bg-[#071A36]/70 border border-slate-200 dark:border-white/10 p-6 shadow-sm dark:shadow-xl backdrop-blur-md space-y-2">
                    <h4 class="font-bold text-sm text-slate-900 dark:text-white font-['Outfit']">Engineering Enterprise & Firm Training</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-light">
                        We deliver customized on-site BIM transformation workshops for architectural design offices and general contractors across Egypt and the MENA region.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <x-public-footer />
</x-layouts.base>
