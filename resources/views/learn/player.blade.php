<x-layouts.base :title="$activeLesson->title_ar . ' — ' . $course->title_ar">
    <div class="min-h-screen flex flex-col bg-[#071A36] text-white" x-data="{
        activeTab: 'overview',
        sidebarOpen: true,
        isCompleted: {{ ($currentProgress && $currentProgress->is_completed) ? 'true' : 'false' }},
        progressPercentage: {{ $progressData['percentage'] ?? 0 }},
        async toggleCompletion() {
            try {
                const res = await fetch('{{ route('learn.toggle_complete', $activeLesson->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                if (res.ok) {
                    const data = await res.json();
                    this.isCompleted = data.is_completed;
                    this.progressPercentage = data.course_progress_percentage;
                }
            } catch (err) {
                console.error(err);
            }
        }
    }">
        <!-- Top Player Navigation Bar -->
        <header class="h-16 border-b border-white/10 bg-[#071A36]/95 backdrop-blur px-4 md:px-6 flex items-center justify-between z-30 shrink-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2 text-slate-300 hover:text-white transition">
                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span class="hidden sm:inline font-medium text-sm">لوحة التحكم</span>
                </a>
                <div class="h-5 w-px bg-white/10 hidden sm:block"></div>
                <div>
                    <h1 class="text-sm font-semibold text-white truncate max-w-xs md:max-w-md">{{ $course->title_ar }}</h1>
                    <p class="text-xs text-[#F3D98B] truncate max-w-xs">{{ $activeLesson->title_ar }}</p>
                </div>
            </div>

            <!-- Central Progress and Actions -->
            <div class="flex items-center gap-3">
                <!-- Course Progress Pill -->
                <div class="hidden lg:flex items-center gap-3 bg-white/5 border border-white/10 rounded-full px-4 py-1.5">
                    <span class="text-xs text-slate-300">نسبة الإنجاز:</span>
                    <div class="w-24 bg-slate-700 rounded-full h-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-[#D4AF37] to-[#F3D98B] h-full transition-all duration-500" :style="`width: ${progressPercentage}%`"></div>
                    </div>
                    <span class="text-xs font-bold text-[#D4AF37]" x-text="`${progressPercentage}%`">{{ $progressData['percentage'] }}%</span>
                </div>

                <!-- Mark Completed Button -->
                @auth
                <button @click="toggleCompletion()"
                    :class="isCompleted ? 'bg-emerald-600/30 text-emerald-400 border-emerald-500/40 hover:bg-emerald-600/40' : 'bg-white/10 text-slate-200 border-white/20 hover:bg-white/20'"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border text-xs font-semibold transition">
                    <svg class="w-4 h-4" :class="isCompleted ? 'text-emerald-400' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span x-text="isCompleted ? 'مكتمل بنجاح' : 'تحديد كمكتمل'">{{ ($currentProgress && $currentProgress->is_completed) ? 'مكتمل بنجاح' : 'تحديد كمكتمل' }}</span>
                </button>
                @endauth

                <!-- Prev/Next Lesson Buttons -->
                <div class="flex items-center gap-1">
                    @if ($previousLesson)
                        <a href="{{ route('learn.player', [$course->slug, $previousLesson->id]) }}" title="الدرس السابق: {{ $previousLesson->title_ar }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/15 text-slate-300 transition">
                            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <button disabled class="p-2 rounded-lg bg-white/5 text-slate-600 cursor-not-allowed">
                            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @endif

                    @if ($nextLesson)
                        <a href="{{ route('learn.player', [$course->slug, $nextLesson->id]) }}" title="الدرس التالي: {{ $nextLesson->title_ar }}" class="p-2 rounded-lg bg-gradient-to-r from-[#D4AF37] to-[#F3D98B] text-[#071A36] font-semibold hover:opacity-95 transition">
                            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @endif
                </div>

                <!-- Toggle Sidebar Button -->
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg bg-white/5 hover:bg-white/15 text-slate-300 transition lg:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </header>

        <!-- Main Body: Player on the Right, Curriculum Sidebar on the Left (RTL Layout) -->
        <div class="flex-1 flex overflow-hidden relative">
            <!-- Left (Main Content Area) -->
            <main class="flex-1 overflow-y-auto flex flex-col bg-[#051329]">
                <!-- Media / Lesson Viewer Stage -->
                <div class="w-full bg-black flex items-center justify-center relative min-h-[360px] md:min-h-[500px] border-b border-white/10">
                    @php
                        $primaryContent = $contents->first();
                        $contentType = $primaryContent ? $primaryContent->type : $activeLesson->lesson_type;
                    @endphp

                    @if ($contentType === 'video' || empty($contentType))
                        <!-- Video Player -->
                        <div class="w-full h-full max-w-5xl aspect-video bg-black flex items-center justify-center relative">
                            <div class="text-center p-8">
                                <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] text-[#071A36] flex items-center justify-center mx-auto mb-4 shadow-xl shadow-[#D4AF37]/20">
                                    <svg class="w-10 h-10 translate-x-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-white mb-2">{{ $activeLesson->title_ar }}</h3>
                                <p class="text-sm text-slate-400 max-w-md mx-auto">مشغل الفيديو عالي الدقة والمحمي برابط مشفر خاص بمنصة Beforbim الهندسية.</p>
                                @if ($activeLesson->duration_seconds)
                                    <span class="inline-block mt-3 px-3 py-1 bg-white/10 rounded-full text-xs text-[#F3D98B]">المدة: {{ gmdate("i:s", $activeLesson->duration_seconds) }} دقيقة</span>
                                @endif
                            </div>
                        </div>
                    @elseif ($contentType === 'external_video')
                        <!-- External Video (Embed) -->
                        <div class="w-full h-full max-w-5xl aspect-video bg-black flex items-center justify-center">
                            <div class="text-center p-8">
                                <p class="text-sm text-slate-300">محتوى مرئي مدمج (External Video Stream)</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $primaryContent->content_data ?? 'رابط خارجي معتمد' }}</p>
                            </div>
                        </div>
                    @elseif ($contentType === 'text')
                        <!-- Text / Markdown Article Lesson -->
                        <div class="w-full max-w-4xl mx-auto py-12 px-6 text-slate-200">
                            <div class="prose prose-invert prose-yellow max-w-none">
                                <h2 class="text-2xl font-bold text-[#F3D98B] border-b border-white/10 pb-4 mb-6">{{ $activeLesson->title_ar }}</h2>
                                <div class="bg-[#123B68]/30 border border-white/10 rounded-xl p-6 leading-relaxed">
                                    {!! nl2br(e($primaryContent->document_markdown ?? $primaryContent->content_data ?? 'لا يوجد نص متاح لهذا الدرس.')) !!}
                                </div>
                            </div>
                        </div>
                    @elseif ($contentType === 'document')
                        <!-- Document / PDF Lesson -->
                        <div class="w-full max-w-4xl mx-auto py-12 px-6 text-center">
                            <div class="bg-[#123B68]/40 border border-white/10 rounded-2xl p-8 max-w-lg mx-auto">
                                <div class="w-16 h-16 rounded-xl bg-red-500/20 text-red-400 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-white mb-2">{{ $primaryContent->title ?? $activeLesson->title_ar }}</h3>
                                <p class="text-sm text-slate-300 mb-6">مستند دراسي هندسي بصيغة PDF / Document متاح للمطالعة والتحميل.</p>
                                @if (!empty($resources))
                                    @foreach ($resources as $res)
                                        <a href="{{ $signedResourceUrls[$res->id] ?? route('learn.resource_download', $res->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#D4AF37] to-[#F3D98B] text-[#071A36] font-bold rounded-xl hover:opacity-95 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            تحميل المستند الهندسي
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Lesson Tabs & Community Sections -->
                <div class="flex-1 max-w-5xl w-full mx-auto px-4 md:px-8 py-6">
                    <!-- Tab Navigation -->
                    <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-6 overflow-x-auto">
                        <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'text-[#D4AF37] border-b-2 border-[#D4AF37] font-bold' : 'text-slate-400 hover:text-white'" class="px-4 py-2 text-sm transition shrink-0">
                            نظرة عامة على الدرس
                        </button>
                        <button @click="activeTab = 'resources'" :class="activeTab === 'resources' ? 'text-[#D4AF37] border-b-2 border-[#D4AF37] font-bold' : 'text-slate-400 hover:text-white'" class="px-4 py-2 text-sm transition shrink-0 flex items-center gap-1.5">
                            الملفات الهندسية والمرفقات
                            @if ($resources->count() > 0)
                                <span class="bg-white/10 text-xs px-2 py-0.5 rounded-full">{{ $resources->count() }}</span>
                            @endif
                        </button>
                        <button @click="activeTab = 'discussions'" :class="activeTab === 'discussions' ? 'text-[#D4AF37] border-b-2 border-[#D4AF37] font-bold' : 'text-slate-400 hover:text-white'" class="px-4 py-2 text-sm transition shrink-0 flex items-center gap-1.5">
                            النقاشات والأسئلة الهندسية
                            @if ($discussions->count() > 0)
                                <span class="bg-[#D4AF37]/20 text-[#D4AF37] text-xs px-2 py-0.5 rounded-full">{{ $discussions->count() }}</span>
                            @endif
                        </button>
                        <button @click="activeTab = 'announcements'" :class="activeTab === 'announcements' ? 'text-[#D4AF37] border-b-2 border-[#D4AF37] font-bold' : 'text-slate-400 hover:text-white'" class="px-4 py-2 text-sm transition shrink-0 flex items-center gap-1.5">
                            إعلانات الدورة
                            @if ($announcements->count() > 0)
                                <span class="bg-blue-500/20 text-blue-400 text-xs px-2 py-0.5 rounded-full">{{ $announcements->count() }}</span>
                            @endif
                        </button>
                    </div>

                    <!-- Tab 1: Overview -->
                    <div x-show="activeTab === 'overview'" x-cloak class="space-y-6">
                        <div class="bg-[#123B68]/30 border border-white/10 rounded-2xl p-6">
                            <h3 class="text-xl font-bold text-white mb-3">{{ $activeLesson->title_ar }}</h3>
                            @if ($activeLesson->title_en)
                                <h4 class="text-sm font-medium text-slate-400 mb-4">{{ $activeLesson->title_en }}</h4>
                            @endif
                            <p class="text-sm text-slate-300 leading-relaxed">
                                {{ $course->short_description_ar ?? 'يتناول هذا الدرس جوانب تطبيقية متقدمة في النمذجة الهندسية وإدارة المشروعات وفق متطلبات الـ BIM الحديثة.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Tab 2: Downloadable Engineering Resources -->
                    <div x-show="activeTab === 'resources'" x-cloak class="space-y-4">
                        @if ($resources->isEmpty())
                            <div class="bg-[#123B68]/20 border border-white/5 rounded-2xl p-8 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="text-sm">لا توجد ملفات مرفقة لهذا الدرس حالياً.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($resources as $resource)
                                    <div class="bg-[#123B68]/30 border border-white/10 rounded-xl p-4 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-[#D4AF37]/10 text-[#D4AF37] flex items-center justify-center font-bold text-xs uppercase">
                                                {{ $resource->file_extension ?: 'FILE' }}
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-white">{{ $resource->title_ar ?: $resource->title_en }}</h4>
                                                <p class="text-xs text-slate-400">{{ number_format($resource->file_size_bytes / 1024, 1) }} KB</p>
                                            </div>
                                        </div>
                                        @if (isset($signedResourceUrls[$resource->id]))
                                            <a href="{{ $signedResourceUrls[$resource->id] }}" class="p-2.5 rounded-lg bg-[#D4AF37] hover:bg-[#F3D98B] text-[#071A36] font-semibold text-xs flex items-center gap-1 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                تحميل
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-500 bg-white/5 px-2 py-1 rounded">مخصص للمشتركين</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Tab 3: Discussions & Questions -->
                    <div x-show="activeTab === 'discussions'" x-cloak class="space-y-6">
                        @auth
                            <!-- Post Question Form -->
                            <form action="{{ route('learn.discussions.store', $course->id) }}" method="POST" class="bg-[#123B68]/30 border border-white/10 rounded-2xl p-5">
                                @csrf
                                <input type="hidden" name="lesson_id" value="{{ $activeLesson->id }}">
                                <label for="message" class="block text-sm font-semibold text-white mb-2">اطرح سؤالاً على المدرب بخصوص هذا الدرس:</label>
                                <textarea name="message" id="message" rows="3" required placeholder="اكتب استفسارك الهندسي هنا بالتفصيل..." class="w-full bg-[#071A36] border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#D4AF37] resize-none"></textarea>
                                <div class="mt-3 flex justify-end">
                                    <button type="submit" class="px-5 py-2 bg-gradient-to-r from-[#D4AF37] to-[#F3D98B] text-[#071A36] font-bold text-xs rounded-xl hover:opacity-95 transition">
                                        إرسال السؤال
                                    </button>
                                </div>
                            </form>
                        @endauth

                        <!-- Discussion List -->
                        <div class="space-y-4">
                            @forelse ($discussions as $discussion)
                                <div class="bg-[#123B68]/20 border border-white/10 rounded-2xl p-5 space-y-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-[#123B68] to-[#D4AF37] flex items-center justify-center font-bold text-white text-xs">
                                                {{ mb_substr($discussion->user->name ?? 'م', 0, 1) }}
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-bold text-white">{{ $discussion->user->name }}</h4>
                                                <p class="text-xs text-slate-400">{{ $discussion->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-sm text-slate-200 leading-relaxed pr-12">{{ $discussion->message }}</p>

                                    <!-- Replies List -->
                                    @if ($discussion->replies->isNotEmpty())
                                        <div class="mr-8 space-y-3 pt-3 border-t border-white/5">
                                            @foreach ($discussion->replies as $reply)
                                                <div class="bg-[#071A36]/60 border border-white/5 rounded-xl p-4">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <span class="text-xs font-bold text-[#F3D98B]">{{ $reply->user->name }}</span>
                                                        @if ($reply->user->hasRole('instructor') || $reply->user_id === $course->instructor_id)
                                                            <span class="text-[10px] bg-[#D4AF37]/20 text-[#D4AF37] px-2 py-0.5 rounded-full font-bold">المدرب</span>
                                                        @endif
                                                        <span class="text-[10px] text-slate-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-xs text-slate-300 leading-relaxed">{{ $reply->message }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Reply Form -->
                                    @auth
                                        <form action="{{ route('learn.discussions.store', $course->id) }}" method="POST" class="mt-3 flex gap-2 mr-8">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $discussion->id }}">
                                            <input type="text" name="message" required placeholder="أضف رداً على هذا الاستفسار..." class="flex-1 bg-[#071A36] border border-white/10 rounded-lg px-3 py-1.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-[#D4AF37]">
                                            <button type="submit" class="px-4 py-1.5 bg-white/10 hover:bg-white/20 text-white rounded-lg text-xs font-semibold transition">رد</button>
                                        </form>
                                    @endauth
                                </div>
                            @empty
                                <div class="bg-[#123B68]/10 border border-white/5 rounded-2xl p-8 text-center text-slate-400">
                                    <p class="text-sm">لا توجد أسئلة أو نقاشات حول هذا الدرس بعد. كن أول من يطرح سؤالاً!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Tab 4: Announcements -->
                    <div x-show="activeTab === 'announcements'" x-cloak class="space-y-4">
                        @forelse ($announcements as $announcement)
                            <div class="bg-[#123B68]/30 border border-white/10 rounded-2xl p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-base font-bold text-[#F3D98B]">{{ $announcement->title }}</h4>
                                    <span class="text-xs text-slate-400">{{ $announcement->published_at?->diffForHumans() ?? $announcement->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-slate-300 leading-relaxed">{{ $announcement->content }}</p>
                            </div>
                        @empty
                            <div class="bg-[#123B68]/10 border border-white/5 rounded-2xl p-8 text-center text-slate-400">
                                <p class="text-sm">لا توجد إعلانات منشورة لهذه الدورة حالياً.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </main>

            <!-- Right Sidebar: Curriculum Navigation -->
            <aside class="w-80 md:w-96 bg-[#071A36] border-r border-white/10 flex flex-col shrink-0 z-20 transition-all duration-300"
                :class="sidebarOpen ? 'block' : 'hidden lg:block'">
                <!-- Curriculum Header -->
                <div class="p-4 border-b border-white/10 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-white">محتوى الدورة التدريبية</h3>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $progressData['completed_count'] ?? 0 }} من {{ $progressData['total_lessons'] ?? 0 }} درس مكتمل</p>
                    </div>
                    <span class="text-xs font-bold text-[#D4AF37] bg-[#D4AF37]/10 px-2.5 py-1 rounded-full">
                        {{ $progressData['percentage'] }}%
                    </span>
                </div>

                <!-- Sections and Lessons Accordion -->
                <div class="flex-1 overflow-y-auto divide-y divide-white/5">
                    @foreach ($sections as $sectionIndex => $section)
                        <div x-data="{ open: true }" class="bg-white/[0.02]">
                            <!-- Section Title -->
                            <button @click="open = !open" class="w-full p-4 flex items-center justify-between text-right hover:bg-white/[0.03] transition">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-white/10 text-slate-300 flex items-center justify-center text-xs font-bold">
                                        {{ $sectionIndex + 1 }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-200">{{ $section->title_ar }}</span>
                                </div>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <!-- Lessons in Section -->
                            <div x-show="open" class="divide-y divide-white/[0.03]">
                                @foreach ($section->lessons as $lessonItem)
                                    @php
                                        $isCurrent = $lessonItem->id === $activeLesson->id;
                                        $isLessonCompleted = in_array($lessonItem->id, $progressData['completed_lesson_ids'] ?? []);
                                    @endphp
                                    <a href="{{ route('learn.player', [$course->slug, $lessonItem->id]) }}"
                                        class="p-3.5 flex items-center justify-between gap-3 text-right transition {{ $isCurrent ? 'bg-[#123B68]/60 border-r-4 border-[#D4AF37]' : 'hover:bg-white/[0.02]' }}">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <!-- Status Checkmark / Icon -->
                                            <div class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 {{ $isLessonCompleted ? 'bg-emerald-500/20 text-emerald-400' : ($isCurrent ? 'bg-[#D4AF37]/20 text-[#D4AF37]' : 'bg-white/5 text-slate-500') }}">
                                                @if ($isLessonCompleted)
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                @elseif ($isCurrent)
                                                    <div class="w-2 h-2 rounded-full bg-[#D4AF37]"></div>
                                                @else
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                                @endif
                                            </div>

                                            <div class="truncate">
                                                <h5 class="text-xs font-medium {{ $isCurrent ? 'text-white font-bold' : 'text-slate-300' }} truncate">
                                                    {{ $lessonItem->title_ar }}
                                                </h5>
                                                <div class="flex items-center gap-2 mt-0.5">
                                                    @if ($lessonItem->duration_seconds)
                                                        <span class="text-[10px] text-slate-500">{{ gmdate("i:s", $lessonItem->duration_seconds) }}</span>
                                                    @endif
                                                    @if ($lessonItem->canPreview())
                                                        <span class="text-[9px] bg-emerald-500/10 text-emerald-400 px-1.5 py-0.2 rounded font-semibold">معاينة مجانية</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        @if ($isCurrent)
                                            <span class="text-[10px] text-[#D4AF37] font-semibold shrink-0">يعرض الآن</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </aside>
        </div>
    </div>
</x-layouts.base>
