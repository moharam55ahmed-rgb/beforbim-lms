<x-layouts.base :title="'Course Content — ' . ($course->title_en ?: $course->title_ar)">
    <div class="min-h-screen bg-slate-50 dark:bg-[#071A36] text-slate-800 dark:text-white flex flex-col items-center justify-center p-6 transition-colors">
        <div class="bg-white dark:bg-[#123B68]/30 border border-slate-200 dark:border-white/10 rounded-3xl p-8 max-w-md w-full text-center shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-[#D4AF37]/20 text-[#D4AF37] flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2 font-['Outfit']">{{ $course->title_en ?: $course->title_ar }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-300 mb-6">This course content is being prepared by the instructor. Lessons will be published soon.</p>
            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-[#D4AF37] to-[#F3D98B] text-[#040E1E] font-bold rounded-xl text-sm hover:opacity-95 transition shadow-lg shadow-[#D4AF37]/20">
                &larr; Return to Dashboard
            </a>
        </div>
    </div>
</x-layouts.base>
