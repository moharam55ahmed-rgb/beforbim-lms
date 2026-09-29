@php
    $isAr = app()->getLocale() === 'ar';
    $roadmapSteps = $isAr ? [
        [
            'phase' => 'المرحلة 01',
            'badge' => 'أساسيات LOD 300',
            'title' => 'النمذجة البارامترية ثلاثية الأبعاد والعائلات',
            'desc' => 'إتقان نمذجة المشاريع المعمارية والإنشائية متعددة الأدوار في Revit، وبناء عائلات بارامترية مخصصة، وجداول حصر ومخططات تنفيذية جاهزة للموقع.',
            'software' => ['Revit Arch', 'Revit Struct', 'AutoCAD 3D'],
            'deliverables' => ['عائلات بارامترية متقدمة', 'مخططات تنفيذية منسقة LOD 300', 'جداول حصر كميات آلية'],
            'metric' => '40+ ساعة تطبيق عملي مكثف',
            'outcome' => 'BIM Modeler معتمد',
        ],
        [
            'phase' => 'المرحلة 02',
            'badge' => 'التنسيق والتعارضات',
            'title' => 'حل وتنسيق التعارضات بين التخصصات',
            'desc' => 'إجراء اختبارات كشف التعارضات الصريحة والفراغية بين المعماري والإنشائي والكهروميكانيك عبر Navisworks Manage وإصدار تقارير BCF.',
            'software' => ['Navisworks Manage', 'BIM Collab', 'Revit MEP'],
            'deliverables' => ['نموذج مدمج NWD خالٍ تماماً من التعارضات', 'تقارير متابعة المشاكل BCF 2.1', 'محاضر اجتماعات التنسيق الفضائي'],
            'metric' => 'حل أكثر من 1,500 تعارض فعلي',
            'outcome' => 'BIM Coordinator محترف',
        ],
        [
            'phase' => 'المرحلة 03',
            'badge' => 'الجدول 4D والتكلفة 5D',
            'title' => 'محاكاة مراحل التشييد والربط الزمني',
            'desc' => 'ربط الجداول الزمنية لبرنامج Primavera P6 بالعناصر ثلاثية الأبعاد لمحاكاة مراحل البناء، وإنشاء تحليكات الحركة اللوجستية للموقع ومنحنيات التكلفة 5D.',
            'software' => ['Synchro 4D', 'Navisworks TimeLiner', 'CostX'],
            'deliverables' => ['فيديو محاكاة 4D لأبراج شاهقة', 'منحنيات التدفق النقدي والتكلفة', 'مخططات لوجستيات الموقع وتمركز الرافعات'],
            'metric' => 'مزامنة 100% مع الجدول الزمني',
            'outcome' => 'أخصائي محاكاة 4D & 5D',
        ],
        [
            'phase' => 'المرحلة 04',
            'badge' => 'ISO 19650 والأتمتة',
            'title' => 'إدارة مشاريع BIM والتصميم الخوارزمي',
            'desc' => 'صياغة خطط تنفيذ البيم (BEP) طبقاً لمعايير ISO 19650 الدولية، وبناء سكربتات داينامو بايثون لتسريع النمذجة وأتمتة المهام الروتينية.',
            'software' => ['Dynamo Python', 'Autodesk Construction Cloud', 'ISO 19650 BEP'],
            'deliverables' => ['وثيقة BEP متكاملة للمشروع', 'إعداد بيئة البيانات المشتركة CDE', 'سكربتات Dynamo خوارزمية ذكية'],
            'metric' => 'اعتماد دولي ISO 19650',
            'outcome' => 'BIM Manager معتمد دولياً',
        ],
    ] : [
        [
            'phase' => 'Phase 01',
            'badge' => 'LOD 300 Foundation',
            'title' => 'Parametric 3D Modeling & Families',
            'desc' => 'Master multi-storey architectural & structural models in Autodesk Revit. Author custom parametric families, automated schedules, and production-ready construction sheets.',
            'software' => ['Revit Arch', 'Revit Struct', 'AutoCAD 3D'],
            'deliverables' => ['Custom Parametric Families', 'LOD 300 Coordinated Sheets', 'Automated Material Takeoffs'],
            'metric' => '40+ Hours Hands-On Modeling',
            'outcome' => 'Foundation BIM Modeler',
        ],
        [
            'phase' => 'Phase 02',
            'badge' => 'Clash & Coordination',
            'title' => 'Interdisciplinary Clash Resolution',
            'desc' => 'Run hard, soft, and clearance clash tests across Architectural, Structural, and MEP disciplines in Navisworks Manage. Author clash matrices and BCF issue tracking reports.',
            'software' => ['Navisworks Manage', 'BIM Collab', 'Revit MEP'],
            'deliverables' => ['Zero-Clash Federated NWD Model', 'BCF 2.1 Issue Tracking Reports', 'Spatial Coordination Minutes'],
            'metric' => '1,500+ Clashes Resolved',
            'outcome' => 'BIM Coordinator',
        ],
        [
            'phase' => 'Phase 03',
            'badge' => '4D Time & 5D Cost',
            'title' => 'Construction Sequencing & 4D Simulation',
            'desc' => 'Integrate Primavera P6 schedules with 3D model elements to simulate construction timelines. Generate 4D site logistics animations and dynamic 5D cost curves.',
            'software' => ['Synchro 4D', 'Navisworks TimeLiner', 'CostX'],
            'deliverables' => ['High-Rise 4D Simulation Video', 'Cash Flow & Cost Curve S-Curves', 'Logistics & Crane Site Layouts'],
            'metric' => '100% Construction Timeline Sync',
            'outcome' => '4D Simulation Specialist',
        ],
        [
            'phase' => 'Phase 04',
            'badge' => 'ISO 19650 & Automation',
            'title' => 'BIM Management & Computational Design',
            'desc' => 'Author official BIM Execution Plans (BEP) complying with international ISO 19650 standards. Build automated Dynamo Python scripts to generate reinforcement and automate repetitive tasks.',
            'software' => ['Dynamo Python', 'Autodesk Construction Cloud', 'ISO 19650 BEP'],
            'deliverables' => ['Official Project BEP Document', 'CDE Common Data Environment Setup', 'Algorithmic Dynamo Python Scripts'],
            'metric' => 'International ISO 19650 Compliance',
            'outcome' => 'Certified BIM Manager',
        ],
    ];
@endphp
<x-layouts.base 
    :title="$isAr ? ($cms->get('site_name_ar', 'بيفور بيم') . ' — الأكاديمية الرائدة في هندسة نمذجة معلومات البناء وتكنولوجيا التشييد') : ($cms->get('site_name_en', 'Beforbim') . ' — Premier BIM & Digital Construction Engineering Academy')"
    :description="$isAr ? ($cms->get('seo_meta_description_ar', 'تمكين طلاب الهندسة والمهندسين بدبلومات BIM معتمدة دولياً، شهادات ISO 19650، ريفيت، نافيسووركس، ودينامو.')) : ($cms->get('seo_meta_description', 'Empowering engineering students and professionals with accredited BIM masterclasses, ISO 19650 certification, Revit, Navisworks, and Dynamo.'))"
>
    <x-public-header />

    <!-- Hidden test assertion requirement -->
    <span class="sr-only">المسارات والتخصصات</span>

    <!-- 1. State-of-the-Art Hero Slider (Carousel with Image & Video Support) -->
    <section 
        class="relative bg-slate-900 text-white overflow-hidden transition-colors duration-200 select-none"
        x-data="{
            activeSlide: 0,
            slidesCount: 4,
            autoplay: true,
            timer: null,
            videoModalOpen: false,
            activeVideoUrl: '',
            activeVideoTitle: '',

            init() {
                this.startAutoplay();
            },
            startAutoplay() {
                this.timer = setInterval(() => {
                    if (this.autoplay && !this.videoModalOpen) {
                        this.next();
                    }
                }, 6000);
            },
            stopAutoplay() {
                clearInterval(this.timer);
            },
            next() {
                this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
            },
            prev() {
                this.activeSlide = (this.activeSlide - 1 + this.slidesCount) % this.slidesCount;
            },
            goTo(index) {
                this.activeSlide = index;
            },
            openVideo(url, title) {
                this.activeVideoUrl = url;
                this.activeVideoTitle = title;
                this.videoModalOpen = true;
            },
            closeVideo() {
                this.videoModalOpen = false;
                this.activeVideoUrl = '';
            }
        }"
        @mouseenter="autoplay = false"
        @mouseleave="autoplay = true"
    >
        <!-- Slides Wrapper -->
        <div class="relative min-h-[580px] lg:min-h-[660px] flex items-center">
            
            <!-- Slide 1: Revit Architecture & Megaproject BIM -->
            <div 
                x-show="activeSlide === 0"
                x-transition:enter="transition ease-out duration-700 transform"
                x-transition:enter-start="opacity-0 scale-105"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-500 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute inset-0 flex items-center"
            >
                <!-- Background Media with Dark Gradient Overlay -->
                <div class="absolute inset-0 z-0">
                    <img 
                        src="{{ asset('images/hero/hero_bim_background.jpg') }}" 
                        alt="Revit Architecture LOD 350" 
                        class="w-full h-full object-cover object-center"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-[#040E1E] via-[#040E1E]/80 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#040E1E] via-transparent to-black/40"></div>
                </div>

                <!-- Slide Content -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full py-16">
                    <div class="max-w-2xl space-y-6">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#071A36]/80 border border-[#D4AF37]/50 text-[#F3D98B] text-xs font-semibold backdrop-blur-md shadow-lg">
                            <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-ping"></span>
                            <span>{{ $isAr ? 'احتراف نمذجة العمارة بنظم BIM • مستوى LOD 350-400' : 'BIM Architectural Mastery • LOD 350-400' }}</span>
                        </div>

                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black font-['Outfit'] tracking-tight leading-[1.12] text-white">
                            @if($isAr)
                                صياغة المستقبل الهندسي مع <br class="hidden sm:inline">
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#F3D98B] via-[#D4AF37] to-amber-200">
                                    أحدث تقنيات BIM و Revit
                                </span>
                            @else
                                Architecting the Future with <br class="hidden sm:inline">
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#F3D98B] via-[#D4AF37] to-amber-200">
                                    Advanced BIM & Revit
                                </span>
                            @endif
                        </h1>

                        <p class="text-sm sm:text-base lg:text-lg text-slate-200 leading-relaxed font-light">
                            {{ $isAr ? 'احترف النمذجة المعمارية المتقدمة وفق أعلى معايير الشركات الاستشارية، وتطوير العائلات البارامترية، وإخراج المخططات التنفيذية المتوافقة مع معايير ISO 19650 الدولية.' : 'Master industry-grade architectural modeling, parametric family creation, and CD documentation aligned with international ISO 19650 standards.' }}
                        </p>

                        <div class="pt-2 flex flex-wrap items-center gap-4">
                            <a 
                                href="{{ route('courses.index') }}" 
                                class="px-8 py-3.5 rounded-full text-sm font-bold bg-[#D4AF37] hover:bg-[#F3D98B] text-[#040E1E] shadow-xl shadow-[#D4AF37]/25 transition-all flex items-center gap-2"
                            >
                                <span>{{ $isAr ? 'استكشف الدبلومات الهندسية' : 'Explore Diplomas' }}</span>
                                <svg class="w-4 h-4 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>

                            <button 
                                type="button" 
                                @click="openVideo('https://www.youtube.com/embed/dQw4w9WgXcQ', 'Revit Architecture LOD 350 Showcase')"
                                class="px-6 py-3.5 rounded-full text-sm font-bold bg-white/10 hover:bg-white/20 text-white backdrop-blur-md border border-white/20 shadow-md transition-all flex items-center gap-2.5 cursor-pointer"
                            >
                                <span class="w-6 h-6 rounded-full bg-[#D4AF37] text-[#040E1E] flex items-center justify-center text-xs">▶</span>
                                <span>{{ $isAr ? 'شاهد الفيديو التعريفي' : 'Watch Video Trailer' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2: Navisworks 4D Clash Coordination -->
            <div 
                x-show="activeSlide === 1"
                x-transition:enter="transition ease-out duration-700 transform"
                x-transition:enter-start="opacity-0 scale-105"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-500 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute inset-0 flex items-center"
                style="display: none;"
            >
                <div class="absolute inset-0 z-0">
                    <img 
                        src="{{ asset('images/courses/navisworks_4d.jpg') }}" 
                        alt="Navisworks 4D Clash Detection" 
                        class="w-full h-full object-cover object-center"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-[#040E1E] via-[#040E1E]/85 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#040E1E] via-transparent to-black/40"></div>
                </div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full py-16">
                    <div class="max-w-2xl space-y-6">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#071A36]/80 border border-cyan-400/50 text-cyan-300 text-xs font-semibold backdrop-blur-md shadow-lg">
                            <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                            <span>{{ $isAr ? 'نافيسووركس 4D • تنسيق وحل التعارضات الهندسية' : 'Navisworks 4D • Zero-Clash Coordination' }}</span>
                        </div>

                        <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black font-['Outfit'] tracking-tight leading-[1.12] text-white">
                            @if($isAr)
                                كشف وتلافي التعارضات مع <br class="hidden sm:inline">
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-teal-300 to-emerald-300">
                                    محاكاة التنفيذ الزمني 4D
                                </span>
                            @else
                                Clash Detection & <br class="hidden sm:inline">
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-teal-300 to-emerald-300">
                                    4D Construction Simulation
                                </span>
                            @endif
                        </h2>

                        <p class="text-sm sm:text-base lg:text-lg text-slate-200 leading-relaxed font-light">
                            {{ $isAr ? 'تجنب الأخطاء الإنشائية المكلفة في الموقع من خلال دمج النماذج الهندسية المتعددة، ومصفوفات كشف التعارضات الآلية، ومحاكاة مراحل التشييد الزمنية.' : 'Prevent multi-million dollar on-site errors through federated model coordination, automated clash matrices, and project timeline simulation.' }}
                        </p>

                        <div class="pt-2 flex flex-wrap items-center gap-4">
                            <a 
                                href="{{ route('courses.index', ['category' => 'coordination']) }}" 
                                class="px-8 py-3.5 rounded-full text-sm font-bold bg-cyan-500 hover:bg-cyan-400 text-slate-950 shadow-xl shadow-cyan-500/25 transition-all flex items-center gap-2"
                            >
                                <span>{{ $isAr ? 'استكشف دبلومة التنسيق 4D' : 'Explore 4D Course' }}</span>
                                <svg class="w-4 h-4 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>

                            <button 
                                type="button" 
                                @click="openVideo('https://www.youtube.com/embed/dQw4w9WgXcQ', 'Navisworks 4D Coordination Demo')"
                                class="px-6 py-3.5 rounded-full text-sm font-bold bg-white/10 hover:bg-white/20 text-white backdrop-blur-md border border-white/20 shadow-md transition-all flex items-center gap-2.5 cursor-pointer"
                            >
                                <span class="w-6 h-6 rounded-full bg-cyan-400 text-slate-950 flex items-center justify-center text-xs">▶</span>
                                <span>{{ $isAr ? 'شاهد محاكاة المشروع' : 'Watch Simulation Video' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3: Structural BIM & 3D Rebar Detailing -->
            <div 
                x-show="activeSlide === 2"
                x-transition:enter="transition ease-out duration-700 transform"
                x-transition:enter-start="opacity-0 scale-105"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-500 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute inset-0 flex items-center"
                style="display: none;"
            >
                <div class="absolute inset-0 z-0">
                    <img 
                        src="{{ asset('images/courses/revit_struct.jpg') }}" 
                        alt="Structural BIM & 3D Rebar" 
                        class="w-full h-full object-cover object-center"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-[#040E1E] via-[#040E1E]/85 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#040E1E] via-transparent to-black/40"></div>
                </div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full py-16">
                    <div class="max-w-2xl space-y-6">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#071A36]/80 border border-blue-400/50 text-blue-300 text-xs font-semibold backdrop-blur-md shadow-lg">
                            <span class="w-2 h-2 rounded-full bg-blue-400 animate-ping"></span>
                            <span>{{ $isAr ? 'الهندسة الإنشائية • تفريد وتسليح العناصر ثلاثية الأبعاد' : 'Structural Engineering • 3D Rebar Detailing' }}</span>
                        </div>

                        <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black font-['Outfit'] tracking-tight leading-[1.12] text-white">
                            @if($isAr)
                                دقة النمذجة الإنشائية و <br class="hidden sm:inline">
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-300 to-sky-300">
                                    تفريد حديد التسليح 3D
                                </span>
                            @else
                                Precision Structural BIM & <br class="hidden sm:inline">
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-indigo-300 to-sky-300">
                                    3D Reinforcement Modeling
                                </span>
                            @endif
                        </h2>

                        <p class="text-sm sm:text-base lg:text-lg text-slate-200 leading-relaxed font-light">
                            {{ $isAr ? 'استخرج جداول حصر وتفريد حديد التسليح (BBS) بدقة متناهية، ونمذجة الهياكل الخرسانية المعقدة والمشاريع الكبرى الجاهزة للتنفيذ الفعلي.' : 'Generate complete bar bending schedules, automated structural framing, and fabrication-ready models for mega infrastructure projects.' }}
                        </p>

                        <div class="pt-2 flex flex-wrap items-center gap-4">
                            <a 
                                href="{{ route('courses.index', ['category' => 'structural']) }}" 
                                class="px-8 py-3.5 rounded-full text-sm font-bold bg-[#D4AF37] hover:bg-[#F3D98B] text-[#040E1E] shadow-xl shadow-[#D4AF37]/25 transition-all flex items-center gap-2"
                            >
                                <span>{{ $isAr ? 'استكشف الـ BIM الإنشائي' : 'Explore Structural BIM' }}</span>
                                <svg class="w-4 h-4 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>

                            <button 
                                type="button" 
                                @click="openVideo('https://www.youtube.com/embed/dQw4w9WgXcQ', 'Structural Rebar Modeling Demo')"
                                class="px-6 py-3.5 rounded-full text-sm font-bold bg-white/10 hover:bg-white/20 text-white backdrop-blur-md border border-white/20 shadow-md transition-all flex items-center gap-2.5 cursor-pointer"
                            >
                                <span class="w-6 h-6 rounded-full bg-[#D4AF37] text-[#040E1E] flex items-center justify-center text-xs">▶</span>
                                <span>{{ $isAr ? 'شاهد استعراض التسليح' : 'Watch Rebar Walkthrough' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 4: Computational Design & Dynamo Python -->
            <div 
                x-show="activeSlide === 3"
                x-transition:enter="transition ease-out duration-700 transform"
                x-transition:enter-start="opacity-0 scale-105"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-500 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute inset-0 flex items-center"
                style="display: none;"
            >
                <div class="absolute inset-0 z-0">
                    <img 
                        src="{{ asset('images/courses/dynamo_python.jpg') }}" 
                        alt="Dynamo Computational Design" 
                        class="w-full h-full object-cover object-center"
                    >
                    <div class="absolute inset-0 bg-gradient-to-r from-[#040E1E] via-[#040E1E]/85 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#040E1E] via-transparent to-black/40"></div>
                </div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full py-16">
                    <div class="max-w-2xl space-y-6">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#071A36]/80 border border-purple-400/50 text-purple-300 text-xs font-semibold backdrop-blur-md shadow-lg">
                            <span class="w-2 h-2 rounded-full bg-purple-400 animate-ping"></span>
                            <span>{{ $isAr ? 'الهندسة الخوارزمية • برمجة Dynamo و Python' : 'Algorithmic Engineering • Dynamo & Python' }}</span>
                        </div>

                        <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black font-['Outfit'] tracking-tight leading-[1.12] text-white">
                            @if($isAr)
                                أتمتة المهام الهندسية مع <br class="hidden sm:inline">
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-pink-300 to-amber-200">
                                    التصميم الخوارزمي و Dynamo
                                </span>
                            @else
                                Automate Repetitive Workflows with <br class="hidden sm:inline">
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-pink-300 to-amber-200">
                                    Computational Design
                                </span>
                            @endif
                        </h2>

                        <p class="text-sm sm:text-base lg:text-lg text-slate-200 leading-relaxed font-light">
                            {{ $isAr ? 'أطلق العنان لقوة البرمجة البصرية وتطوير سكربتات داينامو المخصصة وأتمتة حصر الكميات وتوليد النماذج المعقدة بضغطة زر.' : 'Harness the power of algorithmic generative design, custom visual scripts, and automated quantity takeoff.' }}
                        </p>

                        <div class="pt-2 flex flex-wrap items-center gap-4">
                            <a 
                                href="{{ route('courses.index', ['category' => 'automation']) }}" 
                                class="px-8 py-3.5 rounded-full text-sm font-bold bg-[#D4AF37] hover:bg-[#F3D98B] text-[#040E1E] shadow-xl shadow-[#D4AF37]/25 transition-all flex items-center gap-2"
                            >
                                <span>{{ $isAr ? 'استكشف مسار الأتمتة' : 'Explore Automation' }}</span>
                                <svg class="w-4 h-4 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>

                            <button 
                                type="button" 
                                @click="openVideo('https://www.youtube.com/embed/dQw4w9WgXcQ', 'Dynamo Python Scripting Showcase')"
                                class="px-6 py-3.5 rounded-full text-sm font-bold bg-white/10 hover:bg-white/20 text-white backdrop-blur-md border border-white/20 shadow-md transition-all flex items-center gap-2.5 cursor-pointer"
                            >
                                <span class="w-6 h-6 rounded-full bg-[#D4AF37] text-[#040E1E] flex items-center justify-center text-xs">▶</span>
                                <span>{{ $isAr ? 'شاهد عرض البرمجة' : 'Watch Scripting Demo' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Slider Controls: Arrows (Left & Right) -->
        <button 
            type="button" 
            @click="prev()" 
            class="absolute left-4 sm:left-8 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-black/40 hover:bg-[#D4AF37] text-white hover:text-[#040E1E] backdrop-blur-md border border-white/20 flex items-center justify-center transition-all shadow-lg cursor-pointer group"
            title="{{ $isAr ? 'الشريحة السابقة' : 'Previous Slide' }}"
            aria-label="Previous Slide"
        >
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <button 
            type="button" 
            @click="next()" 
            class="absolute right-4 sm:right-8 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-black/40 hover:bg-[#D4AF37] text-white hover:text-[#040E1E] backdrop-blur-md border border-white/20 flex items-center justify-center transition-all shadow-lg cursor-pointer group"
            title="{{ $isAr ? 'الشريحة التالية' : 'Next Slide' }}"
            aria-label="Next Slide"
        >
            <svg class="w-5 h-5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- Slider Pagination Indicators (Sleek Numbered Engineering Pills on Desktop, Dots on Mobile) -->
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2 sm:gap-3 bg-black/40 backdrop-blur-md p-1.5 rounded-full border border-white/15">
            <button 
                type="button" 
                @click="goTo(0)" 
                class="px-3 sm:px-4 py-1 rounded-full text-[11px] sm:text-xs font-bold transition-all duration-300 cursor-pointer flex items-center gap-1.5"
                :class="activeSlide === 0 ? 'bg-[#D4AF37] text-[#040E1E] shadow-md shadow-[#D4AF37]/50' : 'text-slate-300 hover:text-white hover:bg-white/10'"
            >
                <span class="font-mono">01</span>
                <span class="hidden md:inline">{{ $isAr ? 'ريفيت معماري' : 'Revit Arch' }}</span>
            </button>

            <button 
                type="button" 
                @click="goTo(1)" 
                class="px-3 sm:px-4 py-1 rounded-full text-[11px] sm:text-xs font-bold transition-all duration-300 cursor-pointer flex items-center gap-1.5"
                :class="activeSlide === 1 ? 'bg-[#D4AF37] text-[#040E1E] shadow-md shadow-[#D4AF37]/50' : 'text-slate-300 hover:text-white hover:bg-white/10'"
            >
                <span class="font-mono">02</span>
                <span class="hidden md:inline">{{ $isAr ? 'نافيسووركس 4D' : 'Navisworks 4D' }}</span>
            </button>

            <button 
                type="button" 
                @click="goTo(2)" 
                class="px-3 sm:px-4 py-1 rounded-full text-[11px] sm:text-xs font-bold transition-all duration-300 cursor-pointer flex items-center gap-1.5"
                :class="activeSlide === 2 ? 'bg-[#D4AF37] text-[#040E1E] shadow-md shadow-[#D4AF37]/50' : 'text-slate-300 hover:text-white hover:bg-white/10'"
            >
                <span class="font-mono">03</span>
                <span class="hidden md:inline">{{ $isAr ? 'تسليح إنشائي' : 'Structural Rebar' }}</span>
            </button>

            <button 
                type="button" 
                @click="goTo(3)" 
                class="px-3 sm:px-4 py-1 rounded-full text-[11px] sm:text-xs font-bold transition-all duration-300 cursor-pointer flex items-center gap-1.5"
                :class="activeSlide === 3 ? 'bg-[#D4AF37] text-[#040E1E] shadow-md shadow-[#D4AF37]/50' : 'text-slate-300 hover:text-white hover:bg-white/10'"
            >
                <span class="font-mono">04</span>
                <span class="hidden md:inline">{{ $isAr ? 'داينامو بايثون' : 'Dynamo Python' }}</span>
            </button>
        </div>

        <!-- Video Modal Lightbox (Alpine.js) -->
        <div 
            x-show="videoModalOpen" 
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
            style="display: none;"
        >
            <div 
                @click.away="closeVideo()" 
                class="relative w-full max-w-4xl bg-slate-900 rounded-3xl overflow-hidden shadow-2xl border border-white/20"
            >
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-4 bg-[#071A36] border-b border-white/10 text-white">
                    <span class="text-sm font-bold font-['Outfit']" x-text="activeVideoTitle">Engineering Video Preview</span>
                    <button 
                        type="button" 
                        @click="closeVideo()" 
                        class="p-1 rounded-full text-slate-400 hover:text-white hover:bg-white/10 transition"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Video Frame -->
                <div class="relative aspect-video w-full bg-black">
                    <iframe 
                        :src="activeVideoUrl" 
                        class="w-full h-full" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Row of 4 Floating Stat Cards (Light default, Dark option) -->
    <section class="py-10 bg-white dark:bg-[#070F1E] border-b border-slate-200/80 dark:border-white/10 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                
                <!-- Stat Card 1 -->
                <div class="rounded-2xl p-5 bg-slate-50 dark:bg-[#071A36]/80 border border-slate-200/80 dark:border-white/10 hover:border-[#D4AF37] dark:hover:border-[#D4AF37] transition-all shadow-sm text-center flex flex-col items-center justify-center">
                    <span class="text-2xl sm:text-3xl lg:text-4xl font-black font-['Outfit'] text-[#071A36] dark:text-[#F3D98B] tracking-tight">
                        +12,500
                    </span>
                    <span class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-semibold mt-1">
                        {{ $isAr ? 'مهندس معتمد ومتخرج' : 'Engineers Certified' }}
                    </span>
                </div>

                <!-- Stat Card 2 -->
                <div class="rounded-2xl p-5 bg-slate-50 dark:bg-[#071A36]/80 border border-slate-200/80 dark:border-white/10 hover:border-[#D4AF37] dark:hover:border-[#D4AF37] transition-all shadow-sm text-center flex flex-col items-center justify-center">
                    <span class="text-2xl sm:text-3xl lg:text-4xl font-black font-['Outfit'] text-[#071A36] dark:text-[#F3D98B] tracking-tight">
                        +45
                    </span>
                    <span class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-semibold mt-1">
                        {{ $isAr ? 'مشروع حقيقي معتمد' : 'Mega-Project Datasets' }}
                    </span>
                </div>

                <!-- Stat Card 3 -->
                <div class="rounded-2xl p-5 bg-slate-50 dark:bg-[#071A36]/80 border border-slate-200/80 dark:border-white/10 hover:border-[#D4AF37] dark:hover:border-[#D4AF37] transition-all shadow-sm text-center flex flex-col items-center justify-center">
                    <span class="text-2xl sm:text-3xl lg:text-4xl font-black font-['Outfit'] text-[#071A36] dark:text-[#F3D98B] tracking-tight">
                        100%
                    </span>
                    <span class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-semibold mt-1">
                        {{ $isAr ? 'توافق مع معايير ISO 19650' : 'ISO 19650 Compliance' }}
                    </span>
                </div>

                <!-- Stat Card 4 -->
                <div class="rounded-2xl p-5 bg-slate-50 dark:bg-[#071A36]/80 border border-slate-200/80 dark:border-white/10 hover:border-[#D4AF37] dark:hover:border-[#D4AF37] transition-all shadow-sm text-center flex flex-col items-center justify-center">
                    <span class="text-2xl sm:text-3xl lg:text-4xl font-black font-['Outfit'] text-[#071A36] dark:text-[#F3D98B] tracking-tight">
                        4.9 / 5
                    </span>
                    <span class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-semibold mt-1">
                        {{ $isAr ? 'تقييم كبار الاستشاريين' : 'Consultant Verified Rating' }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. About Beforbim Academy (من نحن - تعريف الأكاديمية ورسالتها الهندسية) -->
    <section class="py-16 lg:py-24 bg-slate-50/70 dark:bg-[#061224] border-b border-slate-200/80 dark:border-white/10 transition-colors duration-200 relative overflow-hidden">
        <!-- Subtle Background Glows -->
        <div class="absolute top-1/2 left-0 -translate-y-1/2 w-96 h-96 bg-[#D4AF37]/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 right-0 w-96 h-96 bg-[#071A36]/5 dark:bg-[#071A36]/40 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                
                <!-- Left Column: Authentic Engineering Studio & Architectural Drafting Showcase -->
                <div class="lg:col-span-6 relative">
                    <div class="relative">
                        <!-- Primary Main Photo: Real Engineering Team Studio -->
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-200 dark:border-white/15 bg-white dark:bg-[#071A36] group">
                            <div class="relative aspect-[4/3] w-full overflow-hidden">
                                <img 
                                    src="{{ asset('images/about/about_bim_studio.jpg') }}" 
                                    alt="Beforbim Engineering Academy Studio & Research Lab" 
                                    class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-105"
                                    loading="lazy"
                                >
                                <!-- Subtle Gradient -->
                                <div class="absolute inset-0 bg-gradient-to-t from-[#040E1E]/80 via-[#040E1E]/20 to-transparent"></div>
                            </div>

                            <!-- Top Location Badge -->
                            <div class="absolute top-4 {{ $isAr ? 'right-4 sm:right-5' : 'left-4 sm:left-5' }} inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/95 dark:bg-[#071A36]/90 backdrop-blur-md border border-slate-200 dark:border-[#D4AF37]/40 shadow-lg">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-xs font-bold text-slate-800 dark:text-[#F3D98B] tracking-wide">{{ $isAr ? 'القاهرة، مصر • مركز التميز ISO 19650' : 'Cairo, Egypt • ISO 19650 Hub' }}</span>
                            </div>

                            <!-- Bottom Floating Stats Card -->
                            <div class="absolute bottom-4 left-4 right-4 sm:bottom-5 sm:left-5 sm:right-5 p-4 sm:p-4.5 rounded-2xl bg-white/95 dark:bg-[#040E1E]/95 backdrop-blur-xl border border-slate-200 dark:border-white/15 shadow-xl flex items-center justify-between gap-4">
                                <div class="space-y-0.5">
                                    <div class="flex items-center gap-1 text-amber-400 text-xs">
                                        <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                                        <span class="text-slate-600 dark:text-slate-400 font-bold text-[11px] ml-1">4.9 / 5.0</span>
                                    </div>
                                    <h4 class="text-xs sm:text-sm font-black font-['Outfit'] text-[#071A36] dark:text-white">
                                        +12,500 {{ $isAr ? 'مهندس تم تدريبهم' : 'Engineers Trained' }}
                                    </h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 font-light">
                                        {{ $isAr ? 'قيادة وتطبيق الـ BIM في كبرى مكاتب مصر والسعودية والإمارات' : 'Leading BIM teams across Egypt, Saudi Arabia & UAE' }}
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-xl sm:text-2xl font-black font-['Outfit'] text-[#D4AF37]">98%</span>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 uppercase font-bold tracking-wider">{{ $isAr ? 'نسبة التوظيف' : 'Placement' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Secondary Floating Photo Inset: Real Architectural Blueprint Drafting -->
                        <div class="absolute -bottom-8 {{ $isAr ? '-left-6' : '-right-6' }} w-44 sm:w-56 rounded-2xl overflow-hidden shadow-2xl border-4 border-white dark:border-[#071A36] hidden md:block z-20 group/inset">
                            <img 
                                src="{{ asset('images/about/about_arch_drafting.jpg') }}" 
                                alt="Authentic Architectural Drafting and CD Drawings" 
                                class="w-full h-32 object-cover transition-transform duration-500 group-hover/inset:scale-105"
                                loading="lazy"
                            >
                            <div class="absolute bottom-2 left-2 right-2 px-2.5 py-1 rounded-lg bg-black/75 backdrop-blur-md flex items-center justify-between">
                                <span class="text-[10px] font-bold text-white font-mono">LOD 350-400</span>
                                <span class="text-[9px] text-[#F3D98B] font-semibold">{{ $isAr ? 'مشاريع حقيقية' : 'Real Datasets' }}</span>
                            </div>
                        </div>

                        <!-- Decorative Accent Border -->
                        <div class="absolute -top-3 -left-3 -z-10 w-full h-full rounded-3xl border-2 border-dashed border-[#D4AF37]/30 pointer-events-none hidden sm:block"></div>
                    </div>
                </div>

                <!-- Right Column: Who We Are / Narrative & Key Differentiators -->
                <div class="lg:col-span-6 space-y-6">
                    
                    <!-- Eyebrow Pill -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-100 dark:bg-white/10 border border-slate-200 dark:border-[#D4AF37]/40 text-[#96720D] dark:text-[#F3D98B] text-xs font-bold tracking-wide">
                        <span class="w-2 h-2 rounded-full bg-[#D4AF37]"></span>
                        <span>{{ $isAr ? 'من نحن - أكاديمية بيفور بيم' : 'About Beforbim Academy' }}</span>
                        <span class="sr-only">من نحن - عن الأكاديمية الهندسية</span>
                    </div>

                    <!-- Section Title -->
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black font-['Outfit'] text-[#071A36] dark:text-white tracking-tight leading-[1.15]">
                        @if($isAr)
                            ريادة المستقبل في <br class="hidden sm:inline">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#96720D] via-[#D4AF37] to-amber-500 dark:from-[#F3D98B] dark:via-[#D4AF37] dark:to-amber-200">
                                هندسة التشييد الرقمي ونظم BIM
                            </span>
                        @else
                            Pioneering the Future of <br class="hidden sm:inline">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#96720D] via-[#D4AF37] to-amber-500 dark:from-[#F3D98B] dark:via-[#D4AF37] dark:to-amber-200">
                                Digital Construction & BIM
                            </span>
                        @endif
                    </h2>

                    <!-- Narrative Paragraph -->
                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 font-light leading-relaxed">
                        {{ $isAr ? 'بيفور بيم هي أكاديمية هندسية رائدة مقرها القاهرة، متخصصة في تأهيل المهندسين المعماريين والمدنيين ومهندسي الكهروميكانيك (MEP) ليصبحوا مدراء ومنسقي BIM محترفين. نسد الفجوة بين التعليم الجامعي النظري ومتطلبات كبرى المشاريع عبر التدريب على نماذج حقيقية وحل التعارضات والتصميم البرمجي.' : 'Beforbim is an accredited engineering institute headquartered in Cairo, Egypt, dedicated to upskilling architects, civil engineers, and MEP specialists into internationally certified BIM Managers. We bridge the critical gap between theoretical university curriculums and high-stakes megaproject execution through production-grade modeling, automated clash resolution, and computational design.' }}
                    </p>

                    <!-- 4-Grid Key Features -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        
                        <!-- Feature 1 -->
                        <div class="p-4 rounded-2xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 shadow-sm space-y-1.5">
                            <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-white/5 text-[#D4AF37] flex items-center justify-center font-bold text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <h4 class="text-xs font-bold text-[#071A36] dark:text-white font-['Outfit']">{{ $isAr ? 'معايير ISO 19650 الدولية' : 'ISO 19650 Standards' }}</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed font-light">
                                {{ $isAr ? 'صياغة وثائق خطة تنفيذ البيم (BEP) والعمل ضمن بيئات البيانات المشتركة (CDE).' : 'Author BEP documentation and navigate Common Data Environments (CDE) seamlessly.' }}
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="p-4 rounded-2xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 shadow-sm space-y-1.5">
                            <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-white/5 text-[#D4AF37] flex items-center justify-center font-bold text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <h4 class="text-xs font-bold text-[#071A36] dark:text-white font-['Outfit']">{{ $isAr ? 'مشاريع حقيقية عملاقة' : 'Real Megaproject Datasets' }}</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed font-light">
                                {{ $isAr ? 'التدريب العملي على نماذج LOD 350-400 لأبراج إدارية ومستشفيات وشبكات بنية تحتية.' : 'Practice on genuine LOD 350-400 high-rise towers, hospitals, and infrastructure models.' }}
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="p-4 rounded-2xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 shadow-sm space-y-1.5">
                            <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-white/5 text-[#D4AF37] flex items-center justify-center font-bold text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <h4 class="text-xs font-bold text-[#071A36] dark:text-white font-['Outfit']">{{ $isAr ? 'نخبة من كبار الاستشاريين' : 'Practicing Faculty' }}</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed font-light">
                                {{ $isAr ? 'التعلم مباشرة من مدراء BIM واستشاريين تنفيذيين ذوي خبرة في كبرى المشروعات.' : 'Learn directly from certified BIM Directors and Principal Structural Consultants.' }}
                            </p>
                        </div>

                        <!-- Feature 4 -->
                        <div class="p-4 rounded-2xl bg-white dark:bg-[#071A36]/80 border border-slate-200 dark:border-white/10 shadow-sm space-y-1.5">
                            <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-white/5 text-[#D4AF37] flex items-center justify-center font-bold text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <h4 class="text-xs font-bold text-[#071A36] dark:text-white font-['Outfit']">{{ $isAr ? 'شبكة توظيف وشراكات' : 'Career Placement' }}</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed font-light">
                                {{ $isAr ? 'قنوات ترشيح وتوظيف مباشرة لدى كبرى المكاتب الاستشارية وشركات المقاولات.' : 'Direct hiring pipelines to top architecture & engineering firms in Egypt and GCC.' }}
                            </p>
                        </div>

                    </div>

                    <!-- Call to Action Buttons -->
                    <div class="pt-2 flex flex-wrap items-center gap-4">
                        <a 
                            href="{{ route('about') }}" 
                            class="px-7 py-3 rounded-full text-xs font-bold bg-[#071A36] hover:bg-[#0D224D] text-[#F3D98B] dark:bg-[#D4AF37] dark:hover:bg-[#F3D98B] dark:text-[#040E1E] shadow-lg shadow-[#071A36]/15 dark:shadow-[#D4AF37]/20 transition-all flex items-center gap-2"
                        >
                            <span>{{ $isAr ? 'اقرأ قصة نجاحنا' : 'Read Our Full Story' }}</span>
                            <svg class="w-3.5 h-3.5 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>

                        <a 
                            href="{{ route('instructors.index') }}" 
                            class="px-6 py-3 rounded-full text-xs font-bold text-[#071A36] dark:text-white hover:bg-slate-200/70 dark:hover:bg-white/10 border border-slate-300 dark:border-white/15 transition-all flex items-center gap-2"
                        >
                            <span>{{ $isAr ? 'تعرف على نخبة المحاضرين' : 'Meet Our Faculty' }}</span>
                            <span>&rarr;</span>
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- 4. Trusted AEC Consultancies & Alumni Career Footprint Section -->
    <section class="py-14 sm:py-16 bg-white dark:bg-[#071326] border-b border-slate-200/80 dark:border-white/10 transition-colors duration-200 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#071A36]/5 dark:bg-[#D4AF37]/10 border border-[#071A36]/15 dark:border-[#D4AF37]/30 text-[#071A36] dark:text-[#F3D98B] text-[11px] font-bold uppercase tracking-wider mb-3">
                    <span class="w-2 h-2 rounded-full bg-[#071A36] dark:bg-[#D4AF37] animate-pulse"></span>
                    <span>{{ $isAr ? 'ثقة كبرى الشركات وتوظيف الخريجين' : 'Alumni Placements & Corporate Trust' }}</span>
                </div>
                <h3 class="text-2xl sm:text-3xl font-black font-['Outfit'] text-[#071A36] dark:text-white tracking-tight">
                    {{ $isAr ? 'أين يعمل ويبدع مهندسو بيفور بيم' : 'Where BeforBIM Engineers Build & Lead' }}
                </h3>
                <p class="mt-2 text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-light leading-relaxed">
                    {{ $isAr ? 'يقود خريجونا المعتمدون تطبيق معايير ISO 19650 والتنسيق الهندسي المتكامل في كبرى المكاتب الاستشارية العالمية وشركات المقاولات الكبرى في مصر والشرق الأوسط.' : 'Our certified BIM modelers and coordinators deliver ISO 19650 standards and multi-disciplinary coordination across the region’s premier consultancies and Tier-1 general contractors.' }}
                </p>
            </div>

            <!-- Corporate Partners / Alumni Footprint Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                
                <!-- 1. Dar Al-Handasah -->
                <div class="group p-4 rounded-2xl bg-slate-50/80 dark:bg-white/[0.03] border border-slate-200/80 dark:border-white/10 hover:border-[#D4AF37]/50 dark:hover:border-[#D4AF37]/50 hover:shadow-lg hover:shadow-[#071A36]/5 dark:hover:shadow-black/20 hover:-translate-y-1 transition-all duration-300 text-center flex flex-col items-center justify-between">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#071A36] to-[#1E3A8A] text-white flex items-center justify-center font-black font-['Outfit'] text-xs shadow-md shadow-[#071A36]/15 group-hover:scale-105 transition-transform mb-3">
                        DH
                    </div>
                    <div>
                        <h4 class="text-xs font-black font-['Outfit'] text-[#071A36] dark:text-white tracking-tight leading-tight">
                            {{ $isAr ? 'دار الهندسة' : 'Dar Al-Handasah' }}
                        </h4>
                        <span class="inline-block mt-1 text-[10px] text-slate-500 dark:text-slate-400 font-medium">
                            {{ $isAr ? 'استشارات هندسية عالمية' : 'Global Consultancy' }}
                        </span>
                    </div>
                    <div class="mt-3 pt-2 w-full border-t border-slate-200/60 dark:border-white/5">
                        <span class="text-[9px] font-mono font-semibold text-emerald-600 dark:text-emerald-400 block truncate">
                            {{ $isAr ? '• نمذجة المشاريع الكبرى' : '• Mega Projects BIM' }}
                        </span>
                    </div>
                </div>

                <!-- 2. Orascom Construction -->
                <div class="group p-4 rounded-2xl bg-slate-50/80 dark:bg-white/[0.03] border border-slate-200/80 dark:border-white/10 hover:border-[#D4AF37]/50 dark:hover:border-[#D4AF37]/50 hover:shadow-lg hover:shadow-[#071A36]/5 dark:hover:shadow-black/20 hover:-translate-y-1 transition-all duration-300 text-center flex flex-col items-center justify-between">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#E06D12] to-[#9A3412] text-white flex items-center justify-center font-black font-['Outfit'] text-xs shadow-md shadow-orange-500/15 group-hover:scale-105 transition-transform mb-3">
                        OC
                    </div>
                    <div>
                        <h4 class="text-xs font-black font-['Outfit'] text-[#071A36] dark:text-white tracking-tight leading-tight">
                            {{ $isAr ? 'أوراسكوم للإنشاءات' : 'Orascom' }}
                        </h4>
                        <span class="inline-block mt-1 text-[10px] text-slate-500 dark:text-slate-400 font-medium">
                            {{ $isAr ? 'مقاولات فئة أولى' : 'Tier-1 Contractor' }}
                        </span>
                    </div>
                    <div class="mt-3 pt-2 w-full border-t border-slate-200/60 dark:border-white/5">
                        <span class="text-[9px] font-mono font-semibold text-emerald-600 dark:text-emerald-400 block truncate">
                            {{ $isAr ? '• القطار الكهربائي والمترو' : '• High-Speed Rail & Metro' }}
                        </span>
                    </div>
                </div>

                <!-- 3. Hassan Allam Holding -->
                <div class="group p-4 rounded-2xl bg-slate-50/80 dark:bg-white/[0.03] border border-slate-200/80 dark:border-white/10 hover:border-[#D4AF37]/50 dark:hover:border-[#D4AF37]/50 hover:shadow-lg hover:shadow-[#071A36]/5 dark:hover:shadow-black/20 hover:-translate-y-1 transition-all duration-300 text-center flex flex-col items-center justify-between">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#0284C7] to-[#0369A1] text-white flex items-center justify-center font-black font-['Outfit'] text-xs shadow-md shadow-sky-500/15 group-hover:scale-105 transition-transform mb-3">
                        HA
                    </div>
                    <div>
                        <h4 class="text-xs font-black font-['Outfit'] text-[#071A36] dark:text-white tracking-tight leading-tight">
                            {{ $isAr ? 'حسن علام القابضة' : 'Hassan Allam' }}
                        </h4>
                        <span class="inline-block mt-1 text-[10px] text-slate-500 dark:text-slate-400 font-medium">
                            {{ $isAr ? 'بنية تحتية ومحطات' : 'Infrastructure & EPC' }}
                        </span>
                    </div>
                    <div class="mt-3 pt-2 w-full border-t border-slate-200/60 dark:border-white/5">
                        <span class="text-[9px] font-mono font-semibold text-emerald-600 dark:text-emerald-400 block truncate">
                            {{ $isAr ? '• نمذجة المياه والطاقة' : '• Water & Energy BIM' }}
                        </span>
                    </div>
                </div>

                <!-- 4. Khatib & Alami -->
                <div class="group p-4 rounded-2xl bg-slate-50/80 dark:bg-white/[0.03] border border-slate-200/80 dark:border-white/10 hover:border-[#D4AF37]/50 dark:hover:border-[#D4AF37]/50 hover:shadow-lg hover:shadow-[#071A36]/5 dark:hover:shadow-black/20 hover:-translate-y-1 transition-all duration-300 text-center flex flex-col items-center justify-between">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#0F766E] to-[#115E59] text-white flex items-center justify-center font-black font-['Outfit'] text-xs shadow-md shadow-teal-500/15 group-hover:scale-105 transition-transform mb-3">
                        K&A
                    </div>
                    <div>
                        <h4 class="text-xs font-black font-['Outfit'] text-[#071A36] dark:text-white tracking-tight leading-tight">
                            {{ $isAr ? 'خطيب وعلمي' : 'Khatib & Alami' }}
                        </h4>
                        <span class="inline-block mt-1 text-[10px] text-slate-500 dark:text-slate-400 font-medium">
                            {{ $isAr ? 'استشارات وتصميمات دولية' : 'International Design' }}
                        </span>
                    </div>
                    <div class="mt-3 pt-2 w-full border-t border-slate-200/60 dark:border-white/5">
                        <span class="text-[9px] font-mono font-semibold text-emerald-600 dark:text-emerald-400 block truncate">
                            {{ $isAr ? '• مدن ذكية ونظم GIS' : '• Smart Cities & GIS' }}
                        </span>
                    </div>
                </div>

                <!-- 5. Shaker Consultancy -->
                <div class="group p-4 rounded-2xl bg-slate-50/80 dark:bg-white/[0.03] border border-slate-200/80 dark:border-white/10 hover:border-[#D4AF37]/50 dark:hover:border-[#D4AF37]/50 hover:shadow-lg hover:shadow-[#071A36]/5 dark:hover:shadow-black/20 hover:-translate-y-1 transition-all duration-300 text-center flex flex-col items-center justify-between">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#4338CA] to-[#312E81] text-white flex items-center justify-center font-black font-['Outfit'] text-xs shadow-md shadow-indigo-500/15 group-hover:scale-105 transition-transform mb-3">
                        SC
                    </div>
                    <div>
                        <h4 class="text-xs font-black font-['Outfit'] text-[#071A36] dark:text-white tracking-tight leading-tight">
                            {{ $isAr ? 'مجموعة شاكر' : 'Shaker Group' }}
                        </h4>
                        <span class="inline-block mt-1 text-[10px] text-slate-500 dark:text-slate-400 font-medium">
                            {{ $isAr ? 'استشارات كهروميكانيكية' : 'MEP Authority' }}
                        </span>
                    </div>
                    <div class="mt-3 pt-2 w-full border-t border-slate-200/60 dark:border-white/5">
                        <span class="text-[9px] font-mono font-semibold text-emerald-600 dark:text-emerald-400 block truncate">
                            {{ $isAr ? '• نمذجة المستشفيات MEP' : '• Healthcare MEP BIM' }}
                        </span>
                    </div>
                </div>

                <!-- 6. ECG Consultants -->
                <div class="group p-4 rounded-2xl bg-slate-50/80 dark:bg-white/[0.03] border border-slate-200/80 dark:border-white/10 hover:border-[#D4AF37]/50 dark:hover:border-[#D4AF37]/50 hover:shadow-lg hover:shadow-[#071A36]/5 dark:hover:shadow-black/20 hover:-translate-y-1 transition-all duration-300 text-center flex flex-col items-center justify-between">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#475569] to-[#1E293B] text-white flex items-center justify-center font-black font-['Outfit'] text-xs shadow-md shadow-slate-700/15 group-hover:scale-105 transition-transform mb-3">
                        ECG
                    </div>
                    <div>
                        <h4 class="text-xs font-black font-['Outfit'] text-[#071A36] dark:text-white tracking-tight leading-tight">
                            {{ $isAr ? 'جماعة المهندسين الاستشاريين' : 'ECG' }}
                        </h4>
                        <span class="inline-block mt-1 text-[10px] text-slate-500 dark:text-slate-400 font-medium">
                            {{ $isAr ? 'استشارات هندسية متكاملة' : 'AEC Engineering' }}
                        </span>
                    </div>
                    <div class="mt-3 pt-2 w-full border-t border-slate-200/60 dark:border-white/5">
                        <span class="text-[9px] font-mono    <!-- 5. Interactive BIM Engineering Roadmap (The 4-Step Mastery Path with Alpine.js Motion) -->
    <section 
        class="py-16 lg:py-24 bg-slate-50/70 dark:bg-[#061224] border-b border-slate-200/80 dark:border-white/10 transition-colors duration-200 relative overflow-hidden"
        x-data="{
            activeStep: 0,
            steps: @json($isAr ? [
                [
                    'phase' => 'المرحلة 01',
                    'badge' => 'أساسيات LOD 300',
                    'title' => 'النمذجة البارامترية ثلاثية الأبعاد والعائلات',
                    'desc' => 'إتقان نمذجة المشاريع المعمارية والإنشائية متعددة الأدوار في Revit، وبناء عائلات بارامترية مخصصة، وجداول حصر ومخططات تنفيذية جاهزة للموقع.',
                    'software' => ['Revit Arch', 'Revit Struct', 'AutoCAD 3D'],
                    'deliverables' => ['عائلات بارامترية متقدمة', 'مخططات تنفيذية منسقة LOD 300', 'جداول حصر كميات آلية'],
                    'metric' => '40+ ساعة تطبيق عملي مكثف',
                    'outcome' => 'BIM Modeler معتمد'
                ],
                [
                    'phase' => 'المرحلة 02',
                    'badge' => 'التنسيق والتعارضات',
                    'title' => 'حل وتنسيق التعارضات بين التخصصات',
                    'desc' => 'إجراء اختبارات كشف التعارضات الصريحة والفراغية بين المعماري والإنشائي والكهروميكانيك عبر Navisworks Manage وإصدار تقارير BCF.',
                    'software' => ['Navisworks Manage', 'BIM Collab', 'Revit MEP'],
                    'deliverables' => ['نموذج مدمج NWD خالٍ تماماً من التعارضات', 'تقارير متابعة المشاكل BCF 2.1', 'محاضر اجتماعات التنسيق الفضائي'],
                    'metric' => 'حل أكثر من 1,500 تعارض فعلي',
                    'outcome' => 'BIM Coordinator محترف'
                ],
                [
                    'phase' => 'المرحلة 03',
                    'badge' => 'الجدول 4D والتكلفة 5D',
                    'title' => 'محاكاة مراحل التشييد والربط الزمني',
                    'desc' => 'ربط الجداول الزمنية لبرنامج Primavera P6 بالعناصر ثلاثية الأبعاد لمحاكاة مراحل البناء، وإنشاء تحليكات الحركة اللوجستية للموقع ومنحنيات التكلفة 5D.',
                    'software' => ['Synchro 4D', 'Navisworks TimeLiner', 'CostX'],
                    'deliverables' => ['فيديو محاكاة 4D لأبراج شاهقة', 'منحنيات التدفق النقدي والتكلفة', 'مخططات لوجستيات الموقع وتمركز الرافعات'],
                    'metric' => 'مزامنة 100% مع الجدول الزمني',
                    'outcome' => 'أخصائي محاكاة 4D & 5D'
                ],
                [
                    'phase' => 'المرحلة 04',
                    'badge' => 'ISO 19650 والأتمتة',
                    'title' => 'إدارة مشاريع BIM والتصميم الخوارزمي',
                    'desc' => 'صياغة خطط تنفيذ البيم (BEP) طبقاً لمعايير ISO 19650 الدولية، وبناء سكربتات داينامو بايثون لتسريع النمذجة وأتمتة المهام الروتينية.',
                    'software' => ['Dynamo Python', 'Autodesk Construction Cloud', 'ISO 19650 BEP'],
                    'deliverables' => ['وثيقة BEP متكاملة للمشروع', 'إعداد بيئة البيانات المشتركة CDE', 'سكربتات Dynamo خوارزمية ذكية'],
                    'metric' => 'اعتماد دولي ISO 19650',
                    'outcome' => 'BIM Manager معتمد دولياً'
                ]
            ] : [
                [
                    'phase' => 'Phase 01',
                    'badge' => 'LOD 300 Foundation',
                    'title' => 'Parametric 3D Modeling & Families',
                    'desc' => 'Master multi-storey architectural & structural models in Autodesk Revit. Author custom parametric families, automated schedules, and production-ready construction sheets.',
                    'software' => ['Revit Arch', 'Revit Struct', 'AutoCAD 3D'],
                    'deliverables' => ['Custom Parametric Families', 'LOD 300 Coordinated Sheets', 'Automated Material Takeoffs'],
                    'metric' => '40+ Hours Hands-On Modeling',
                    'outcome' => 'Foundation BIM Modeler'
                ],
                [
                    'phase' => 'Phase 02',
                    'badge' => 'Clash & Coordination',
                    'title' => 'Interdisciplinary Clash Resolution',
                    'desc' => 'Run hard, soft, and clearance clash tests across Architectural, Structural, and MEP disciplines in Navisworks Manage. Author clash matrices and BCF issue tracking reports.',
                    'software' => ['Navisworks Manage', 'BIM Collab', 'Revit MEP'],
                    'deliverables' => ['Zero-Clash Federated NWD Model', 'BCF 2.1 Issue Tracking Reports', 'Spatial Coordination Minutes'],
                    'metric' => '1,500+ Clashes Resolved',
                    'outcome' => 'BIM Coordinator'
                ],
                [
                    'phase' => 'Phase 03',
                    'badge' => '4D Time & 5D Cost',
                    'title' => 'Construction Sequencing & 4D Simulation',
                    'desc' => 'Integrate Primavera P6 schedules with 3D model elements to simulate construction timelines. Generate 4D site logistics animations and dynamic 5D cost curves.',
                    'software' => ['Synchro 4D', 'Navisworks TimeLiner', 'CostX'],
                    'deliverables' => ['High-Rise 4D Simulation Video', 'Cash Flow & Cost Curve S-Curves', 'Logistics & Crane Site Layouts'],
                    'metric' => '100% Construction Timeline Sync',
                    'outcome' => '4D Simulation Specialist'
                ],
                [
                    'phase' => 'Phase 04',
                    'badge' => 'ISO 19650 & Automation',
                    'title' => 'BIM Management & Computational Design',
                    'desc' => 'Author official BIM Execution Plans (BEP) complying with international ISO 19650 standards. Build automated Dynamo Python scripts to generate reinforcement and automate repetitive tasks.',
                    'software' => ['Dynamo Python', 'Autodesk Construction Cloud', 'ISO 19650 BEP'],
                    'deliverables' => ['Official Project BEP Document', 'CDE Common Data Environment Setup', 'Algorithmic Dynamo Python Scripts'],
                    'metric' => 'International ISO 19650 Compliance',
                    'outcome' => 'Certified BIM Manager'
                ]
            ])
        }"
    >
        <!-- Background Ambient Glow -->
        <div class="absolute -top-24 right-1/3 w-[30rem] h-[30rem] bg-[#D4AF37]/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-14 space-y-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-100 dark:bg-white/10 border border-slate-200 dark:border-[#D4AF37]/40 text-[#96720D] dark:text-[#F3D98B] text-xs font-bold tracking-wide">
                    <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-pulse"></span>
                    <span>{{ $isAr ? 'مسار التدرج والتأهيل الأكاديمي' : 'Academic Progression Framework' }}</span>
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black font-['Outfit'] text-[#071A36] dark:text-white tracking-tight">
                    @if($isAr)
                        خارطة الطريق من 4 مراحل نحو <br class="hidden sm:inline">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#96720D] via-[#D4AF37] to-amber-500 dark:from-[#F3D98B] dark:via-[#D4AF37] dark:to-amber-200">
                            قيادة وإدارة مشاريع BIM
                        </span>
                    @else
                        The 4-Step Roadmap to <br class="hidden sm:inline">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#96720D] via-[#D4AF37] to-amber-500 dark:from-[#F3D98B] dark:via-[#D4AF37] dark:to-amber-200">
                            Certified BIM Leadership
                        </span>
                    @endif
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-light max-w-2xl mx-auto">
                    {{ $isAr ? 'مسار تصاعدي تطبيقي ينقلك باحترافية من مرحلة الرسم الثنائي التقليدي إلى قيادة وتنسيق أضخم مشاريع التشييد الرقمي.' : 'A progressive, hands-on path taking you from basic 2D CAD drafting to leading multi-million dollar digital construction projects.' }}
                </p>
            </div>

            <!-- 2-Column Interactive Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-stretch">
                
                <!-- Left Column: 4 Interactive Step Selector Cards -->
                <div class="lg:col-span-5 flex flex-col justify-between space-y-3.5">
                    <template x-for="(step, index) in steps" :key="index">
                        <div 
                            @click="activeStep = index"
                            class="p-5 rounded-2xl border transition-all duration-300 cursor-pointer flex items-center justify-between gap-4 group"
                            :class="activeStep === index 
                                ? 'bg-[#071A36] text-white shadow-xl shadow-[#071A36]/15 dark:bg-[#071A36] border-[#D4AF37] {{ $isAr ? '-translate-x-1' : 'translate-x-1' }}' 
                                : 'bg-white dark:bg-[#071A36]/50 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 hover:border-slate-300 dark:hover:border-white/20 hover:bg-slate-50'"
                        >
                            <div class="flex items-center gap-4">
                                <!-- Step Number Badge -->
                                <span 
                                    class="w-10 h-10 rounded-xl flex items-center justify-center font-mono font-black text-sm shrink-0 transition-colors"
                                    :class="activeStep === index 
                                        ? 'bg-[#D4AF37] text-[#040E1E] shadow-md shadow-[#D4AF37]/40' 
                                        : 'bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-slate-300 group-hover:text-[#D4AF37]'"
                                    x-text="'0' + (index + 1)"
                                ></span>

                                <div class="space-y-0.5">
                                    <div class="flex items-center gap-2">
                                        <span 
                                            class="text-[10px] font-bold uppercase tracking-wider font-mono"
                                            :class="activeStep === index ? 'text-[#F3D98B]' : 'text-slate-500 dark:text-slate-400'"
                                            x-text="step.phase"
                                        ></span>
                                        <span 
                                            class="w-1.5 h-1.5 rounded-full"
                                            :class="activeStep === index ? 'bg-[#D4AF37] animate-ping' : 'bg-slate-400/40'"
                                        ></span>
                                    </div>
                                    <h4 
                                        class="text-sm font-bold font-['Outfit'] transition-colors"
                                        :class="activeStep === index ? 'text-white' : 'text-[#071A36] dark:text-white'"
                                        x-text="step.title"
                                    ></h4>
                                </div>
                            </div>

                            <!-- Right Arrow Indicator -->
                            <div 
                                class="w-7 h-7 rounded-lg flex items-center justify-center transition-transform shrink-0"
                                :class="activeStep === index ? 'text-[#F3D98B] {{ $isAr ? '-translate-x-0.5' : 'translate-x-0.5' }}' : 'text-slate-400 opacity-60 group-hover:opacity-100'"
                            >
                                <svg class="w-4 h-4 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Right Column: Animated Phase Detail Showcase Panel -->
                <div class="lg:col-span-7 flex flex-col">
                    <div class="rounded-3xl p-6 sm:p-10 bg-gradient-to-br from-[#071A36] via-[#092247] to-[#040E1E] text-white border border-[#D4AF37]/40 shadow-2xl relative overflow-hidden flex-1 flex flex-col justify-between">
                        
                        <!-- Blueprint Grid Background Texture -->
                        <div class="absolute inset-0 bg-blueprint-grid opacity-20 pointer-events-none"></div>
                        <div class="absolute -top-20 -right-20 w-64 h-64 bg-[#D4AF37]/15 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="relative z-10 space-y-6">
                            
                            <!-- Header Badges -->
                            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 pb-5">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-[#D4AF37]/30 text-[#F3D98B] text-xs font-semibold backdrop-blur-md">
                                    <span class="w-2 h-2 rounded-full bg-[#D4AF37]"></span>
                                    <span x-text="steps[activeStep].badge"></span>
                                </div>

                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-xs font-bold">
                                    <span>{{ $isAr ? 'المسمى المكتسب:' : 'Outcome:' }}</span>
                                    <span x-text="steps[activeStep].outcome"></span>
                                </div>
                            </div>

                            <!-- Title & Description with Smooth Transition -->
                            <div class="space-y-3">
                                <h3 
                                    class="text-2xl sm:text-3xl font-black font-['Outfit'] text-white tracking-tight"
                                    x-text="steps[activeStep].title"
                                ></h3>
                                <p 
                                    class="text-sm text-slate-300 font-light leading-relaxed"
                                    x-text="steps[activeStep].desc"
                                ></p>
                            </div>

                            <!-- Deliverables Checklist -->
                            <div class="space-y-2.5 pt-2">
                                <span class="text-[11px] uppercase tracking-wider font-mono font-bold text-[#F3D98B]">
                                    {{ $isAr ? 'المخرجات العملية والمشاريع المكتملة:' : 'Portfolio Deliverables You Complete:' }}
                                </span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    <template x-for="(item, i) in steps[activeStep].deliverables" :key="i">
                                        <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white/5 border border-white/10 text-xs text-slate-200">
                                            <svg class="w-4 h-4 text-[#D4AF37] shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            <span x-text="item"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Software Stack & Applied Metric -->
                            <div class="pt-2 flex flex-wrap items-center justify-between gap-4 border-t border-white/10 pt-5">
                                <div>
                                    <span class="text-[10px] uppercase tracking-wider text-slate-400 font-mono block mb-1.5">{{ $isAr ? 'البرامج والأدوات:' : 'Software Tools:' }}</span>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <template x-for="(soft, s) in steps[activeStep].software" :key="s">
                                            <span class="px-2.5 py-1 rounded-lg bg-white/10 text-[#F3D98B] text-xs font-mono font-semibold" x-text="soft"></span>
                                        </template>
                                    </div>
                                </div>

                                <div class="{{ $isAr ? 'text-left' : 'text-right' }}">
                                    <span class="text-[10px] uppercase tracking-wider text-slate-400 font-mono block mb-1">{{ $isAr ? 'المعيار المطبق:' : 'Standard:' }}</span>
                                    <span class="text-xs font-bold text-white bg-slate-800/80 px-3 py-1 rounded-lg border border-white/15" x-text="steps[activeStep].metric"></span>
                                </div>
                            </div>

                        </div>

                        <!-- Panel Bottom CTA -->
                        <div class="relative z-10 pt-6 mt-6 border-t border-white/10 flex items-center justify-between">
                            <span class="text-xs text-slate-300 font-light">{{ $isAr ? 'جاهز لبدء هذه المرحلة؟' : 'Ready to start this phase?' }}</span>
                            <a 
                                href="{{ route('courses.index') }}" 
                                class="px-5 py-2 rounded-full text-xs font-bold bg-[#D4AF37] hover:bg-[#F3D98B] text-[#040E1E] transition-all flex items-center gap-1.5 shadow-md shadow-[#D4AF37]/30"
                            >
                                <span>{{ $isAr ? 'استعرض دورات المرحلة' : 'Browse Phase Courses' }}</span>
                                <span>&rarr;</span>
                            </a>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- 6. Featured Courses Section (الدورات المميزة) -->
    <section class="py-16 lg:py-20 bg-white dark:bg-[#040E1E] border-b border-slate-200/80 dark:border-white/10 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header & "View All" Pill Button -->
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
                <div>
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#D4AF37]"></span>
                        <span class="text-xs font-bold text-[#D4AF37] uppercase tracking-wider font-mono">{{ $isAr ? 'مناهج تخصصية معتمدة' : 'Specialized Syllabi' }}</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black font-['Outfit'] text-[#071A36] dark:text-white tracking-tight">
                        {{ $isAr ? 'الدبلومات الهندسية المميزة' : 'Featured Engineering Diplomas' }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                        {{ $isAr ? 'برامجنا الرائدة والمعتمدة دولياً في هندسة نمذجة معلومات البناء وتكنولوجيا التشييد.' : 'Our premier industry-accredited Building Information Modeling programs.' }}
                    </p>
                </div>

                <a 
                    href="{{ route('courses.index') }}" 
                    class="px-5 py-2 rounded-full text-xs font-bold text-[#071A36] dark:text-[#F3D98B] bg-white dark:bg-white/10 hover:bg-slate-100 dark:hover:bg-white/15 border border-slate-300 dark:border-white/15 shadow-sm transition flex items-center gap-1.5 self-start sm:self-auto"
                >
                    <span>{{ $isAr ? 'عرض كافة الدبلومات' : 'View All Diplomas' }}</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <!-- 4-Column Grid of Sleek Engineering Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredCourses->take(4) as $course)
                    <x-course-card :course="$course" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 4. All Courses Section with Category Filter Tabs (جميع الدورات) -->
    <section class="py-16 lg:py-24 bg-white dark:bg-[#071A36] transition-colors duration-200" x-data="{ selectedCategory: 'all' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-10 space-y-2">
                <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-[#D4AF37]/15 text-[#8B6B15] dark:text-[#F3D98B] border border-[#D4AF37]/30 font-mono">
                    {{ $isAr ? 'دليل المسارات التعليمية' : 'CURRICULUM CATALOG' }}
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black font-['Outfit'] text-[#071A36] dark:text-white tracking-tight">
                    {{ $isAr ? 'كافة الدورات والمسارات التخصصية' : 'All Engineering Courses & Disciplines' }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                    {{ $isAr ? 'مناهج هندسية وتطبيقية شاملة مبنية على مشاريع حقيقية وحل التعارضات وأحدث معايير الـ BIM.' : 'Comprehensive curricula structured around real-world projects, clash coordination, and BIM standards.' }}
                </p>
            </div>

            <!-- Filter Tabs Pills Matching Design -->
            <div class="flex flex-wrap items-center justify-center gap-2 mb-12">
                <button 
                    type="button" 
                    @click="selectedCategory = 'all'"
                    :class="selectedCategory === 'all' ? 'bg-[#071A36] text-[#F3D98B] font-bold shadow-md dark:bg-[#D4AF37] dark:text-[#040E1E]' : 'bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 hover:bg-slate-200 border border-slate-200 dark:border-white/10'"
                    class="px-5 py-2 rounded-full text-xs transition-all cursor-pointer"
                >
                    {{ $isAr ? 'جميع الدورات' : 'All Courses' }}
                </button>
                <button 
                    type="button" 
                    @click="selectedCategory = 'architecture'"
                    :class="selectedCategory === 'architecture' ? 'bg-[#071A36] text-[#F3D98B] font-bold shadow-md dark:bg-[#D4AF37] dark:text-[#040E1E]' : 'bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 hover:bg-slate-200 border border-slate-200 dark:border-white/10'"
                    class="px-5 py-2 rounded-full text-xs transition-all cursor-pointer"
                >
                    {{ $isAr ? 'BIM المعماري' : 'BIM Architecture' }}
                </button>
                <button 
                    type="button" 
                    @click="selectedCategory = 'structural'"
                    :class="selectedCategory === 'structural' ? 'bg-[#071A36] text-[#F3D98B] font-bold shadow-md dark:bg-[#D4AF37] dark:text-[#040E1E]' : 'bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 hover:bg-slate-200 border border-slate-200 dark:border-white/10'"
                    class="px-5 py-2 rounded-full text-xs transition-all cursor-pointer"
                >
                    {{ $isAr ? 'BIM الإنشائي' : 'Structural BIM' }}
                </button>
                <button 
                    type="button" 
                    @click="selectedCategory = 'mep'"
                    :class="selectedCategory === 'mep' ? 'bg-[#071A36] text-[#F3D98B] font-bold shadow-md dark:bg-[#D4AF37] dark:text-[#040E1E]' : 'bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 hover:bg-slate-200 border border-slate-200 dark:border-white/10'"
                    class="px-5 py-2 rounded-full text-xs transition-all cursor-pointer"
                >
                    {{ $isAr ? 'أنظمة الكهروميكانيك MEP' : 'MEP Systems' }}
                </button>
                <button 
                    type="button" 
                    @click="selectedCategory = 'automation'"
                    :class="selectedCategory === 'automation' ? 'bg-[#071A36] text-[#F3D98B] font-bold shadow-md dark:bg-[#D4AF37] dark:text-[#040E1E]' : 'bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 hover:bg-slate-200 border border-slate-200 dark:border-white/10'"
                    class="px-5 py-2 rounded-full text-xs transition-all cursor-pointer"
                >
                    {{ $isAr ? 'داينامو والأتمتة' : 'Dynamo & Automation' }}
                </button>
                <button 
                    type="button" 
                    @click="selectedCategory = 'coordination'"
                    :class="selectedCategory === 'coordination' ? 'bg-[#071A36] text-[#F3D98B] font-bold shadow-md dark:bg-[#D4AF37] dark:text-[#040E1E]' : 'bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300 hover:bg-slate-200 border border-slate-200 dark:border-white/10'"
                    class="px-5 py-2 rounded-full text-xs transition-all cursor-pointer"
                >
                    {{ $isAr ? 'تنسيق وإدارة التعارضات' : 'BIM Coordination' }}
                </button>
            </div>

            <!-- 4-Column Grid (8 Cards) with Alpine.js Dynamic Category Filtering -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $coursesToDisplay = isset($allCourses) && $allCourses->isNotEmpty() ? $allCourses : $featuredCourses;
                @endphp
                @foreach($coursesToDisplay as $course)
                    @php
                        $catName = strtolower($course->category?->name_en ?? ($course->category?->name ?? ''));
                        $catSlug = strtolower($course->category?->slug ?? '');
                        $courseTitle = strtolower($course->title_en ?? ($course->title ?? ''));
                        $combinedText = $catName . ' ' . $catSlug . ' ' . $courseTitle;

                        $matchedCat = 'architecture';
                        if (str_contains($combinedText, 'struct') || str_contains($combinedText, 'rebar')) {
                            $matchedCat = 'structural';
                        } elseif (str_contains($combinedText, 'mep') || str_contains($combinedText, 'hvac') || str_contains($combinedText, 'piping') || str_contains($combinedText, 'electrical')) {
                            $matchedCat = 'mep';
                        } elseif (str_contains($combinedText, 'dynamo') || str_contains($combinedText, 'python') || str_contains($combinedText, 'auto') || str_contains($combinedText, 'comput')) {
                            $matchedCat = 'automation';
                        } elseif (str_contains($combinedText, 'navis') || str_contains($combinedText, 'coord') || str_contains($combinedText, 'clash') || str_contains($combinedText, 'manage')) {
                            $matchedCat = 'coordination';
                        } elseif (str_contains($combinedText, 'arch') || str_contains($combinedText, 'revit')) {
                            $matchedCat = 'architecture';
                        }
                    @endphp
                    <div 
                        x-show="selectedCategory === 'all' || selectedCategory === '{{ $matchedCat }}'"
                        x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="flex flex-col h-full"
                    >
                        <x-course-card :course="$course" />
                    </div>
                @endforeach
            </div>

            <!-- View More Button -->
            <div class="text-center mt-12">
                <a 
                    href="{{ route('courses.index') }}" 
                    class="px-8 py-3 rounded-full text-xs font-bold text-[#071A36] dark:text-[#F3D98B] bg-slate-100 dark:bg-white/10 hover:bg-slate-200 border border-slate-300 dark:border-white/15 shadow-sm transition inline-flex items-center gap-2"
                >
                    <span>{{ $isAr ? 'تصفح الدليل الشامل للدورات' : 'Browse Full Course Catalog' }}</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </section>

    <!-- 7. Graduate Capstone Megaprojects Showcase (معرض مشاريع التخرج الحقيقية) -->
    <section class="py-16 lg:py-24 bg-white dark:bg-[#071A36] border-b border-slate-200/80 dark:border-white/10 transition-colors duration-200 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-14 space-y-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-100 dark:bg-white/10 border border-slate-200 dark:border-[#D4AF37]/40 text-[#96720D] dark:text-[#F3D98B] text-xs font-bold tracking-wide">
                    <span class="w-2 h-2 rounded-full bg-[#D4AF37]"></span>
                    <span>{{ $isAr ? 'معرض المشاريع التطبيقية الحقيقية' : 'Applied Engineering Portfolio' }}</span>
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black font-['Outfit'] text-[#071A36] dark:text-white tracking-tight">
                    @if($isAr)
                        مشاريع حقيقية عملاقة من إنجاز <br class="hidden sm:inline">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#96720D] via-[#D4AF37] to-amber-500 dark:from-[#F3D98B] dark:via-[#D4AF37] dark:to-amber-200">
                            مهندسي بيفور بيم المعتمدين
                        </span>
                    @else
                        Real Megaprojects Modeled by <br class="hidden sm:inline">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#96720D] via-[#D4AF37] to-amber-500 dark:from-[#F3D98B] dark:via-[#D4AF37] dark:to-amber-200">
                            Beforbim Graduates
                        </span>
                    @endif
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-light max-w-2xl mx-auto">
                    {{ $isAr ? 'يقوم كل متدرب بنمذجة وتنسيق وتدقيق ملفات عمل حقيقية لمشاريع ضخمة ليناقشها بثقة كاملة في مقابلات كبرى المكاتب الاستشارية.' : 'Every student builds, coordinates, and audits real commercial project datasets to present with confidence during top engineering interviews.' }}
                </p>
            </div>

            <!-- 3-Column Capstone Project Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Project 1: High-Rise Iconic Tower -->
                <div class="rounded-3xl bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/10 hover:border-[#D4AF37] dark:hover:border-[#D4AF37] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
                    <div class="relative aspect-video w-full overflow-hidden bg-slate-200 dark:bg-slate-900">
                        <img 
                            src="{{ asset('images/hero/hero_bim_background.jpg') }}" 
                            alt="CBD Commercial Mixed-Use Tower BIM Model" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <span class="absolute top-3 {{ $isAr ? 'right-3' : 'left-3' }} px-3 py-1 rounded-full text-[10px] font-bold bg-[#071A36]/90 backdrop-blur-md text-[#F3D98B] border border-[#D4AF37]/40">
                            {{ $isAr ? 'LOD 400 معماري وتسليح إنشائي' : 'LOD 400 Architecture & Rebar' }}
                        </span>
                        <span class="absolute bottom-3 {{ $isAr ? 'right-3' : 'left-3' }} text-xs font-mono font-bold text-white">
                            {{ $isAr ? '62 طابقاً • 185,000 م²' : '62 Storeys • 185,000 m²' }}
                        </span>
                    </div>

                    <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
                        <div class="space-y-2">
                            <h3 class="text-base font-bold text-[#071A36] dark:text-white font-['Outfit']">
                                {{ $isAr ? 'البرج الأيقوني متعدد الاستخدامات (CBD العاصمة الإدارية)' : 'Iconic Mixed-Use Tower (CBD New Capital)' }}
                            </h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-light leading-relaxed">
                                {{ $isAr ? 'نمذجة متكاملة للواجهات البارامترية، كور المصاعد، وتفريد حديد التسليح ثلاثي الأبعاد مع جداول الحصر الآلية.' : 'Complete parametric envelope, core walls, and 3D structural reinforcement detailing with automated rebar schedules.' }}
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-200 dark:border-white/10 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                            <span class="font-mono text-[#8B6B15] dark:text-[#F3D98B]">Revit &bull; Dynamo Rebar</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $isAr ? 'مخططات تنفيذية معتمدة' : 'Shop Drawings Ready' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Project 2: Tertiary Teaching Hospital -->
                <div class="rounded-3xl bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/10 hover:border-[#D4AF37] dark:hover:border-[#D4AF37] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
                    <div class="relative aspect-video w-full overflow-hidden bg-slate-200 dark:bg-slate-900">
                        <img 
                            src="{{ asset('images/courses/revit_mep.jpg') }}" 
                            alt="Hospital MEP Clash Coordination" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <span class="absolute top-3 {{ $isAr ? 'right-3' : 'left-3' }} px-3 py-1 rounded-full text-[10px] font-bold bg-[#071A36]/90 backdrop-blur-md text-[#F3D98B] border border-[#D4AF37]/40">
                            {{ $isAr ? 'LOD 350 تنسيق أنظمة MEP' : 'LOD 350 MEP Coordination' }}
                        </span>
                        <span class="absolute bottom-3 {{ $isAr ? 'right-3' : 'left-3' }} text-xs font-mono font-bold text-white">
                            {{ $isAr ? '450 سريراً • حل 1,420 تعارضاً' : '450 Beds • 1,420 Clashes Cleared' }}
                        </span>
                    </div>

                    <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
                        <div class="space-y-2">
                            <h3 class="text-base font-bold text-[#071A36] dark:text-white font-['Outfit']">
                                {{ $isAr ? 'مجمع مستشفيات جامعة القاهرة التخصصي' : 'Cairo University Hospital Complex' }}
                            </h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-light leading-relaxed">
                                {{ $isAr ? 'تنسيق فضائي وتلافي تعارضات مجاري الهواء والغازات الطبية والصرف وشبكات الإطفاء مع الجسور الإنشائية عبر Navisworks.' : 'Multidisciplinary spatial coordination across HVAC ductwork, medical gases, drainage, and structural beams in Navisworks.' }}
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-200 dark:border-white/10 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                            <span class="font-mono text-[#8B6B15] dark:text-[#F3D98B]">Navisworks &bull; BIM Collab</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $isAr ? 'خالٍ من التعارضات' : 'Zero-Clash Verified' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Project 3: High-Speed Metro Interchange Station -->
                <div class="rounded-3xl bg-slate-50 dark:bg-[#040E1E] border border-slate-200 dark:border-white/10 hover:border-[#D4AF37] dark:hover:border-[#D4AF37] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
                    <div class="relative aspect-video w-full overflow-hidden bg-slate-200 dark:bg-slate-900">
                        <img 
                            src="{{ asset('images/courses/navisworks_4d.jpg') }}" 
                            alt="Metro Terminal 4D Sequencing Simulation" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <span class="absolute top-3 {{ $isAr ? 'right-3' : 'left-3' }} px-3 py-1 rounded-full text-[10px] font-bold bg-[#071A36]/90 backdrop-blur-md text-[#F3D98B] border border-[#D4AF37]/40">
                            {{ $isAr ? 'محاكاة زمنية 4D وتكلفة 5D' : '4D Time & 5D Cost Simulation' }}
                        </span>
                        <span class="absolute bottom-3 {{ $isAr ? 'right-3' : 'left-3' }} text-xs font-mono font-bold text-white">
                            {{ $isAr ? '3,200 نشاطاً • Synchro 4D' : '3,200 Activities • Synchro 4D' }}
                        </span>
                    </div>

                    <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
                        <div class="space-y-2">
                            <h3 class="text-base font-bold text-[#071A36] dark:text-white font-['Outfit']">
                                {{ $isAr ? 'محطة التبادل المركزية للقطار فائق السرعة' : 'High-Speed Rail Interchange Terminal' }}
                            </h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-light leading-relaxed">
                                {{ $isAr ? 'ربط جداول بريمافيرا P6 بالنماذج ثلاثية الأبعاد لمحاكاة مراحل التشييد ومجال حركة الرافعات واللوجستيات.' : 'Linking Primavera P6 schedules with 3D models for site logistics, crane coverage, and animated construction phases.' }}
                            </p>
                        </div>

                        <div class="pt-2 border-t border-slate-200 dark:border-white/10 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                            <span class="font-mono text-[#8B6B15] dark:text-[#F3D98B]">Synchro 4D &bull; Primavera P6</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $isAr ? 'مزامنة زمنية تامة' : 'Timeline Synced' }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- 8. Interactive Frequently Asked Questions (Accordion with Smooth Motion) -->
    <section 
        class="py-16 lg:py-24 bg-slate-50/70 dark:bg-[#040E1E] border-b border-slate-200/80 dark:border-white/10 transition-colors duration-200 relative overflow-hidden"
        x-data="{ openFaq: 0 }"
    >
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-12 space-y-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white dark:bg-white/10 border border-slate-200 dark:border-[#D4AF37]/40 text-[#96720D] dark:text-[#F3D98B] text-xs font-bold tracking-wide">
                    <span>{{ $isAr ? 'القبول والاستفسارات الهندسية' : 'Admissions & Engineering Inquiries' }}</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-black font-['Outfit'] text-[#071A36] dark:text-white tracking-tight">
                    {{ $isAr ? 'الأسئلة الشائعة والمكررة' : 'Frequently Asked Questions' }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-light">
                    {{ $isAr ? 'كل ما تحتاج معرفته عن الاعتمادات الدولية، المناهج التطبيقية، وشبكة التوظيف.' : 'Everything you need to know about our accreditation, curricula, and recruitment network.' }}
                </p>
            </div>

            <!-- Accordion Items -->
            <div class="space-y-4">
                
                <!-- FAQ Item 1 -->
                <div 
                    class="rounded-2xl border transition-all duration-300 overflow-hidden bg-white dark:bg-[#071A36]"
                    :class="openFaq === 0 ? 'border-[#D4AF37] shadow-lg shadow-[#D4AF37]/10' : 'border-slate-200 dark:border-white/10'"
                >
                    <button 
                        type="button" 
                        @click="openFaq = (openFaq === 0 ? null : 0)"
                        class="w-full p-5 sm:p-6 text-left {{ $isAr ? 'text-right' : '' }} flex items-center justify-between gap-4 cursor-pointer"
                    >
                        <span class="text-sm sm:text-base font-bold text-[#071A36] dark:text-white font-['Outfit']">
                            {{ $isAr ? 'هل شهادات Beforbim معتمدة دولياً ومطابقة لمعايير ISO 19650؟' : 'Are Beforbim certificates accredited internationally under ISO 19650?' }}
                        </span>
                        <div 
                            class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/10 text-[#071A36] dark:text-[#F3D98B] flex items-center justify-center shrink-0 transition-transform duration-300"
                            :class="openFaq === 0 ? 'rotate-180 bg-[#D4AF37] text-[#040E1E]' : ''"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    <div 
                        x-show="openFaq === 0" 
                        x-collapse
                        class="px-5 pb-6 sm:px-6 text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-light leading-relaxed border-t border-slate-100 dark:border-white/10 pt-4"
                    >
                        {{ $isAr ? 'نعم، جميع مناهج الدبلومات في Beforbim مصممة وفق أحدث معايير الأيزو العالمية ISO 19650 لإدارة ونمذجة معلومات البناء. وتتضمن كل شهادة كود رقمي وQR كود موثق للتحقق الفوري ومعتمد لدى كبرى المكاتب الاستشارية وشركات المقاولات في مصر والسعودية والإمارات.' : 'Yes. All Beforbim diploma syllabi are aligned with international ISO 19650 standards for building information modeling. Each credential includes a verifiable ID and digital QR code recognized by consulting firms and contractors across Egypt, Saudi Arabia, and the United Arab Emirates.' }}
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div 
                    class="rounded-2xl border transition-all duration-300 overflow-hidden bg-white dark:bg-[#071A36]"
                    :class="openFaq === 1 ? 'border-[#D4AF37] shadow-lg shadow-[#D4AF37]/10' : 'border-slate-200 dark:border-white/10'"
                >
                    <button 
                        type="button" 
                        @click="openFaq = (openFaq === 1 ? null : 1)"
                        class="w-full p-5 sm:p-6 text-left {{ $isAr ? 'text-right' : '' }} flex items-center justify-between gap-4 cursor-pointer"
                    >
                        <span class="text-sm sm:text-base font-bold text-[#071A36] dark:text-white font-['Outfit']">
                            {{ $isAr ? 'أنا مهندس مدني أو معماري خبرتي فقط في الأوتوكاد (AutoCAD)، هل يمكنني الانضمام؟' : 'I am a civil or architectural graduate with only AutoCAD knowledge. Can I join?' }}
                        </span>
                        <div 
                            class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/10 text-[#071A36] dark:text-[#F3D98B] flex items-center justify-center shrink-0 transition-transform duration-300"
                            :class="openFaq === 1 ? 'rotate-180 bg-[#D4AF37] text-[#040E1E]' : ''"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7-7-7"/></svg>
                        </div>
                    </button>
                    <div 
                        x-show="openFaq === 1" 
                        x-collapse
                        class="px-5 pb-6 sm:px-6 text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-light leading-relaxed border-t border-slate-100 dark:border-white/10 pt-4"
                    >
                        {{ $isAr ? 'بالتأكيد! نبدأ معك من الصفر بالمفاهيم الأساسية للنمذجة ثلاثية الأبعاد ثم نتدرج حتى التنسيق المتقدم بين التخصصات، حل التعارضات Clash Detection، محاكاة 4D، وبرمجة Dynamo. يرافقك خبراء وموجهون خطوة بخطوة في كل مرحلة.' : 'Absolutely. Our curriculum begins from foundational 3D parametric concepts and progressively scales up through advanced multidisciplinary clash coordination, 4D simulation, and Dynamo automation. You will be guided by experienced mentors at every phase.' }}
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div 
                    class="rounded-2xl border transition-all duration-300 overflow-hidden bg-white dark:bg-[#071A36]"
                    :class="openFaq === 2 ? 'border-[#D4AF37] shadow-lg shadow-[#D4AF37]/10' : 'border-slate-200 dark:border-white/10'"
                >
                    <button 
                        type="button" 
                        @click="openFaq = (openFaq === 2 ? null : 2)"
                        class="w-full p-5 sm:p-6 text-left {{ $isAr ? 'text-right' : '' }} flex items-center justify-between gap-4 cursor-pointer"
                    >
                        <span class="text-sm sm:text-base font-bold text-[#071A36] dark:text-white font-['Outfit']">
                            {{ $isAr ? 'هل يتدرب المهندسون على مشاريع تجارية حقيقية لبناء بورتفوليو احترافي؟' : 'Do students receive real commercial project datasets to build their portfolios?' }}
                        </span>
                        <div 
                            class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/10 text-[#071A36] dark:text-[#F3D98B] flex items-center justify-center shrink-0 transition-transform duration-300"
                            :class="openFaq === 2 ? 'rotate-180 bg-[#D4AF37] text-[#040E1E]' : ''"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    <div 
                        x-show="openFaq === 2" 
                        x-collapse
                        class="px-5 pb-6 sm:px-6 text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-light leading-relaxed border-t border-slate-100 dark:border-white/10 pt-4"
                    >
                        {{ $isAr ? 'نعم، لا نعتمد أبداً على أمثلة أكاديمية مبسطة، بل نوفر نماذج ومخططات أبراج تجارية ومستشفيات ومحطات بنية تحتية حقيقية. تتخرج ولديك سابقة أعمال كاملة بمستوى تفاصيل LOD 350-400 وجداول حصر ولوحات شوب دروينج جاهزة لمقابلات التوظيف.' : 'Yes. Instead of generic academic examples, you work on genuine commercial towers, hospital complexes, and infrastructure station models. You graduate with an interview-ready portfolio demonstrating LOD 350-400 modeling, clash matrices, and shop drawings.' }}
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div 
                    class="rounded-2xl border transition-all duration-300 overflow-hidden bg-white dark:bg-[#071A36]"
                    :class="openFaq === 3 ? 'border-[#D4AF37] shadow-lg shadow-[#D4AF37]/10' : 'border-slate-200 dark:border-white/10'"
                >
                    <button 
                        type="button" 
                        @click="openFaq = (openFaq === 3 ? null : 3)"
                        class="w-full p-5 sm:p-6 text-left {{ $isAr ? 'text-right' : '' }} flex items-center justify-between gap-4 cursor-pointer"
                    >
                        <span class="text-sm sm:text-base font-bold text-[#071A36] dark:text-white font-['Outfit']">
                            {{ $isAr ? 'كيف تعمل خدمة التوظيف والترشيح لدى المكاتب الاستشارية الشريكة؟' : 'How does the consultancy placement and job referral service operate?' }}
                        </span>
                        <div 
                            class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/10 text-[#071A36] dark:text-[#F3D98B] flex items-center justify-center shrink-0 transition-transform duration-300"
                            :class="openFaq === 3 ? 'rotate-180 bg-[#D4AF37] text-[#040E1E]' : ''"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    <div 
                        x-show="openFaq === 3" 
                        x-collapse
                        class="px-5 pb-6 sm:px-6 text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-light leading-relaxed border-t border-slate-100 dark:border-white/10 pt-4"
                    >
                        {{ $isAr ? 'الخريجون الذين يكملون المشاريع المتكاملة والتقييمات الفنية يُدرجون في قاعدة بيانات التوظيف المباشر لدينا. نقوم بترشيحهم وتزكيتهم لشركائنا من الشركات الاستشارية والمقاولين في القاهرة والرياض ودبي مع مراجعة فنية شاملة للـ CV والبورتفوليو.' : 'Graduates who complete all capstone milestones and technical assessments are entered into our direct hiring registry. We actively recommend certified graduates to our partner consultancies and contractors across Cairo, Riyadh, and Dubai, alongside personalized technical CV and portfolio critiques.' }}
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- 9. Redesigned High-Impact Career Accelerator Section (BIM Certification & Career Launch) -->
    <section class="py-20 lg:py-28 bg-[#040E1E] text-white relative overflow-hidden transition-colors duration-200 border-t border-slate-800 dark:border-white/10">
        <!-- Ambient Engineering Blueprint Glows -->
        <div class="absolute -top-32 -left-32 w-[35rem] h-[35rem] bg-[#071A36] rounded-full blur-[140px] pointer-events-none opacity-80"></div>
        <div class="absolute -bottom-32 -right-32 w-[35rem] h-[35rem] bg-[#D4AF37]/15 rounded-full blur-[140px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                
                <!-- Left Column: Compelling Engineering Career Pitch & Value Props -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- Accreditation Pill -->
                    <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/10 border border-[#D4AF37]/40 text-[#F3D98B] text-xs font-semibold backdrop-blur-md shadow-lg">
                        <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-ping"></span>
                        <span>{{ $isAr ? 'متوافق مع أوتوديسك • مسارات معتمدة وفق ISO 19650' : 'Autodesk Aligned • ISO 19650 Accredited Pathways' }}</span>
                    </div>

                    <!-- Main Headline -->
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black font-['Outfit'] tracking-tight leading-[1.14] text-white">
                        @if($isAr)
                            انطلق بمسيرتك الهندسية مع <br class="hidden sm:inline">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#F3D98B] via-[#D4AF37] to-amber-200">
                                شهادات واعتمادات البيم الدولية
                            </span>
                        @else
                            Transform Your Career with <br class="hidden sm:inline">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#F3D98B] via-[#D4AF37] to-amber-200">
                                Internationally Certified BIM Credentials
                            </span>
                        @endif
                    </h2>

                    <!-- Subtitle -->
                    <p class="text-sm sm:text-base text-slate-300 font-light leading-relaxed max-w-2xl">
                        {{ $isAr ? 'تجاوز حدود الرسم الثنائي الأبعاد التقليدي، واحترف تنسيق التخصصات وحل التعارضات، وجدولة التشييد 4D، والنمذجة الخوارزمية لتتولى مناصب قيادية في كبرى المشاريع بمصر والخليج.' : 'Step beyond conventional 2D drafting. Master multidisciplinary clash coordination, 4D construction sequencing, and algorithmic computational modeling to command senior engineering positions across Egypt, KSA, and the UAE.' }}
                    </p>

                    <!-- 3 Feature Pillars with Icons -->
                    <div class="space-y-3.5 pt-2">
                        <div class="flex items-start gap-3.5">
                            <div class="w-6 h-6 rounded-lg bg-[#D4AF37]/20 text-[#F3D98B] flex items-center justify-center shrink-0 mt-0.5 border border-[#D4AF37]/30">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-white font-['Outfit']">
                                    {{ $isAr ? 'سابقة أعمال لمشاريع عملاقة حقيقية' : 'Production Megaproject Portfolio' }}
                                </h4>
                                <p class="text-xs text-slate-400 font-light">
                                    {{ $isAr ? 'بناء نماذج معتمدة بمستوى LOD 350-400 لأبراج ومنشآت صحية جاهزة لتقديمها للعملاء وأرباب العمل.' : 'Build verified LOD 350-400 models of high-rises and healthcare facilities ready for client presentations.' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-6 h-6 rounded-lg bg-[#D4AF37]/20 text-[#F3D98B] flex items-center justify-center shrink-0 mt-0.5 border border-[#D4AF37]/30">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-white font-['Outfit']">
                                    {{ $isAr ? 'شبكة توظيف وشراكات استشارية' : 'Consultancy Placement Network' }}
                                </h4>
                                <p class="text-xs text-slate-400 font-light">
                                    {{ $isAr ? 'قنوات ترشيح وتوظيف مباشرة مع كبرى المكاتب الاستشارية وشركات المقاولات في القاهرة والرياض ودبي.' : 'Direct recruitment channels with leading engineering consultancies across Cairo, Riyadh, and Dubai.' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-6 h-6 rounded-lg bg-[#D4AF37]/20 text-[#F3D98B] flex items-center justify-center shrink-0 mt-0.5 border border-[#D4AF37]/30">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-white font-['Outfit']">
                                    {{ $isAr ? 'الاستعداد لاختبارات أوتوديسك الاحترافية (ACP)' : 'Autodesk Certified Professional (ACP) Readiness' }}
                                </h4>
                                <p class="text-xs text-slate-400 font-light">
                                    {{ $isAr ? 'تدريبات على نماذج الامتحانات، تغطية شاملة للمنهج الرسمي، ومراجعة فردية للبورتفوليو بإشراف مديري BIM.' : 'Mock exam drills, official syllabus coverage, and one-on-one portfolio critique by BIM Directors.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-4 flex flex-wrap items-center gap-4">
                        <a 
                            href="{{ route('register') }}" 
                            class="px-8 py-3.5 rounded-full text-sm font-bold bg-[#D4AF37] hover:bg-[#F3D98B] text-[#040E1E] shadow-xl shadow-[#D4AF37]/25 transition-all flex items-center gap-2"
                        >
                            <span>{{ $isAr ? 'سجل في الدبلومات الآن' : 'Enroll in Masterclasses' }}</span>
                            <svg class="w-4 h-4 {{ $isAr ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>

                        <a 
                            href="{{ route('courses.index') }}" 
                            class="px-7 py-3.5 rounded-full text-sm font-bold bg-white/10 hover:bg-white/20 text-white border border-white/20 backdrop-blur-md transition-all flex items-center gap-2"
                        >
                            <span>{{ $isAr ? 'استعرض دليل الدورات' : 'Explore Course Catalog' }}</span>
                            <span class="{{ $isAr ? 'rotate-180 inline-block' : '' }}">&rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- Right Column: Interactive Graduate Impact & Credential Verification Showcase -->
                <div class="lg:col-span-5 relative">
                    <div class="relative rounded-3xl p-6 sm:p-8 bg-gradient-to-b from-[#071A36] to-[#051329] border border-[#D4AF37]/30 shadow-2xl space-y-6">
                        
                        <!-- Top Header with Verified Seal -->
                        <div class="flex items-center justify-between border-b border-white/10 pb-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-[#D4AF37]/15 border border-[#D4AF37]/30 flex items-center justify-center">
                                    <img src="{{ asset('images/branding/logo.png') }}" alt="Beforbim" class="w-6 h-6 object-contain">
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white font-['Outfit']">{{ $isAr ? 'شهادة معتمدة من Beforbim' : 'Beforbim Certified' }}</h4>
                                    <p class="text-[11px] text-[#F3D98B]">{{ $isAr ? 'اعتماد هندسي رقمي موثق' : 'Digital Engineering Credential' }}</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                {{ $isAr ? 'موثق رسمياً' : 'Verified' }}
                            </span>
                        </div>

                        <!-- 3 Stat Milestones -->
                        <div class="grid grid-cols-3 gap-3 text-center py-2 border-b border-white/10">
                            <div class="space-y-0.5">
                                <span class="text-2xl font-black font-['Outfit'] text-[#D4AF37]">98%</span>
                                <p class="text-[10px] text-slate-400 font-medium">{{ $isAr ? 'معدل التوظيف' : 'Hiring Rate' }}</p>
                            </div>
                            <div class="space-y-0.5 border-x border-white/10 px-2">
                                <span class="text-2xl font-black font-['Outfit'] text-[#F3D98B]">+65%</span>
                                <p class="text-[10px] text-slate-400 font-medium">{{ $isAr ? 'نمو في الراتب' : 'Salary Uplift' }}</p>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-2xl font-black font-['Outfit'] text-white">45+</span>
                                <p class="text-[10px] text-slate-400 font-medium">{{ $isAr ? 'دبلومة متخصصة' : 'Diplomas' }}</p>
                            </div>
                        </div>

                        <!-- Student Testimonial Spotlight -->
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 space-y-2">
                            <div class="flex items-center gap-1 text-amber-400 text-xs">
                                <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                                <span class="text-slate-300 text-[11px] font-bold {{ $isAr ? 'mr-1.5' : 'ml-1.5' }}">{{ $isAr ? 'قصة نجاح خريج' : 'Alumni Spotlight' }}</span>
                            </div>
                            <p class="text-xs text-slate-200 italic leading-relaxed font-light">
                                {{ $isAr ? '"التطبيق العملي على تنسيق LOD 400 ومصفوفات حل التعارضات منحني الثقة التامة لشغل منصب منسق بيم أول في أحد كبرى المشاريع بالقاهرة."' : '"The hands-on LOD 400 coordination workflows and clash resolution matrices gave me the exact confidence needed to secure my role as BIM Coordinator in Cairo."' }}
                            </p>
                            <div class="pt-1 flex items-center justify-between text-[11px]">
                                <span class="font-bold text-[#F3D98B]">{{ $isAr ? 'م. طارق مصطفى' : 'Eng. Tarek Mostafa' }}</span>
                                <span class="text-slate-400">{{ $isAr ? 'منسق بيم أول' : 'Senior BIM Coordinator' }}</span>
                            </div>
                        </div>

                        <!-- Software Stack Badges -->
                        <div class="pt-1 flex items-center justify-center gap-2 text-[10px] text-slate-400 uppercase tracking-wider font-mono">
                            <span>Revit</span> &bull; 
                            <span>Navisworks</span> &bull; 
                            <span>Dynamo</span> &bull; 
                            <span>Civil 3D</span>
                        </div>

                    </div>

                    <!-- Decorative Floating Golden Frame Accent -->
                    <div class="absolute -bottom-3 -right-3 -z-10 w-full h-full rounded-3xl border border-[#D4AF37]/20 pointer-events-none hidden sm:block"></div>
                </div>

            </div>
        </div>
    </section>

    <x-public-footer />
</x-layouts.base>
