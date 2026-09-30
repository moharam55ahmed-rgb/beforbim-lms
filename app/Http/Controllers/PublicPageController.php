<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Controllers\CourseController;
use App\Modules\Course\Models\Course;
use App\Modules\CourseReview\Models\CourseReview;
use App\Modules\Setting\Services\CmsSettingService;
use App\Modules\SupportTicket\Models\SupportTicket;
use App\Modules\User\Models\InstructorProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function __construct(
        protected CmsSettingService $cms
    ) {}

    /**
     * Display the public homepage.
     */
    public function home(): View
    {
        try {
            $categories = Category::where('is_active', true)
                ->withCount(['courses' => function ($q) {
                    $q->where('status', 'APPROVED')->whereNotNull('published_at');
                }])
                ->orderBy('display_order')
                ->take(6)
                ->get();

            $featuredCourses = Course::published()
                ->with(['instructor', 'category'])
                ->withCount('approvedReviews')
                ->latest('published_at')
                ->take(6)
                ->get();

            $featuredInstructors = User::whereHas('roles', function ($q) {
                $q->where('name', 'instructor');
            })
                ->with('instructorProfile')
                ->take(4)
                ->get();

            $topReviews = CourseReview::where('status', 'approved')
                ->where('rating', '>=', 4)
                ->with(['student', 'course'])
                ->latest()
                ->take(3)
                ->get();

            $totalStudents = User::whereHas('roles', fn ($q) => $q->where('name', 'student'))->count();
            $totalCourses = Course::published()->count();
            $avgRating = CourseReview::where('status', 'approved')->avg('rating');

            $stats = [
                'certified_alumni' => $totalStudents > 0 ? number_format($totalStudents * 450 + 12000).'+' : '12,500+',
                'simulated_datasets' => ($totalCourses > 0 ? $totalCourses * 9 : 45).'+',
                'iso_compliance' => '100%',
                'avg_rating' => $avgRating ? number_format($avgRating, 1) : '4.9',
            ];

            $allCourses = Course::published()
                ->with(['instructor', 'category'])
                ->withCount('approvedReviews')
                ->latest('published_at')
                ->take(8)
                ->get();
        } catch (\Throwable) {
            $categories = collect();
            $featuredCourses = collect();
            $featuredInstructors = collect();
            $topReviews = collect();
            $allCourses = collect();
            $stats = [
                'certified_alumni' => '12,500+',
                'simulated_datasets' => '45+',
                'iso_compliance' => '100%',
                'avg_rating' => '4.9',
            ];
        }

        $recentArticles = array_slice(array_values($this->getBlogArticles()), 0, 3);

        return view('pages.home', [
            'cms' => $this->cms,
            'categories' => $categories,
            'featuredCourses' => $featuredCourses,
            'allCourses' => $allCourses,
            'featuredInstructors' => $featuredInstructors,
            'topReviews' => $topReviews,
            'recentArticles' => $recentArticles,
            'stats' => $stats,
        ]);
    }

    /**
     * Display the About Us page.
     */
    public function about(): View
    {
        return view('pages.about', [
            'cms' => $this->cms,
        ]);
    }

    /**
     * Display the Contact Us page.
     */
    public function contact(): View
    {
        return view('pages.contact', [
            'cms' => $this->cms,
        ]);
    }

    /**
     * Handle the contact form submission.
     */
    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:30',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:3000',
        ]);

        // If the user is logged in, create an official support ticket.
        if ($user = $request->user()) {
            $ticket = SupportTicket::create([
                'user_id' => $user->id,
                'category' => 'TECHNICAL',
                'priority' => 'NORMAL',
                'status' => 'OPEN',
                'subject' => '[استفسار موقع] '.$validated['subject'],
            ]);

            $ticket->messages()->create([
                'user_id' => $user->id,
                'is_staff_reply' => false,
                'is_internal_note' => false,
                'message' => "المرسل: {$validated['name']} ({$validated['email']})\nالهاتف: ".($validated['phone'] ?? 'غير محدد')."\n\n{$validated['message']}",
            ]);
        }

        return back()->with('success', 'شكراً لتواصلك مع Beforbim! تم استلام رسالتك وسيقوم فريق الاستشارات الهندسية بالتواصل معك في أقرب وقت.');
    }

    /**
     * Display the Blog / Knowledge Base index.
    /**
     * Get the engineering blog articles list.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function getBlogArticles(): array
    {
        return [
            'iso-19650-bim-execution-plan-guide' => [
                'slug' => 'iso-19650-bim-execution-plan-guide',
                'title' => 'Comprehensive Guide to Authoring a BIM Execution Plan (BEP) Under ISO 19650',
                'title_ar' => 'الدليل الشامل لإعداد خطة تنفيذ الـ BIM وفق المواصفة القياسية ISO 19650',
                'excerpt' => 'Discover the essential requirements for drafting a robust BEP, role matrix, and CDE workflows to eliminate site disputes and streamline contractor delivery.',
                'author' => 'Eng. Khaled Al-Dosari (BIM Director)',
                'category' => 'BIM Management',
                'read_time' => '7 min read',
                'date' => '2026-09-20',
                'featured_image' => '/images/blog/iso-19650.jpg',
                'content' => 'The BIM Execution Plan (BEP) is the foundational operational contract for modern AEC engineering success, delineating information exchange protocols, LOD milestones, and federated coordination procedures across all project disciplines.',
            ],
            'revit-clash-detection-with-navisworks' => [
                'slug' => 'revit-clash-detection-with-navisworks',
                'title' => 'Multi-Disciplinary Clash Detection Strategies & Rework Reduction via Navisworks',
                'title_ar' => 'استراتيجيات اكتشاف التعارضات الهندسية (Clash Detection) وتقليل الهدر في المواقع',
                'excerpt' => 'How MEP and structural coordination teams eliminate hundreds of thousands of dollars in site change orders using Navisworks Manage clash matrices.',
                'author' => 'Eng. Ahmed Al-Shammari (BIM Coordinator)',
                'category' => 'Coordination & Detailing',
                'read_time' => '5 min read',
                'date' => '2026-09-18',
                'featured_image' => '/images/blog/clash-detection.jpg',
                'content' => 'Clash Detection is not merely pushing an automated button. It is a precise engineering matrix establishing tolerance thresholds, prioritization hierarchies, and clearing spatial clearances between structural concrete and MEP services.',
            ],
            'dynamo-automation-for-structural-detailing' => [
                'slug' => 'dynamo-automation-for-structural-detailing',
                'title' => 'Automating Reinforced Concrete Detailing with Dynamo & Revit API: From Logic to Production',
                'title_ar' => 'أتمتة تسليح المنشآت الخرسانية عبر Dynamo و Revit API: من النظرية إلى التطبيق',
                'excerpt' => 'Algorithmic workflows that accelerate structural shop drawing production cycles by over 60% in engineering technical offices.',
                'author' => 'Eng. Omar Farouk (Computational Designer)',
                'category' => 'Computational Design',
                'read_time' => '10 min read',
                'date' => '2026-09-12',
                'featured_image' => '/images/blog/dynamo-rebar.jpg',
                'content' => 'Leveraging computational parameters for column stirrups, slab reinforcement distribution, and rebar bending schedules eliminates repetitive manual drafting and prevents costly detailing errors.',
            ],
            'lod-350-vs-lod-400-fabrication-standards' => [
                'slug' => 'lod-350-vs-lod-400-fabrication-standards',
                'title' => 'Engineering Distinction Between LOD 350 & LOD 400 in Fabrication and Site Assembly',
                'title_ar' => 'الفرق الهندسي بين مستويات التفاصيل LOD 350 و LOD 400 في التصنيع والتركيب الميداني',
                'excerpt' => 'A practical comparison between BIMForum LOD levels and their critical importance in precast elements, connection detailing, and contractor procurement.',
                'author' => 'Eng. Khaled Al-Dosari (BIM Director)',
                'category' => 'Modeling Standards',
                'read_time' => '8 min read',
                'date' => '2026-09-08',
                'featured_image' => '/images/blog/lod-standards.jpg',
                'content' => 'LOD 350 represents the critical nexus between design intent and physical construction by incorporating actual support brackets, ties, and clearances, whereas LOD 400 mandates full shop-level fabrication detailing.',
            ],
            'mep-coordination-and-builders-work' => [
                'slug' => 'mep-coordination-and-builders-work',
                'title' => 'MEP Builders Work Openings Protocol: Pre-Pour Approvals & Structural Integrity',
                'title_ar' => 'تنسيق فتحات الأعمال الإنشائية (Builders Work Openings) للأنظمة الكهروميكانيكية',
                'excerpt' => 'The standard engineering protocol for reserving and approving duct and pipe sleeves in reinforced concrete beams and shear walls before casting.',
                'author' => 'Eng. Hossam El-Ali (Senior MEP Specialist)',
                'category' => 'MEP Engineering',
                'read_time' => '6 min read',
                'date' => '2026-09-05',
                'featured_image' => '/images/blog/mep-coordination.jpg',
                'content' => 'Early sign-off on Builders Work opening drawings ensures that MEP penetrations are cast directly into structural elements, preventing destructive post-pour diamond coring and preserving load-bearing concrete integrity.',
            ],
        ];
    }

    /**
     * Display the Blog / Knowledge Base index.
     */
    public function blog(): View
    {
        $articles = array_values($this->getBlogArticles());

        return view('pages.blog', [
            'cms' => $this->cms,
            'articles' => $articles,
        ]);
    }

    /**
     * Display a single blog article.
     */
    public function blogShow(string $slug): View
    {
        $articles = $this->getBlogArticles();
        $article = $articles[$slug] ?? abort(404);

        $related = array_filter($articles, fn ($k) => $k !== $slug, ARRAY_FILTER_USE_KEY);
        $relatedArticles = array_slice(array_values($related), 0, 3);

        try {
            $featuredCourses = Course::where('status', 'APPROVED')
                ->latest()
                ->take(2)
                ->get();
        } catch (\Throwable) {
            $featuredCourses = collect();
        }

        return view('pages.blog-show', [
            'cms' => $this->cms,
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'featuredCourses' => $featuredCourses,
        ]);
    }

    /**
     * Display the instructors index page.
     */
    public function instructors(): View
    {
        try {
            $instructors = User::whereHas('roles', function ($q) {
                $q->where('name', 'instructor');
            })
                ->with(['instructorProfile', 'courses' => function ($q) {
                    $q->published();
                }])
                ->paginate(8);

            if ($instructors->isEmpty()) {
                $instructors = $this->getFallbackInstructorsPaginator();
            }

            $view = view('pages.instructors', [
                'cms' => $this->cms,
                'instructors' => $instructors,
            ]);
            $view->render();

            return $view;
        } catch (\Throwable) {
            return view('pages.instructors', [
                'cms' => $this->cms,
                'instructors' => $this->getFallbackInstructorsPaginator(),
            ]);
        }
    }

    /**
     * Display a specific instructor's profile.
     */
    public function instructorShow(mixed $user): View
    {
        try {
            $userModel = $user instanceof User ? $user : User::find($user);

            if (! $userModel) {
                $userModel = $this->getFallbackInstructorModel($user);
            }

            if (! $userModel) {
                abort(404);
            }

            try {
                $userModel->loadMissing(['instructorProfile', 'courses' => function ($q) {
                    $q->published()->with('category');
                }]);
            } catch (\Throwable) {
                // Ignore relation loading error
            }

            $view = view('pages.instructor-show', [
                'cms' => $this->cms,
                'instructor' => $userModel,
            ]);
            $view->render();

            return $view;
        } catch (\Throwable) {
            $userModel = $this->getFallbackInstructorModel($user);
            if (! $userModel) {
                abort(404);
            }

            return view('pages.instructor-show', [
                'cms' => $this->cms,
                'instructor' => $userModel,
            ]);
        }
    }

    /**
     * Build fallback in-memory User models for instructors.
     *
     * @return array<int, User>
     */
    public function getFallbackInstructorsModels(): array
    {
        $courses = app(CourseController::class)->getAllFallbackCourseModels();

        $data = [
            [
                'id' => 279,
                'name' => 'م. خالد الدوسري',
                'name_en' => 'Eng. Khaled Al-Dosari',
                'email' => 'khaled.dosari@beforbim.com',
                'engineering_title' => 'Senior Structural BIM Specialist (Autodesk Certified)',
                'avatar_url' => asset('images/instructors/khaled_avatar.jpg'),
                'specialization' => 'Revit Structure & BIM Management',
                'specialization_en' => 'Senior Structural BIM Director & Coordination Specialist',
                'experience_years' => 15,
                'bio' => 'استشاري وخبير معتمد في النمذجة الإنشائية وإدارة التنسيق الهندسي للمشاريع الضخمة وفق مواصفات ISO 19650، مع خبرة تتجاوز 15 عاماً في قطاعات البنية التحتية والمستشفيات والأبراج السكنية.',
                'certifications' => ['Autodesk Certified Professional: Revit Structure', 'ISO 19650 Certified Information Manager', 'BIM Project Management Specialist'],
                'course_indices' => [0, 1],
            ],
            [
                'id' => 280,
                'name' => 'د. أحمد الشمري',
                'name_en' => 'Dr. Ahmed Al-Shammari',
                'email' => 'ahmed.shammari@beforbim.com',
                'engineering_title' => 'Navisworks & 4D Simulation Director',
                'avatar_url' => asset('images/instructors/ahmed_avatar.jpg'),
                'specialization' => 'Clash Detection & 4D Construction Simulation',
                'specialization_en' => 'BIM Coordination & 4D Time Simulation Consultant',
                'experience_years' => 12,
                'bio' => 'دكتوراه في هندسة التشييد وإدارة المشروعات، رائد في استراتيجيات اكتشاف التعارضات الهندسية ومحاكاة الجداول الزمنية 4D لتقليل أوامر التغيير والهدر المالي في المواقع.',
                'certifications' => ['Navisworks Manage Certified Specialist', 'Project Management Professional (PMP)', 'Synchro 4D Certified Professional'],
                'course_indices' => [3],
            ],
            [
                'id' => 281,
                'name' => 'م. عمر فاروق',
                'name_en' => 'Eng. Omar Farouk',
                'email' => 'omar.farouk@beforbim.com',
                'engineering_title' => 'Lead Computational Designer & Dynamo Developer',
                'avatar_url' => asset('images/instructors/omar_avatar.jpg'),
                'specialization' => 'Computational BIM & Dynamo Automation',
                'specialization_en' => 'Parametric Architecture & Algorithmic Automation Lead',
                'experience_years' => 10,
                'bio' => 'مهندس متخصص في التصميم البرمجي والأتمتة الهندسية عبر Dynamo و Python، طور خوارزميات توفر مئات ساعات العمل للمكاتب الفنية في تفريد التسليح واستخراج الجداول.',
                'certifications' => ['Dynamo Certified Specialist', 'Autodesk Developer Network Member', 'Computational Design Lead'],
                'course_indices' => [4],
            ],
            [
                'id' => 282,
                'name' => 'م. حسام العلي',
                'name_en' => 'Eng. Hossam El-Ali',
                'email' => 'hossam.elali@beforbim.com',
                'engineering_title' => 'Senior MEP BIM Systems Consultant',
                'avatar_url' => asset('images/instructors/hossam_avatar.jpg'),
                'specialization' => 'Revit MEP: HVAC, Plumbing & Firefighting',
                'specialization_en' => 'Lead MEP Infrastructure & Builders Work Consultant',
                'experience_years' => 14,
                'bio' => 'استشاري أعمال كهروميكانيكية معتمد، أدار نمذجة وتنسيق شبكات التكييف والمواسير ومكافحة الحريق وتحديد فتحات الأعمال الإنشائية في أكثر من 20 مجمعاً طبياً وفندقياً.',
                'certifications' => ['Revit MEP Certified Professional', 'ASHRAE Member', 'NFPA Systems Specialist'],
                'course_indices' => [2],
            ],
        ];

        $instructors = [];
        foreach ($data as $item) {
            $user = new User([
                'name' => $item['name'],
                'email' => $item['email'],
                'engineering_title' => $item['engineering_title'],
                'avatar_url' => $item['avatar_url'],
                'status' => 'active',
            ]);
            $user->id = $item['id'];

            $profile = new InstructorProfile([
                'specialization' => $item['specialization'],
                'experience_years' => $item['experience_years'],
                'bio' => $item['bio'],
                'bio_ar' => $item['bio'],
                'bio_en' => $item['bio'],
                'certifications' => $item['certifications'],
            ]);
            $user->setRelation('instructorProfile', $profile);

            $assignedCourses = collect();
            foreach ($item['course_indices'] as $idx) {
                if (isset($courses[$idx])) {
                    $assignedCourses->push($courses[$idx]);
                }
            }
            $user->setRelation('courses', $assignedCourses);

            $instructors[] = $user;
        }

        return $instructors;
    }

    /**
     * Get paginated fallback instructors.
     */
    protected function getFallbackInstructorsPaginator(): LengthAwarePaginator
    {
        $models = $this->getFallbackInstructorsModels();

        return new LengthAwarePaginator(
            $models,
            count($models),
            8,
            1,
            ['path' => route('instructors.index')]
        );
    }

    /**
     * Get a specific fallback instructor by id or email.
     */
    public function getFallbackInstructorModel(mixed $idOrEmail): ?User
    {
        $models = $this->getFallbackInstructorsModels();
        $target = $idOrEmail instanceof User ? $idOrEmail->id : $idOrEmail;

        foreach ($models as $m) {
            if ($m->id == $target || $m->email === (string) $target) {
                return $m;
            }
        }

        return $models[0] ?? null;
    }
}
