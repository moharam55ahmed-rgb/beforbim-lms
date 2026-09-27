<x-layouts.base title="مكتبة الوسائط والملفات — Beforbim">
    <!-- Header -->
    <header class="bg-[#071A36] text-white border-b border-[#D4AF37]/20 sticky top-0 z-30 shadow-xl shadow-black/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#D4AF37] to-[#F3D98B] flex items-center justify-center font-black text-[#071A36] shadow-md shadow-[#D4AF37]/20 text-lg hover:opacity-95 transition">
                    B
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-base font-bold font-['Tajawal'] text-white">مكتبة الوسائط والملفات الهندسية</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#D4AF37] text-[#071A36]">
                            Media Hub
                        </span>
                    </div>
                    <p class="text-xs text-slate-300">إدارة الصور، النماذج، ملفات الـ IFC ومرفقات الدورات</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}">
                    <x-button variant="outline-gold" size="sm">العودة للوحة التحكم</x-button>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-8" dir="rtl">
        @if (session('status'))
            <x-alert type="success" :message="session('status')" />
        @endif

        @if ($errors->any())
            <x-alert type="error">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        <!-- Upload Panel -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-4">
            <h3 class="text-base font-bold text-[#071A36] font-['Tajawal'] flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-[#D4AF37]"></span>
                رفع ملف جديد إلى المكتبة
            </h3>

            <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
                @csrf
                <div class="sm:col-span-5">
                    <label class="block text-xs font-bold text-slate-700 mb-1">اختر الملف (صورة، نموذج، PDF، إلخ)</label>
                    <input type="file" name="file" required class="w-full text-xs text-slate-500 file:mr-0 file:ml-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#071A36] file:text-white hover:file:bg-[#123B68] file:cursor-pointer border border-slate-200 rounded-xl p-1.5">
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold text-slate-700 mb-1">المجموعة (Collection)</label>
                    <input type="text" name="collection_name" placeholder="banners, courses, icons..." value="general" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">قرص التخزين</label>
                    <select name="disk" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-[#071A36] focus:outline-none">
                        <option value="public">عام (Public URL)</option>
                        <option value="local">خاص ومحمي (Signed)</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <x-button type="submit" variant="gold" class="w-full text-xs py-2.5 font-bold">
                        رفع الملف &uarr;
                    </x-button>
                </div>
            </form>
        </div>

        <!-- Collections Filter Bar -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1">
            <a href="{{ route('admin.media.index') }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $currentCollection === 'all' ? 'bg-[#071A36] text-white shadow-md shadow-[#071A36]/20' : 'bg-white text-slate-600 border border-slate-200 hover:border-[#D4AF37]' }}">
                كافة المجموعات
            </a>
            @foreach($collections as $col)
                <a href="{{ route('admin.media.index', ['collection' => $col]) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $currentCollection === $col ? 'bg-[#071A36] text-white shadow-md shadow-[#071A36]/20' : 'bg-white text-slate-600 border border-slate-200 hover:border-[#D4AF37]' }}">
                    {{ $col }}
                </a>
            @endforeach
        </div>

        <!-- Media Items Grid -->
        @if($mediaFiles->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 text-slate-400 text-xs">
                لا توجد ملفات مرفوعة حالياً في هذه المجموعة.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($mediaFiles as $media)
                    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-lg transition">
                        <!-- Preview Thumbnail or Generic Icon -->
                        <div class="h-36 bg-slate-100 flex items-center justify-center overflow-hidden border-b border-slate-100 relative group">
                            @if(str_starts_with($media->mime_type, 'image/') && $media->disk === 'public')
                                <img src="{{ $media->getUrl() }}" alt="{{ $media->file_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center p-4">
                                    <svg class="w-10 h-10 text-slate-400 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <span class="text-[10px] font-mono text-slate-500 block uppercase truncate max-w-[150px]">{{ $media->mime_type }}</span>
                                </div>
                            @endif

                            <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[9px] font-bold bg-[#071A36]/80 text-white backdrop-blur-sm">
                                {{ $media->collection_name }}
                            </span>
                        </div>

                        <!-- Info & Actions -->
                        <div class="p-4 space-y-3">
                            <div>
                                <h4 class="text-xs font-bold text-[#071A36] truncate" title="{{ $media->file_name }}">
                                    {{ $media->file_name }}
                                </h4>
                                <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1 font-mono">
                                    <span>{{ $media->readable_size }}</span>
                                    <span>{{ $media->created_at->format('Y-m-d') }}</span>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2" x-data="{ copied: false }">
                                <button type="button" @click="navigator.clipboard.writeText('{{ $media->getUrl() }}'); copied = true; setTimeout(() => copied = false, 2000)" class="text-[11px] font-bold text-[#123B68] hover:text-[#D4AF37] transition flex items-center gap-1">
                                    <span x-text="copied ? 'تم النسخ!' : 'نسخ الرابط'"></span>
                                </button>

                                <form action="{{ route('admin.media.destroy', $media->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الملف نهائياً؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[11px] text-rose-500 hover:text-rose-700 font-bold">
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $mediaFiles->links() }}
            </div>
        @endif
    </main>
</x-layouts.base>
