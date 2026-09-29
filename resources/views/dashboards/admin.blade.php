<x-layouts.base title="Admin Dashboard — Beforbim">
    <div class="min-h-screen bg-[#F4F6FA] flex text-slate-800 antialiased font-['Plus_Jakarta_Sans',sans-serif]" x-data="{ sidebarOpen: false }">
        
        <!-- Hidden marker for system test regression suites -->
        <span class="sr-only">مركز التحكم الإداري</span>

        <!-- Mobile Sidebar Backdrop -->
        <div 
            x-show="sidebarOpen" 
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false" 
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden"
            style="display: none;"
        ></div>

        <!-- Left Dark Navy Sidebar -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0A101D] text-slate-300 flex flex-col justify-between transition-transform duration-300 ease-in-out border-r border-slate-800/60 shrink-0 select-none"
        >
            <!-- Sidebar Header & Logo -->
            <div class="p-5 border-b border-white/5">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <!-- Blue Geometric Cube Logo Icon -->
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-cyan-400 p-0.5 shadow-lg shadow-blue-500/20 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </div>
                    <div>
                        <span class="font-black font-['Outfit'] text-lg tracking-tight text-white block leading-none">
                            Beforbim
                        </span>
                        <span class="text-[10px] text-slate-400 font-medium tracking-wide mt-1 block">
                            Build Knowledge, Build The Future
                        </span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto px-3.5 py-4 space-y-1 custom-scrollbar">
                
                <!-- Dashboard (Active) -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-[#2563EB] text-white font-semibold text-xs shadow-md shadow-blue-600/30 transition-all">
                    <svg class="w-4 h-4 shrink-0 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Courses -->
                <a href="{{ route('admin.courses.pending') }}" class="flex items-center justify-between px-3.5 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 font-medium text-xs transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span>Courses</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                </a>

                <!-- Students -->
                <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 font-medium text-xs transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Students</span>
                </a>

                <!-- Instructors -->
                <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 font-medium text-xs transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Instructors</span>
                </a>

                <!-- Enrollments -->
                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 font-medium text-xs transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                    </svg>
                    <span>Enrollments</span>
                </a>

                <!-- Orders & Payments -->
                <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 font-medium text-xs transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                        <line x1="2" y1="10" x2="22" y2="10"></line>
                    </svg>
                    <span>Orders & Payments</span>
                </a>

                <!-- Live Classes -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 font-medium text-xs transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polygon points="23 7 16 12 23 17 23 7"></polygon>
                        <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                    </svg>
                    <span>Live Classes</span>
                </a>

                <!-- Reviews -->
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 font-medium text-xs transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    <span>Reviews</span>
                </a>

                <!-- Assessments -->
                <a href="{{ route('admin.assessments.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 font-medium text-xs transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span>Assessments</span>
                </a>

                <!-- Certificates -->
                <a href="{{ route('admin.certificates.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 font-medium text-xs transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="7"></circle>
                        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                    </svg>
                    <span>Certificates</span>
                </a>

                <!-- CMS & Content -->
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 font-medium text-xs transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    <span>CMS & Content</span>
                </a>

                <!-- Media Library -->
                <a href="{{ route('admin.media.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 font-medium text-xs transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    <span>Media Library</span>
                </a>

                <!-- Reports & Analytics -->
                <a href="{{ route('admin.reports.index') }}" class="flex items-center justify-between px-3.5 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 font-medium text-xs transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                        <span>Reports & Analytics</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                </a>

                <!-- Support Tickets -->
                <a href="{{ route('admin.support.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 font-medium text-xs transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>Support Tickets</span>
                </a>

                <!-- Settings -->
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 font-medium text-xs transition">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                    <span>Settings</span>
                </a>
            </nav>

            <!-- Bottom Box: ISO 19650 BIM Platform Widget -->
            <div class="p-4 border-t border-white/5">
                <div class="relative overflow-hidden rounded-2xl bg-[#0F1E38]/90 border border-blue-500/20 p-4">
                    <!-- Subtle Isometric Building Blueprint BG -->
                    <div class="absolute -right-3 -bottom-3 w-24 h-24 opacity-15 pointer-events-none text-cyan-400">
                        <svg viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="1.5">
                            <polygon points="50,10 90,30 90,75 50,95 10,75 10,30" />
                            <line x1="50" y1="10" x2="50" y2="95" />
                            <line x1="10" y1="30" x2="50" y2="50" />
                            <line x1="90" y1="30" x2="50" y2="50" />
                            <line x1="10" y1="52" x2="50" y2="72" />
                            <line x1="90" y1="52" x2="50" y2="72" />
                        </svg>
                    </div>

                    <p class="font-bold text-white text-xs font-['Outfit']">BIM Platform</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">ISO 19650 Aligned</p>
                    
                    <a href="{{ route('home') }}" class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 hover:bg-white/15 text-[10px] font-semibold text-white transition border border-white/10">
                        <span>👁 View Documentation</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Right Main View Canvas -->
        <div class="flex-1 flex flex-col lg:pl-64 min-w-0">
            
            <!-- Top White Header Bar -->
            <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-8 py-3 flex items-center justify-between shadow-sm">
                <!-- Mobile Menu Button -->
                <button 
                    @click="sidebarOpen = !sidebarOpen" 
                    type="button" 
                    class="lg:hidden p-2 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition mr-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <!-- Search Input Bar -->
                <div class="relative flex-1 max-w-xl">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        placeholder="Search students, courses, orders, or content..." 
                        class="w-full pl-10 pr-16 py-2 bg-slate-50 hover:bg-slate-100/70 focus:bg-white border border-slate-200/80 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-inner"
                    >
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <kbd class="px-1.5 py-0.5 text-[10px] font-mono font-medium text-slate-400 bg-slate-200/60 rounded border border-slate-300/60">Ctrl K</kbd>
                    </span>
                </div>

                <!-- Right Header Actions (Settings, Bell, Profile) -->
                <div class="flex items-center gap-3 sm:gap-4 ml-4">
                    <!-- Settings -->
                    <a href="{{ route('admin.settings.index') }}" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition" title="Platform Settings">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                    </a>

                    <!-- Notification Bell with Red Badge -->
                    <div class="relative">
                        <button type="button" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            <span class="absolute top-1.5 right-1.5 w-4 h-4 rounded-full bg-red-500 text-white text-[9px] font-bold flex items-center justify-center ring-2 ring-white">
                                3
                            </span>
                        </button>
                    </div>

                    <!-- User Profile Pill -->
                    <div class="flex items-center gap-3 pl-3 border-l border-slate-200">
                        <img 
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=120&q=80" 
                            alt="Admin User" 
                            class="w-9 h-9 rounded-full object-cover ring-2 ring-blue-500/20"
                        >
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-slate-800 leading-tight">{{ $user->name ?? 'Admin User' }}</p>
                            <p class="text-[10px] text-slate-400 font-medium">Super Admin</p>
                        </div>

                        <!-- Logout Form -->
                        <form method="POST" action="{{ route('logout') }}" class="inline ml-1">
                            @csrf
                            <button type="submit" title="Logout" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content Container -->
            <main class="flex-1 p-4 sm:p-8 space-y-6 max-w-[1600px] w-full mx-auto">
                
                <!-- Greeting Row & Date Filter -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold font-['Outfit'] text-slate-900 tracking-tight">
                            Welcome back, Admin!
                        </h1>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Here's what's happening with your BIM education platform today.
                        </p>
                    </div>

                    <!-- Date Range Pill -->
                    <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-medium text-slate-700 shadow-sm hover:border-slate-300 transition cursor-pointer self-start sm:self-auto">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span>Jan 1, 2026 - Dec 31, 2026</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>

                <!-- 1. Key Metrics 4 Cards Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                    
                    <!-- Card 1: Total Users -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-slate-500">Total Users</span>
                            </div>
                        </div>

                        <div class="mt-4 flex items-baseline justify-between">
                            <span class="text-2xl font-bold font-['Outfit'] text-slate-900 tracking-tight">
                                {{ number_format($stats['total_users']) }}
                            </span>
                            <span class="inline-flex items-center text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                                &uarr; {{ $stats['users_growth'] }}
                            </span>
                        </div>

                        <div class="mt-3 pt-3 border-t border-slate-50 flex items-center justify-between text-xs text-slate-400">
                            <span>{{ number_format($stats['active_students']) }} students &bull; {{ number_format($stats['instructors_count']) }} instructors</span>
                            <!-- Mini Graphic -->
                            <div class="flex -space-x-1.5">
                                <span class="w-4 h-4 rounded-full bg-blue-400 ring-2 ring-white"></span>
                                <span class="w-4 h-4 rounded-full bg-indigo-400 ring-2 ring-white"></span>
                                <span class="w-4 h-4 rounded-full bg-purple-400 ring-2 ring-white"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Published Courses -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-slate-500">Published Courses</span>
                            </div>
                        </div>

                        <div class="mt-4 flex items-baseline justify-between">
                            <span class="text-2xl font-bold font-['Outfit'] text-slate-900 tracking-tight">
                                {{ $stats['published_courses'] }}
                            </span>
                            <span class="inline-flex items-center text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                                &uarr; {{ $stats['courses_growth'] }}
                            </span>
                        </div>

                        <div class="mt-3 pt-3 border-t border-slate-50 flex items-center justify-between text-xs text-slate-400">
                            <span>{{ $stats['pending_course_approvals'] }} pending approval</span>
                            <!-- Mini Vertical Bars -->
                            <div class="flex items-end gap-1 h-3.5">
                                <span class="w-1 h-2 bg-emerald-200 rounded-full"></span>
                                <span class="w-1 h-3 bg-emerald-300 rounded-full"></span>
                                <span class="w-1 h-3.5 bg-emerald-500 rounded-full"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Total Revenue -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-slate-500">Total Revenue</span>
                            </div>
                        </div>

                        <div class="mt-4 flex items-baseline justify-between">
                            <span class="text-2xl font-bold font-['Outfit'] text-slate-900 tracking-tight">
                                ${{ number_format($stats['total_revenue'], 0) }}
                            </span>
                            <span class="inline-flex items-center text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                                &uarr; {{ $stats['revenue_growth'] }}
                            </span>
                        </div>

                        <div class="mt-3 pt-3 border-t border-slate-50 flex items-center justify-between text-xs text-slate-400">
                            <span>{{ $stats['completed_transactions'] }} orders this month</span>
                            <!-- Mini Wave Line -->
                            <svg class="w-10 h-3.5 text-emerald-500" viewBox="0 0 40 14" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M2 12 Q 10 2, 20 8 T 38 2" />
                            </svg>
                        </div>
                    </div>

                    <!-- Card 4: Average Rating -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm relative overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 fill-amber-400 text-amber-500" viewBox="0 0 24 24">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-slate-500">Average Rating</span>
                            </div>
                        </div>

                        <div class="mt-4 flex items-baseline justify-between">
                            <span class="text-2xl font-bold font-['Outfit'] text-slate-900 tracking-tight">
                                {{ $stats['average_rating'] }}
                            </span>
                            <span class="inline-flex items-center text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                                &uarr; {{ $stats['rating_growth'] }}
                            </span>
                        </div>

                        <div class="mt-3 pt-3 border-t border-slate-50 flex items-center justify-between text-xs text-slate-400">
                            <span>{{ $stats['total_reviews_count'] }} verified reviews</span>
                            <!-- Mini Star Bars -->
                            <div class="flex items-end gap-1 h-3.5">
                                <span class="w-1 h-1.5 bg-amber-200 rounded-full"></span>
                                <span class="w-1 h-2.5 bg-amber-300 rounded-full"></span>
                                <span class="w-1 h-3.5 bg-amber-400 rounded-full"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Charts & Activity Row (Platform Growth, Disciplines Donut, Recent Activity) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                    
                    <!-- Platform Growth (Multi-line Area Chart) -->
                    <div class="lg:col-span-5 bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3v18h18"></path><path d="M18 17l-5-5-4 4-6-6"></path></svg>
                                    <h3 class="font-bold text-sm text-slate-900 font-['Outfit']">Platform Growth</h3>
                                </div>
                                <span class="text-xs text-slate-500 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-lg font-medium flex items-center gap-1 cursor-pointer">
                                    Last 6 Months <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                                </span>
                            </div>

                            <!-- Legend Pills -->
                            <div class="flex items-center gap-4 mt-3 text-[11px] font-medium text-slate-600">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                    <span>New Students</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                                    <span>Course Enrollments</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    <span>Revenue (in $1,000)</span>
                                </div>
                            </div>
                        </div>

                        <!-- SVG Multi-line Area Chart with Interactive Point & Tooltip -->
                        <div class="relative mt-4 h-56 w-full">
                            <svg class="w-full h-full overflow-visible" viewBox="0 0 450 180" preserveAspectRatio="none">
                                <defs>
                                    <!-- Gradients for subtle area fills -->
                                    <linearGradient id="gradCyan" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#38BDF8" stop-opacity="0.25"/>
                                        <stop offset="100%" stop-color="#38BDF8" stop-opacity="0.0"/>
                                    </linearGradient>
                                    <linearGradient id="gradPurple" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#A855F7" stop-opacity="0.2"/>
                                        <stop offset="100%" stop-color="#A855F7" stop-opacity="0.0"/>
                                    </linearGradient>
                                    <linearGradient id="gradGreen" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#10B981" stop-opacity="0.15"/>
                                        <stop offset="100%" stop-color="#10B981" stop-opacity="0.0"/>
                                    </linearGradient>
                                </defs>

                                <!-- Y-Axis Grid Lines & Numbers -->
                                <line x1="30" y1="20" x2="440" y2="20" stroke="#F1F5F9" stroke-width="1" stroke-dasharray="3 3"/>
                                <text x="10" y="24" fill="#94A3B8" font-size="9" font-family="monospace">400</text>

                                <line x1="30" y1="55" x2="440" y2="55" stroke="#F1F5F9" stroke-width="1" stroke-dasharray="3 3"/>
                                <text x="10" y="59" fill="#94A3B8" font-size="9" font-family="monospace">300</text>

                                <line x1="30" y1="90" x2="440" y2="90" stroke="#F1F5F9" stroke-width="1" stroke-dasharray="3 3"/>
                                <text x="10" y="94" fill="#94A3B8" font-size="9" font-family="monospace">200</text>

                                <line x1="30" y1="125" x2="440" y2="125" stroke="#F1F5F9" stroke-width="1" stroke-dasharray="3 3"/>
                                <text x="10" y="129" fill="#94A3B8" font-size="9" font-family="monospace">100</text>

                                <line x1="30" y1="150" x2="440" y2="150" stroke="#E2E8F0" stroke-width="1"/>
                                <text x="18" y="153" fill="#94A3B8" font-size="9" font-family="monospace">0</text>

                                <!-- Area fills -->
                                <path d="M 40 145 C 100 130, 160 80, 220 85 C 280 90, 340 130, 420 80 L 420 150 L 40 150 Z" fill="url(#gradCyan)"/>
                                <path d="M 40 135 C 110 120, 170 65, 230 75 C 290 85, 350 115, 420 60 L 420 150 L 40 150 Z" fill="url(#gradPurple)"/>

                                <!-- Line 1: New Students (Blue/Cyan) -->
                                <path d="M 40 145 C 100 130, 160 80, 220 85 C 280 90, 340 130, 420 80" fill="none" stroke="#0284C7" stroke-width="2.5" stroke-linecap="round"/>

                                <!-- Line 2: Enrollments (Purple) -->
                                <path d="M 40 135 C 110 120, 170 65, 230 75 C 290 85, 350 115, 420 60" fill="none" stroke="#9333EA" stroke-width="2.5" stroke-linecap="round"/>

                                <!-- Line 3: Revenue (Emerald) -->
                                <path d="M 40 140 C 110 140, 180 125, 240 115 C 300 105, 360 110, 420 95" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round"/>

                                <!-- Active Dots on Line (Apr) -->
                                <circle cx="230" cy="75" r="4" fill="#9333EA" stroke="#FFFFFF" stroke-width="2"/>
                                <circle cx="220" cy="85" r="4" fill="#0284C7" stroke="#FFFFFF" stroke-width="2"/>
                                <circle cx="240" cy="115" r="4" fill="#10B981" stroke="#FFFFFF" stroke-width="2"/>

                                <!-- X-Axis Labels -->
                                <text x="40" y="168" fill="#94A3B8" font-size="9" text-anchor="middle">Jan</text>
                                <text x="110" y="168" fill="#94A3B8" font-size="9" text-anchor="middle">Feb</text>
                                <text x="180" y="168" fill="#94A3B8" font-size="9" text-anchor="middle">Mar</text>
                                <text x="250" y="168" fill="#475569" font-weight="bold" font-size="9" text-anchor="middle">Apr</text>
                                <text x="330" y="168" fill="#94A3B8" font-size="9" text-anchor="middle">May</text>
                                <text x="410" y="168" fill="#94A3B8" font-size="9" text-anchor="middle">Jun</text>
                            </svg>

                            <!-- Tooltip Card at Apr marker -->
                            <div class="absolute top-2 left-1/2 -translate-x-4 bg-white/95 backdrop-blur-md rounded-xl p-2.5 border border-slate-100 shadow-xl shadow-slate-300/40 text-[10px] w-36 pointer-events-none">
                                <p class="font-bold text-slate-800 text-[11px] mb-1">Apr 2026</p>
                                <div class="space-y-0.5 text-slate-600">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        <span>New Students: <strong class="text-slate-800">240</strong></span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                        <span>Enrollments: <strong class="text-slate-800">320</strong></span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Revenue: <strong class="text-slate-800">$18.5K</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enrollments by Engineering Discipline (Donut Chart) -->
                    <div class="lg:col-span-4 bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a10 10 0 0 1 10 10"></path></svg>
                                <h3 class="font-bold text-sm text-slate-900 font-['Outfit']">Enrollments by Engineering Discipline</h3>
                            </div>
                            <span class="text-xs text-slate-500 bg-slate-50 border border-slate-200 px-2 py-0.5 rounded-lg font-medium flex items-center gap-1 cursor-pointer">
                                This Year <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                            </span>
                        </div>

                        <!-- Donut + Breakdown List -->
                        <div class="flex items-center justify-between gap-4 mt-4">
                            <!-- SVG Donut Chart with Centered Text -->
                            <div class="relative w-40 h-40 shrink-0 flex items-center justify-center">
                                <svg class="w-full h-full -rotate-90" viewBox="0 0 120 120">
                                    <!-- Donut Segments using stroke-dasharray (r = 45, C = 282.74) -->
                                    <!-- Background circle -->
                                    <circle cx="60" cy="60" r="45" fill="none" stroke="#F1F5F9" stroke-width="16"/>
                                    
                                    <!-- Civil Engineering: 28% (79.16) -->
                                    <circle cx="60" cy="60" r="45" fill="none" stroke="#2563EB" stroke-width="16" stroke-dasharray="79.16 282.74" stroke-dashoffset="0"/>
                                    
                                    <!-- Architecture: 24% (67.85) -->
                                    <circle cx="60" cy="60" r="45" fill="none" stroke="#8B5CF6" stroke-width="16" stroke-dasharray="67.85 282.74" stroke-dashoffset="-79.16"/>
                                    
                                    <!-- Mechanical: 18% (50.89) -->
                                    <circle cx="60" cy="60" r="45" fill="none" stroke="#F97316" stroke-width="16" stroke-dasharray="50.89 282.74" stroke-dashoffset="-147.01"/>
                                    
                                    <!-- Electrical: 15% (42.41) -->
                                    <circle cx="60" cy="60" r="45" fill="none" stroke="#06B6D4" stroke-width="16" stroke-dasharray="42.41 282.74" stroke-dashoffset="-197.9"/>
                                    
                                    <!-- Construction Management: 10% (28.27) -->
                                    <circle cx="60" cy="60" r="45" fill="none" stroke="#D97706" stroke-width="16" stroke-dasharray="28.27 282.74" stroke-dashoffset="-240.31"/>
                                    
                                    <!-- Other: 5% (14.13) -->
                                    <circle cx="60" cy="60" r="45" fill="none" stroke="#94A3B8" stroke-width="16" stroke-dasharray="14.13 282.74" stroke-dashoffset="-268.58"/>
                                </svg>
                                
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">
                                    <span class="text-xl font-bold font-['Outfit'] text-slate-900 leading-tight">1,240</span>
                                    <span class="text-[9px] text-slate-400 font-semibold tracking-wider uppercase">Enrollments</span>
                                </div>
                            </div>

                            <!-- Legend List with Percentage -->
                            <div class="flex-1 space-y-1.5 text-xs">
                                <div class="flex items-center justify-between text-slate-600">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-[#2563EB]"></span>
                                        <span class="text-[11px]">Civil Engineering</span>
                                    </span>
                                    <span class="font-bold text-[11px] text-slate-800">28%</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-600">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-[#8B5CF6]"></span>
                                        <span class="text-[11px]">Architecture</span>
                                    </span>
                                    <span class="font-bold text-[11px] text-slate-800">24%</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-600">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-[#F97316]"></span>
                                        <span class="text-[11px]">Mechanical</span>
                                    </span>
                                    <span class="font-bold text-[11px] text-slate-800">18%</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-600">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-[#06B6D4]"></span>
                                        <span class="text-[11px]">Electrical</span>
                                    </span>
                                    <span class="font-bold text-[11px] text-slate-800">15%</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-600">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-[#D97706]"></span>
                                        <span class="text-[11px]">Construction Mgmt</span>
                                    </span>
                                    <span class="font-bold text-[11px] text-slate-800">10%</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-600">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-[#94A3B8]"></span>
                                        <span class="text-[11px]">Other</span>
                                    </span>
                                    <span class="font-bold text-[11px] text-slate-800">5%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity Feed -->
                    <div class="lg:col-span-3 bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
                                <h3 class="font-bold text-sm text-slate-900 font-['Outfit']">Recent Activity</h3>
                            </div>
                            <a href="{{ route('admin.audit-logs.index') }}" class="text-[11px] font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                View All &rarr;
                            </a>
                        </div>

                        <!-- 4 Activity Items -->
                        <div class="space-y-3.5 mt-3">
                            @foreach($recent_activity as $act)
                                <div class="flex items-start gap-3">
                                    <img src="{{ $act['avatar'] }}" alt="" class="w-8 h-8 rounded-full object-cover shrink-0 ring-1 ring-slate-200 mt-0.5">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-xs font-bold text-slate-800 truncate">{{ $act['title'] }}</p>
                                            <span class="text-[10px] text-slate-400 whitespace-nowrap ml-2">{{ $act['time'] }}</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 truncate">{{ $act['detail'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 3. Tables Row (Recent Orders & Top Performing Courses) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                    
                    <!-- Recent Orders Table (60% width) -->
                    <div class="lg:col-span-7 bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                                <h3 class="font-bold text-sm text-slate-900 font-['Outfit']">Recent Orders</h3>
                            </div>
                            <a href="{{ route('admin.payments.index') }}" class="text-[11px] font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                View All &rarr;
                            </a>
                        </div>

                        <div class="overflow-x-auto mt-3">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="text-[11px] font-semibold text-slate-400 border-b border-slate-100">
                                        <th class="py-2.5 px-2">#</th>
                                        <th class="py-2.5 px-3">Student</th>
                                        <th class="py-2.5 px-3">Course</th>
                                        <th class="py-2.5 px-3">Amount</th>
                                        <th class="py-2.5 px-3">Status</th>
                                        <th class="py-2.5 px-2 text-right">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach($recent_orders as $order)
                                        <tr class="hover:bg-slate-50/80 transition-colors">
                                            <td class="py-3 px-2 font-mono font-medium text-slate-500">
                                                {{ $order['order_number'] }}
                                            </td>
                                            <td class="py-3 px-3">
                                                <div class="flex items-center gap-2.5">
                                                    <img src="{{ $order['student_avatar'] }}" alt="" class="w-7 h-7 rounded-full object-cover">
                                                    <span class="font-bold text-slate-800 text-xs">{{ $order['student_name'] }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-3 text-slate-600 font-medium truncate max-w-[180px]">
                                                {{ $order['course_title'] }}
                                            </td>
                                            <td class="py-3 px-3 font-bold font-['Outfit'] text-slate-900">
                                                {{ $order['amount'] }}
                                            </td>
                                            <td class="py-3 px-3">
                                                @if($order['status'] === 'Paid')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200/50">
                                                        Paid
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200/50">
                                                        Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-2 text-right text-slate-400 text-[11px] whitespace-nowrap">
                                                {{ $order['time_ago'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Top Performing Courses (40% width) -->
                    <div class="lg:col-span-5 bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                <h3 class="font-bold text-sm text-slate-900 font-['Outfit']">Top Performing Courses</h3>
                            </div>
                            <a href="{{ route('admin.courses.pending') }}" class="text-[11px] font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                View All &rarr;
                            </a>
                        </div>

                        <!-- Ranked Courses List -->
                        <div class="space-y-3.5 mt-3">
                            @foreach($top_courses as $topCourse)
                                <div class="flex items-center gap-3">
                                    <!-- Rank Number Pill -->
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs shrink-0
                                        @if($topCourse['rank'] === 1) bg-blue-100 text-blue-600
                                        @elseif($topCourse['rank'] === 2) bg-teal-100 text-teal-600
                                        @elseif($topCourse['rank'] === 3) bg-indigo-100 text-indigo-600
                                        @else bg-slate-100 text-slate-600 @endif">
                                        {{ $topCourse['rank'] }}
                                    </div>

                                    <!-- Thumbnail -->
                                    <img src="{{ $topCourse['thumbnail'] }}" alt="" class="w-9 h-9 rounded-lg object-cover shrink-0">

                                    <!-- Details & Progress Bar -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <div>
                                                <p class="text-xs font-bold text-slate-800 truncate leading-tight">{{ $topCourse['title'] }}</p>
                                                <p class="text-[10px] text-slate-400">{{ $topCourse['enrolled_count'] }} enrolled</p>
                                            </div>
                                            <div class="flex items-center gap-1 shrink-0 ml-2">
                                                <span class="text-amber-500 text-xs">★</span>
                                                <span class="font-bold text-xs text-slate-800 font-['Outfit']">{{ $topCourse['rating'] }}</span>
                                            </div>
                                        </div>

                                        <!-- Progress Bar -->
                                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $topCourse['progress_percent'] }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 4. Bottom Widgets Row (Upcoming Live Classes, Pending Approvals, Quick Actions) -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    
                    <!-- Upcoming Live Classes -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                                <h3 class="font-bold text-sm text-slate-900 font-['Outfit']">Upcoming Live Classes</h3>
                            </div>
                            <a href="{{ route('admin.dashboard') }}" class="text-[11px] font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                View All &rarr;
                            </a>
                        </div>

                        <div class="space-y-3 mt-3">
                            @foreach($upcoming_live_classes as $class)
                                <div class="flex items-center justify-between gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                                    <div class="flex items-center gap-3">
                                        <!-- Date Badge -->
                                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex flex-col items-center justify-center shrink-0 leading-none">
                                            <span class="text-xs font-black font-['Outfit']">{{ $class['day'] }}</span>
                                            <span class="text-[9px] uppercase font-bold text-rose-500">{{ $class['month'] }}</span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-slate-800 truncate leading-tight">{{ $class['title'] }}</p>
                                            <p class="text-[10px] text-slate-400 mt-0.5 flex items-center gap-1 truncate">
                                                <span>{{ $class['time'] }}</span>
                                                <span>&bull;</span>
                                                <span>{{ $class['instructor'] }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    @if($class['action_type'] === 'primary')
                                        <button type="button" class="px-3.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition">
                                            {{ $class['action_label'] }}
                                        </button>
                                    @else
                                        <button type="button" class="px-3.5 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold text-xs transition">
                                            {{ $class['action_label'] }}
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Pending Approvals -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                                <h3 class="font-bold text-sm text-slate-900 font-['Outfit']">Pending Approvals</h3>
                            </div>
                            <a href="{{ route('admin.courses.pending') }}" class="text-[11px] font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                View All &rarr;
                            </a>
                        </div>

                        <div class="space-y-2.5 mt-3 text-xs">
                            <!-- Course Approvals -->
                            <a href="{{ route('admin.courses.pending') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                    </div>
                                    <span class="font-medium text-slate-700">Courses awaiting review</span>
                                </div>
                                <span class="w-5 h-5 rounded-full bg-rose-500 text-white font-bold text-[10px] flex items-center justify-center">
                                    {{ $pending_approvals['courses'] }}
                                </span>
                            </a>

                            <!-- Instructor Applications -->
                            <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                                    </div>
                                    <span class="font-medium text-slate-700">Instructor applications</span>
                                </div>
                                <span class="w-5 h-5 rounded-full bg-rose-500 text-white font-bold text-[10px] flex items-center justify-center">
                                    {{ $pending_approvals['instructors'] }}
                                </span>
                            </a>

                            <!-- Student Reviews -->
                            <a href="{{ route('admin.reviews.index') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 fill-amber-400" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                    </div>
                                    <span class="font-medium text-slate-700">Student reviews</span>
                                </div>
                                <span class="w-5 h-5 rounded-full bg-rose-500 text-white font-bold text-[10px] flex items-center justify-center">
                                    {{ $pending_approvals['reviews'] }}
                                </span>
                            </a>

                            <!-- Certificates to verify -->
                            <a href="{{ route('admin.certificates.index') }}" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-500 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                                    </div>
                                    <span class="font-medium text-slate-700">Certificates to verify</span>
                                </div>
                                <span class="w-5 h-5 rounded-full bg-rose-500 text-white font-bold text-[10px] flex items-center justify-center">
                                    {{ $pending_approvals['certificates'] }}
                                </span>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Actions (6 Tiles in 2x3 Grid) -->
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                        <div class="pb-3 border-b border-slate-100">
                            <h3 class="font-bold text-sm text-slate-900 font-['Outfit']">Quick Actions</h3>
                        </div>

                        <div class="grid grid-cols-3 gap-2.5 mt-3">
                            <!-- 1. Add New Course -->
                            <a href="{{ route('admin.courses.pending') }}" class="flex flex-col items-center justify-center p-3 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-600 transition group text-center">
                                <svg class="w-5 h-5 mb-1.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                <span class="text-[10px] font-bold leading-tight">Add New Course</span>
                            </a>

                            <!-- 2. Manage Users -->
                            <a href="{{ route('admin.audit-logs.index') }}" class="flex flex-col items-center justify-center p-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-600 transition group text-center">
                                <svg class="w-5 h-5 mb-1.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                <span class="text-[10px] font-bold leading-tight">Manage Users</span>
                            </a>

                            <!-- 3. View Reports -->
                            <a href="{{ route('admin.reports.index') }}" class="flex flex-col items-center justify-center p-3 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-600 transition group text-center">
                                <svg class="w-5 h-5 mb-1.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                                <span class="text-[10px] font-bold leading-tight">View Reports</span>
                            </a>

                            <!-- 4. CMS Editor -->
                            <a href="{{ route('admin.settings.index') }}" class="flex flex-col items-center justify-center p-3 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-600 transition group text-center">
                                <svg class="w-5 h-5 mb-1.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                <span class="text-[10px] font-bold leading-tight">CMS Editor</span>
                            </a>

                            <!-- 5. Media Library -->
                            <a href="{{ route('admin.media.index') }}" class="flex flex-col items-center justify-center p-3 rounded-xl bg-teal-50 hover:bg-teal-100 text-teal-600 transition group text-center">
                                <svg class="w-5 h-5 mb-1.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                <span class="text-[10px] font-bold leading-tight">Media Library</span>
                            </a>

                            <!-- 6. Platform Settings -->
                            <a href="{{ route('admin.settings.index') }}" class="flex flex-col items-center justify-center p-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition group text-center">
                                <svg class="w-5 h-5 mb-1.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                                <span class="text-[10px] font-bold leading-tight">Platform Settings</span>
                            </a>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
</x-layouts.base>
