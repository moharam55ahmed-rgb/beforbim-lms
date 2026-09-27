<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Models\Course;
use App\Modules\CourseReview\Models\CourseReview;
use App\Modules\Setting\Services\CmsSettingService;
use App\Modules\SupportTicket\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            ->take(4)
            ->get();

        return view('pages.home', [
            'cms' => $this->cms,
            'categories' => $categories,
            'featuredCourses' => $featuredCourses,
            'featuredInstructors' => $featuredInstructors,
            'topReviews' => $topReviews,
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
        $cmsArticles = $this->cms->get('blog_articles');
        if (is_array($cmsArticles) && count($cmsArticles) > 0) {
            $keyed = [];
            foreach ($cmsArticles as $art) {
                if (isset($art['slug'])) {
                    $keyed[$art['slug']] = $art;
                }
            }
            if (! empty($keyed)) {
                return $keyed;
            }
        }

        return [
            'iso-19650-bim-execution-plan-guide' => [
                'slug' => 'iso-19650-bim-execution-plan-guide',
                'title' => 'الدليل الشامل لإعداد خطة تنفيذ الـ BIM وفق المواصفة القياسية ISO 19650',
                'excerpt' => 'تعرف على العناصر الأساسية في صياغة الـ BEP ودور كل طرف في المشروع لضمان التنسيق الرقمي وتفادي النزاعات أثناء التنفيذ.',
                'author' => 'م. خالد الدوسري (BIM Director)',
                'category' => 'إدارة مشروعات BIM',
                'read_time' => '7 دقائق قراءة',
                'date' => '2026-09-20',
                'featured_image' => '/images/blog/iso-19650.jpg',
                'content' => 'تُعد خطة تنفيذ نمذجة معلومات البناء (BIM Execution Plan - BEP) الركيزة التشغيلية الأهم لنجاح المشروعات الهندسية الحديثة، وتحدد بوضوح متطلبات تبادل المعلومات والمعايير المعتمدة.',
            ],
            'revit-clash-detection-with-navisworks' => [
                'slug' => 'revit-clash-detection-with-navisworks',
                'title' => 'استراتيجيات اكتشاف التعارضات الهندسية (Clash Detection) وتقليل الهدر في المواقع',
                'excerpt' => 'كيف يمكن لمهندسي التنسيق الكهروميكانيكي والإنشائي توفير آلاف الدولارات في المشروعات الكبرى باستخدام Navisworks Manage.',
                'author' => 'م. أحمد الشمري (BIM Coordinator)',
                'category' => 'تنسيق ونمذجة',
                'read_time' => '5 دقائق قراءة',
                'date' => '2026-09-18',
                'featured_image' => '/images/blog/clash-detection.jpg',
                'content' => 'عملية الـ Clash Detection ليست مجرد نقرة زر، بل هي مصفوفة فنية دقيقة تبدأ بتحديد مستويات التسامح الهندسي وتحديد الأولويات بين العناصر الإنشائية والميكانيكية.',
            ],
            'dynamo-automation-for-structural-detailing' => [
                'slug' => 'dynamo-automation-for-structural-detailing',
                'title' => 'أتمتة تسليح المنشآت الخرسانية عبر Dynamo و Revit API: من النظرية إلى التطبيق',
                'excerpt' => 'خطوات بناء برمجيات حسابية تسرع وتيرة إنتاج اللوحات الإنشائية التنفيذية بنسبة تفوق 60% في المكاتب الفنية.',
                'author' => 'م. عمر فاروق (Computational Designer)',
                'category' => 'التصميم الحسابي البرمجي',
                'read_time' => '10 دقائق قراءة',
                'date' => '2026-09-12',
                'featured_image' => '/images/blog/dynamo-rebar.jpg',
                'content' => 'استخدام البارامترات الحسابية في توزيع كانات الأعمدة وشبكات تسليح البلاطات يختصر أشهراً من العمل اليدوي المتكرر ويمنع الأخطاء البشرية الشائعة.',
            ],
            'lod-350-vs-lod-400-fabrication-standards' => [
                'slug' => 'lod-350-vs-lod-400-fabrication-standards',
                'title' => 'الفرق الهندسي بين مستويات التفاصيل LOD 350 و LOD 400 في التصنيع والتركيب الميداني',
                'excerpt' => 'مقارنة فنية تطبيقية بين مستويات الـ LOD وفق دليل BIMForum وأهميتها في تصنيع العناصر مسبقة الصنع والوصلات المعدنية.',
                'author' => 'م. خالد الدوسري (BIM Director)',
                'category' => 'معايير النمذجة',
                'read_time' => '8 دقائق قراءة',
                'date' => '2026-09-08',
                'featured_image' => '/images/blog/lod-standards.jpg',
                'content' => 'يمثل مستوى التفاصيل LOD 350 حلقة الوصل بين التصميم والتنفيذ حيث يشمل أجزاء التثبيت والدعامات، في حين يتطلب LOD 400 تضمين كافة تفاصيل التصنيع والتركيب.',
            ],
            'mep-coordination-and-builders-work' => [
                'slug' => 'mep-coordination-and-builders-work',
                'title' => 'تنسيق فتحات الأعمال الإنشائية (Builders Work Openings) للأنظمة الكهروميكانيكية',
                'excerpt' => 'البروتوكول الهندسي لاعتماد وتمرير فتحات الدكتات والمواسير في الكمرات والحوائط الخرسانية المسلحة قبل الصب.',
                'author' => 'م. حسام العلي (Senior MEP Specialist)',
                'category' => 'هندسة MEP',
                'read_time' => '6 دقائق قراءة',
                'date' => '2026-09-05',
                'featured_image' => '/images/blog/mep-coordination.jpg',
                'content' => 'الاعتماد المبكر لمخططات Builders Work يضمن عدم اللجوء إلى التكسير والـ Coring في الخرسانة بعد صبها، مما يحافظ على السلامة الإنشائية للعناصر.',
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

        return view('pages.blog-show', [
            'cms' => $this->cms,
            'article' => $article,
        ]);
    }

    /**
     * Display the instructors index page.
     */
    public function instructors(): View
    {
        $instructors = User::whereHas('roles', function ($q) {
            $q->where('name', 'instructor');
        })
            ->with(['instructorProfile', 'courses' => function ($q) {
                $q->published();
            }])
            ->paginate(8);

        return view('pages.instructors', [
            'cms' => $this->cms,
            'instructors' => $instructors,
        ]);
    }

    /**
     * Display a specific instructor's profile.
     */
    public function instructorShow(User $user): View
    {
        if (! $user->hasRole('instructor')) {
            abort(404);
        }

        $user->load(['instructorProfile', 'courses' => function ($q) {
            $q->published()->with('category');
        }]);

        return view('pages.instructor-show', [
            'cms' => $this->cms,
            'instructor' => $user,
        ]);
    }
}
