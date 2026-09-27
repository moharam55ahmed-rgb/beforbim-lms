<x-layouts.base :title="'بنك الأسئلة الهندسية — Beforbim'">
    <div class="min-h-screen bg-[#F5F7FA] py-10 px-4 md:px-8">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <div>
                    <h1 class="text-xl md:text-2xl font-black text-[#071A36]">بنك الأسئلة الهندسية التخصصي</h1>
                    <p class="text-xs md:text-sm text-slate-500 mt-1">تصنيف الأسئلة حسب التخصص والمستوى وصعوبة الأسئلة (Revit, Navisworks, BIM)</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.assessments.index') }}" class="px-4 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold rounded-xl transition">
                        العودة للاختبارات
                    </a>
                </div>
            </div>

            <!-- Filter Bar -->
            <form action="{{ route('admin.assessments.questions') }}" method="GET" class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 grid grid-cols-1 md:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث في نص السؤال..." class="px-3 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-[#123B68]">
                
                <select name="category" class="px-3 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-[#123B68]">
                    <option value="">جميع التصنيفات التخصصية</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>

                <select name="difficulty_level" class="px-3 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-[#123B68]">
                    <option value="">جميع مستويات الصعوبة</option>
                    <option value="easy" {{ request('difficulty_level') === 'easy' ? 'selected' : '' }}>سهل (Easy)</option>
                    <option value="medium" {{ request('difficulty_level') === 'medium' ? 'selected' : '' }}>متوسط (Medium)</option>
                    <option value="hard" {{ request('difficulty_level') === 'hard' ? 'selected' : '' }}>متقدم / صعب (Hard)</option>
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-[#071A36] text-[#D4AF37] font-bold text-xs rounded-xl hover:bg-[#123B68] transition">
                        تصفية النتائج
                    </button>
                    @if(request()->anyFilled(['search', 'category', 'difficulty_level']))
                        <a href="{{ route('admin.assessments.questions') }}" class="px-3 py-2 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 text-xs flex items-center">
                            إعادة ضبط
                        </a>
                    @endif
                </div>
            </form>

            <!-- Questions List -->
            <div class="space-y-4">
                @forelse($questions as $question)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:border-slate-300 transition">
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-3">
                            <div class="space-y-2 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    @if($question->category)
                                        <span class="px-2.5 py-0.5 text-[11px] font-bold rounded-md bg-blue-50 text-[#123B68] border border-blue-200">
                                            {{ $question->category }}
                                        </span>
                                    @endif

                                    <span class="px-2.5 py-0.5 text-[11px] font-bold rounded-md
                                        @if($question->difficulty_level === 'easy') bg-emerald-50 text-emerald-700 border border-emerald-200
                                        @elseif($question->difficulty_level === 'hard') bg-rose-50 text-rose-700 border border-rose-200
                                        @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                                        {{ ucfirst($question->difficulty_level) }}
                                    </span>

                                    <span class="px-2.5 py-0.5 text-[11px] font-mono font-bold rounded-md bg-slate-100 text-slate-600">
                                        {{ $question->question_type }}
                                    </span>

                                    <span class="text-xs font-mono text-slate-400">الدرجة: {{ $question->points }}</span>
                                </div>

                                <p class="text-sm font-bold text-[#071A36] leading-relaxed">{{ $question->question_text_ar }}</p>

                                @if(!empty($question->tags))
                                    <div class="flex flex-wrap gap-1.5 pt-1">
                                        @foreach($question->tags as $tag)
                                            <span class="text-[10px] px-2 py-0.5 bg-slate-50 text-slate-500 rounded border border-slate-200">#{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <form action="{{ route('admin.assessments.questions.destroy', $question->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا السؤال؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-xs text-rose-600 hover:bg-rose-50 rounded-lg transition font-medium">
                                    حذف
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-12 text-center rounded-2xl border border-slate-200 text-slate-400 text-sm">
                        لا توجد أسئلة تطابق معايير البحث المحددة.
                    </div>
                @endforelse
            </div>

            @if($questions->hasPages())
                <div class="p-4 bg-white rounded-2xl border border-slate-200">
                    {{ $questions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.base>
