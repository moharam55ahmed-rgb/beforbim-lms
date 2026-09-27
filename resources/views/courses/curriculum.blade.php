<x-layouts.base title="بناء المنهج والأقسام — {{ $course->title_ar }}">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('courses.edit', $course->id) }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg">
                        B
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-base font-black tracking-wider text-white font-['Tajawal']">{{ $course->title_ar }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-[#D4AF37]/20 text-[#F3D98B] border border-[#D4AF37]/40">
                                {{ $course->status }}
                            </span>
                        </div>
                        <span class="block text-[10px] text-slate-300 font-mono">استوديو بناء وتنسيق المنهج الهندسي (Curriculum Builder)</span>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('courses.edit', $course->id) }}">
                    <x-button variant="outline-gold" size="sm">تعديل البيانات الأساسية</x-button>
                </a>
                @if($course->status === 'DRAFT' || $course->status === 'REJECTED')
                    <form method="POST" action="{{ route('courses.submit', $course->id) }}" class="inline">
                        @csrf
                        <x-button type="submit" variant="gold" size="sm">
                            إرسال للاعتماد الأكاديمي &uarr;
                        </x-button>
                    </form>
                @endif
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-6" x-data="{ activeSectionModal: false, activeLessonModal: null, activeResourceModal: null }">
        @if(session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif

        <!-- Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-[#071A36] font-['Tajawal']">هيكلية الفصول والمحاضرات الهندسية</h1>
                <p class="text-xs text-slate-500 mt-0.5">قسم الدورة إلى فصول رئيسية، وأضف الدروس وملفات الـ BIM لكل محاضرة</p>
            </div>
            <x-button @click="activeSectionModal = true" variant="navy" size="sm">
                + إضافة قسم جديد (Section)
            </x-button>
        </div>

        <!-- Sections List -->
        @if($course->sections->isEmpty())
            <div class="bg-white rounded-2xl border-2 border-dashed border-slate-300 p-12 text-center">
                <div class="w-14 h-14 rounded-2xl bg-[#071A36]/5 text-[#D4AF37] flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-base font-bold text-[#071A36]">لا توجد أقسام في المنهج بعد</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">ابدأ بإضافة القسم الأول للدورة التدريبية، مثل: مقدمة نمذجة BIM، أو أساسيات واجهة برنامج Revit.</p>
                <div class="mt-4">
                    <x-button @click="activeSectionModal = true" variant="gold" size="sm">إضافة القسم الأول</x-button>
                </div>
            </div>
        @else
            <div class="space-y-6">
                @foreach($course->sections as $sectionIndex => $section)
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden" x-data="{ expanded: true }">
                        <!-- Section Header -->
                        <div class="bg-[#F5F7FA] px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <button type="button" @click="expanded = !expanded" class="text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-5 h-5 transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-mono font-bold bg-[#071A36] text-[#D4AF37] px-2 py-0.5 rounded">
                                            القسم {{ $sectionIndex + 1 }}
                                        </span>
                                        <h3 class="font-bold text-sm text-[#071A36]">{{ $section->title_ar }}</h3>
                                    </div>
                                    @if($section->description_ar)
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $section->description_ar }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <x-button @click="activeLessonModal = {{ $section->id }}" variant="gold" size="sm" class="text-xs">
                                    + إضافة درس
                                </x-button>
                                <form method="POST" action="{{ route('courses.sections.destroy', $section->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا القسم وجميع دروسه؟');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Lessons List within Section -->
                        <div x-show="expanded" class="divide-y divide-slate-100">
                            @if($section->lessons->isEmpty())
                                <div class="p-6 text-center text-xs text-slate-400">
                                    لا توجد دروس أو محاضرات في هذا القسم بعد. اضغط على "+ إضافة درس" لإدراج المحتوى.
                                </div>
                            @else
                                @foreach($section->lessons as $lessonIndex => $lesson)
                                    <div class="p-5 hover:bg-slate-50/60 transition-colors flex flex-col md:flex-row md:items-center justify-between gap-4">
                                        <div class="flex items-start gap-3">
                                            <div class="p-2 rounded-xl bg-blue-50 text-[#123B68] mt-0.5">
                                                @if($lesson->lesson_type === 'VIDEO')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-bold text-[#071A36]">
                                                        {{ $sectionIndex + 1 }}.{{ $lessonIndex + 1 }} {{ $lesson->title_ar }}
                                                    </span>
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                                                        {{ $lesson->lesson_type }}
                                                    </span>
                                                    @if($lesson->is_preview_free)
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                            معاينة مجانية
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Attached Resources Preview -->
                                                <div class="flex items-center gap-2 mt-2">
                                                    @foreach($lesson->resources as $res)
                                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] bg-slate-100 text-slate-700 border border-slate-200">
                                                            <span>📁 {{ $res->title_ar }} ({{ strtoupper($res->file_extension) }})</span>
                                                            <form method="POST" action="{{ route('courses.resources.destroy', $res->id) }}" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-slate-400 hover:text-rose-500">&times;</button>
                                                            </form>
                                                        </span>
                                                    @endforeach
                                                    <button type="button" @click="activeResourceModal = {{ $lesson->id }}" class="text-[10px] font-bold text-[#123B68] hover:text-[#071A36]">
                                                        + إرفاق ملف BIM
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 self-end md:self-auto">
                                            <form method="POST" action="{{ route('courses.lessons.destroy', $lesson->id) }}" onsubmit="return confirm('هل تريد بالتأكيد حذف هذا الدرس؟');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Add Section Modal -->
        <div x-show="activeSectionModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-sm text-[#071A36]">إضافة قسم جديد في المنهج</h3>
                    <button @click="activeSectionModal = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>
                <form method="POST" action="{{ route('courses.sections.store', $course->id) }}" class="space-y-4">
                    @csrf
                    <x-input name="title_ar" label="عنوان القسم (بالعربية)" placeholder="مثال: الفصل الأول: مقدمة وإعداد البيئة الهندسية" required />
                    <x-input name="title_en" label="العنوان بالإنجليزية (اختياري)" placeholder="e.g. Chapter 1: Setup & Foundations" />
                    <div class="space-y-1.5 text-start">
                        <label for="description_ar" class="block text-xs font-semibold text-slate-700">وصف موجز للقسم</label>
                        <textarea name="description_ar" id="description_ar" rows="2" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs" placeholder="ماذا سيتعلم الطالب في هذا القسم؟"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <x-button type="button" @click="activeSectionModal = false" variant="outline" size="sm">إلغاء</x-button>
                        <x-button type="submit" variant="gold" size="sm">حفظ القسم</x-button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add Lesson Modal -->
        @foreach($course->sections as $section)
            <div x-show="activeLessonModal === {{ $section->id }}" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-bold text-sm text-[#071A36]">إضافة درس إلى: {{ $section->title_ar }}</h3>
                        <button @click="activeLessonModal = null" class="text-slate-400 hover:text-slate-600">&times;</button>
                    </div>
                    <form method="POST" action="{{ route('courses.lessons.store', $section->id) }}" class="space-y-4">
                        @csrf
                        <x-input name="title_ar" label="عنوان الدرس (بالعربية)" placeholder="مثال: نمذجة الأعمدة والكمرات الخرسانية في Revit" required />
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1 text-start">
                                <label class="block text-xs font-semibold text-slate-700">نوع المحتوى</label>
                                <select name="lesson_type" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                                    <option value="VIDEO">فيديو مسجل (Video)</option>
                                    <option value="ARTICLE">مقال هندسي (Article)</option>
                                    <option value="DOCUMENT">مستند / كراسة شروط (Document)</option>
                                </select>
                            </div>

                            <div class="space-y-1 text-start">
                                <label class="block text-xs font-semibold text-slate-700">المدة بالثواني</label>
                                <input type="number" name="duration_seconds" value="600" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs" />
                            </div>
                        </div>

                        <x-input name="video_url" label="رابط الفيديو (Vimeo / Local / YouTube)" placeholder="https://vimeo.com/..." />

                        <div class="flex items-center gap-2 pt-1">
                            <input type="checkbox" name="is_preview_free" id="preview_{{ $section->id }}" value="1" class="rounded border-slate-300 text-[#D4AF37] focus:ring-[#D4AF37]">
                            <label for="preview_{{ $section->id }}" class="text-xs text-slate-700 font-medium">إتاحة المحاضرة كمعاينة مجانية للجمهور (Free Preview)</label>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <x-button type="button" @click="activeLessonModal = null" variant="outline" size="sm">إلغاء</x-button>
                            <x-button type="submit" variant="gold" size="sm">حفظ الدرس</x-button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

        <!-- Add Resource Modal -->
        @foreach($course->sections as $sec)
            @foreach($sec->lessons as $les)
                <div x-show="activeResourceModal === {{ $les->id }}" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="font-bold text-sm text-[#071A36]">إرفاق ملف هندسي للدرس: {{ $les->title_ar }}</h3>
                            <button @click="activeResourceModal = null" class="text-slate-400 hover:text-slate-600">&times;</button>
                        </div>
                        <form method="POST" action="{{ route('courses.resources.store', $les->id) }}" class="space-y-4">
                            @csrf
                            <x-input name="title_ar" label="اسم الملف التوضيحي" placeholder="مثال: ملف Revit للمشروع الهندسي" required />
                            <x-input name="file_name" label="اسم الملف مع الامتداد" placeholder="sample-tower.rvt" required />
                            <x-input name="file_path" label="مسار الملف أو رابط التخزين" placeholder="courses/resources/sample-tower.rvt" required />
                            
                            <div class="flex items-center gap-2 pt-1">
                                <input type="checkbox" name="is_downloadable" id="download_{{ $les->id }}" value="1" checked class="rounded border-slate-300 text-[#D4AF37] focus:ring-[#D4AF37]">
                                <label for="download_{{ $les->id }}" class="text-xs text-slate-700 font-medium">السماح للطلاب المسجلين بتحميل هذا الملف</label>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <x-button type="button" @click="activeResourceModal = null" variant="outline" size="sm">إلغاء</x-button>
                                <x-button type="submit" variant="gold" size="sm">إرفاق الملف</x-button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        @endforeach

    </main>
</x-layouts.base>
