<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\AccessControl\Models\Role;
use App\Modules\Cart\Models\Cart;
use App\Modules\Cart\Models\CartItem;
use App\Modules\Category\Models\Category;
use App\Modules\Certificate\Models\Certificate;
use App\Modules\Course\Models\Course;
use App\Modules\CourseAnnouncement\Models\CourseAnnouncement;
use App\Modules\CourseReview\Models\CourseReview;
use App\Modules\Curriculum\Models\CourseSection;
use App\Modules\Enrollment\Models\Enrollment;
use App\Modules\Lesson\Models\Lesson;
use App\Modules\Media\Models\LessonContent;
use App\Modules\Media\Models\LessonResource;
use App\Modules\Media\Models\MediaFile;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Models\OrderItem;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\Transaction;
use App\Modules\Progress\Models\LessonProgress;
use App\Modules\Setting\Models\CmsSetting;
use App\Modules\SupportTicket\Models\SupportTicket;
use App\Modules\User\Models\InstructorProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the complete demo data seeder.
     */
    public function run(): void
    {
        if (! Role::where('name', 'super_admin')->exists()) {
            $this->call(RolesAndPermissionsSeeder::class);
        }

        $defaultPassword = Hash::make('password123');

        // =========================================================================
        // 1. Platform CMS Settings
        // =========================================================================
        $cmsSettings = [
            // General
            ['key' => 'site_name', 'group' => 'branding', 'value' => 'Beforbim — BIM Engineering Academy', 'type' => 'string', 'is_public' => true],
            ['key' => 'site_name_ar', 'group' => 'general', 'value' => 'بيفور بيم — أكاديمية نمذجة معلومات البناء', 'type' => 'string', 'is_public' => true],
            ['key' => 'site_name_en', 'group' => 'general', 'value' => 'Beforbim — BIM Engineering Academy', 'type' => 'string', 'is_public' => true],
            ['key' => 'site_tagline', 'group' => 'branding', 'value' => 'The Premier BIM & Digital Construction Engineering Academy', 'type' => 'string', 'is_public' => true],
            ['key' => 'contact_email', 'group' => 'general', 'value' => 'info@beforbim.com', 'type' => 'string', 'is_public' => true],
            ['key' => 'contact_phone', 'group' => 'general', 'value' => '+20 2 2456 7890', 'type' => 'string', 'is_public' => true],
            ['key' => 'contact_address', 'group' => 'general', 'value' => 'Beforbim Engineering Center, New Cairo, Cairo, Egypt', 'type' => 'string', 'is_public' => true],
            ['key' => 'support_whatsapp', 'group' => 'general', 'value' => '+201001234567', 'type' => 'string', 'is_public' => true],
            ['key' => 'working_hours', 'group' => 'general', 'value' => 'Sunday — Thursday: 9:00 AM to 6:00 PM (Cairo Time)', 'type' => 'string', 'is_public' => true],
            ['key' => 'default_currency', 'group' => 'payment', 'value' => 'USD', 'type' => 'string', 'is_public' => true],
            ['key' => 'vat_percentage', 'group' => 'payment', 'value' => '14.00', 'type' => 'string', 'is_public' => true],
            ['key' => 'enforce_single_device', 'group' => 'security', 'value' => 'true', 'type' => 'boolean', 'is_public' => false],

            // Branding
            ['key' => 'brand_color_primary', 'group' => 'branding', 'value' => '#071A36', 'type' => 'string', 'is_public' => true],
            ['key' => 'brand_color_accent', 'group' => 'branding', 'value' => '#D4AF37', 'type' => 'string', 'is_public' => true],
            ['key' => 'logo_url', 'group' => 'branding', 'value' => '/images/branding/logo.png', 'type' => 'string', 'is_public' => true],
            ['key' => 'favicon_url', 'group' => 'branding', 'value' => '/favicon.ico', 'type' => 'string', 'is_public' => true],

            // Hero Section
            ['key' => 'hero_badge', 'group' => 'branding', 'value' => 'الاعتماد الأكاديمي الدولي وفق مواصفة ISO 19650', 'type' => 'string', 'is_public' => true],
            ['key' => 'hero_title_ar', 'group' => 'branding', 'value' => 'المنصة الهندسية الأولى المعتمدة لمهندسي الـ BIM وإدارة المشروعات الرقمية', 'type' => 'string', 'is_public' => true],
            ['key' => 'hero_subtitle_ar', 'group' => 'branding', 'value' => 'اكتسب مهارات متقدمة في Revit, Navisworks, Civil 3D, Dynamo مع نخبة من الخبراء والاستشاريين المعتمدين.', 'type' => 'string', 'is_public' => true],
            ['key' => 'hero_cta_primary_text', 'group' => 'branding', 'value' => 'استكشف دبلومات الـ BIM المعتمدة', 'type' => 'string', 'is_public' => true],
            ['key' => 'hero_cta_primary_link', 'group' => 'branding', 'value' => '/courses', 'type' => 'string', 'is_public' => true],
            ['key' => 'hero_cta_secondary_text', 'group' => 'branding', 'value' => 'منهجية Beforbim الأكاديمية', 'type' => 'string', 'is_public' => true],
            ['key' => 'hero_cta_secondary_link', 'group' => 'branding', 'value' => '/about', 'type' => 'string', 'is_public' => true],

            // About Content
            ['key' => 'about_headline', 'group' => 'branding', 'value' => 'نبني جيلاً من المهندسين القادرين على قيادة التحول الرقمي للتشييد', 'type' => 'string', 'is_public' => true],
            ['key' => 'about_mission', 'group' => 'branding', 'value' => 'جسر الفجوة بين التعليم الهندسي الأكاديمي والواقع العملي في المشروعات الضخمة عبر برامج مهنية تطبيقية.', 'type' => 'string', 'is_public' => true],
            ['key' => 'about_vision', 'group' => 'branding', 'value' => 'أن نكون المرجع الهندسي الرقمي الأول في الشرق الأوسط وإفريقيا لتأهيل مديري ومنسقي نمذجة معلومات البناء.', 'type' => 'string', 'is_public' => true],

            // SEO Settings
            ['key' => 'seo_meta_title', 'group' => 'branding', 'value' => 'Beforbim — أكاديمية نمذجة معلومات البناء وهندسة التشييد الرقمي', 'type' => 'string', 'is_public' => true],
            ['key' => 'seo_meta_description', 'group' => 'branding', 'value' => 'أكاديمية Beforbim الرائدة في برامج دبلومات BIM المعتمدة، هندسة التشييد الرقمي، وتطبيقات Revit, Navisworks, Civil 3D, و Dynamo مع نخبة من الاستشاريين الدوليين.', 'type' => 'string', 'is_public' => true],
            ['key' => 'seo_meta_keywords', 'group' => 'branding', 'value' => 'BIM, Revit, Navisworks, Civil 3D, Dynamo, نمذجة معلومات البناء, هندسة مدنية, كورسات هندسية, ISO 19650', 'type' => 'string', 'is_public' => true],
            ['key' => 'seo_og_image', 'group' => 'branding', 'value' => '/images/branding/og-cover.jpg', 'type' => 'string', 'is_public' => true],
            ['key' => 'seo_twitter_handle', 'group' => 'branding', 'value' => '@BeforbimAcademy', 'type' => 'string', 'is_public' => true],

            // Blog Articles (5 Articles)
            [
                'key' => 'blog_articles',
                'group' => 'general',
                'value' => [
                    [
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
                    [
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
                    [
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
                    [
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
                    [
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
                ],
                'type' => 'json',
                'is_public' => true,
            ],
        ];

        foreach ($cmsSettings as $setting) {
            CmsSetting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'group' => $setting['group'],
                    'value' => is_array($setting['value']) ? json_encode($setting['value']) : (string) $setting['value'],
                    'type' => $setting['type'],
                    'is_public' => $setting['is_public'],
                ]
            );
        }

        // =========================================================================
        // 2. Demo Users (Super Admin, Instructor, Student)
        // =========================================================================
        // 2.1 Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@beforbim.com'],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Eng. Abdelrahman Elnagar (Super Admin)',
                'password' => $defaultPassword,
                'phone' => '+201000000002',
                'phone_country_code' => '+20',
                'phone_number' => '1000000002',
                'engineering_title' => 'Chief Technology Officer & BIM Director',
                'status' => 'active',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]
        );
        $superAdmin->update(['status' => 'active', 'password' => $defaultPassword]);
        $superAdmin->assignRole('super_admin');
        $superAdmin->assignRole('admin');

        // 2.2 Instructor
        $instructor = User::firstOrCreate(
            ['email' => 'instructor@beforbim.com'],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Eng. Khaled El-Dossary',
                'password' => $defaultPassword,
                'phone' => '+201000000003',
                'phone_country_code' => '+20',
                'phone_number' => '1000000003',
                'engineering_title' => 'Senior Structural BIM Specialist & Consultant (Autodesk Certified)',
                'bio' => 'Senior Engineering Consultant and Accredited BIM Lecturer with 12+ years of experience directing mega-scale digital construction projects in Egypt and the Middle East.',
                'status' => 'active',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]
        );
        $instructor->update([
            'status' => 'active',
            'password' => $defaultPassword,
            'avatar_url' => '/images/instructors/khaled_avatar.jpg',
        ]);
        $instructor->assignRole('instructor');

        InstructorProfile::updateOrCreate(
            ['user_id' => $instructor->id],
            [
                'bio' => 'Senior Engineering Consultant and Accredited BIM Lecturer with 12+ years of experience directing mega-scale digital construction projects in Egypt and the Middle East in accordance with ISO 19650.',
                'specialization' => 'Senior Structural BIM Specialist & Project Coordinator',
                'experience_years' => 12,
                'education' => 'M.Sc. Structural Engineering — Cairo University, Egypt',
                'certifications' => ['Autodesk Certified Professional (Revit Structure)', 'ISO 19650 Certified BIM Manager', 'BuildingSMART International Professional'],
                'linkedin_url' => 'https://linkedin.com/in/beforbim-instructor',
                'website_url' => 'https://beforbim.com/instructors/khaled',
                'profile_status' => 'approved',
            ]
        );

        // 2.3 Student
        $student = User::firstOrCreate(
            ['email' => 'student@beforbim.com'],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Eng. Ahmed El-Shammari',
                'password' => $defaultPassword,
                'phone' => '+201000000004',
                'phone_country_code' => '+20',
                'phone_number' => '1000000004',
                'engineering_title' => 'Civil Site & BIM Engineer',
                'bio' => 'Civil site engineer specializing in 3D concrete modeling and digital construction management.',
                'status' => 'active',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
            ]
        );
        $student->update(['status' => 'active', 'password' => $defaultPassword]);
        $student->assignRole('student');

        // =========================================================================
        // 3. BIM Categories (Architecture, Structural, MEP, Coordination, Automation)
        // =========================================================================
        $categoriesData = [
            [
                'name_ar' => 'نمذجة العمارة (Architectural BIM)',
                'name_en' => 'Architecture',
                'slug' => 'architectural-bim',
                'description_ar' => 'دورات احتراف إعداد النماذج المعمارية، إخراج المخططات التنفيذية وتفاصيل الـ LOD 350.',
                'display_order' => 1,
            ],
            [
                'name_ar' => 'الهندسة الإنشائية (Structural BIM)',
                'name_en' => 'Structural',
                'slug' => 'structural-bim',
                'description_ar' => 'نمذجة المنشآت الخرسانية والمعدنية، تفاصيل التسليح واستخراج جداول الكميات عبر Revit و Tekla.',
                'display_order' => 2,
            ],
            [
                'name_ar' => 'الأنظمة الكهروميكانيكية (MEP BIM)',
                'name_en' => 'MEP',
                'slug' => 'mep',
                'description_ar' => 'تصميم ونمذجة مسارات التكييف والصحي ومكافحة الحريق والإنارة وتنسيق المسارات.',
                'display_order' => 3,
            ],
            [
                'name_ar' => 'التنسيق وإدارة التعارضات (Coordination & Clash Detection)',
                'name_en' => 'Coordination',
                'slug' => 'bim-coordination',
                'description_ar' => 'التنسيق الفيدرالي الشامل بين التخصصات، فحص التعارضات، وإعداد مصفوفات التنسيق.',
                'display_order' => 4,
            ],
            [
                'name_ar' => 'التصميم الحسابي والأتمتة (Automation & Dynamo)',
                'name_en' => 'Automation',
                'slug' => 'computational-bim',
                'description_ar' => 'أتمتة المهام الهندسية المتكررة وبرمجة المعاملات البارامترية عبر خوارزميات Dynamo و Python.',
                'display_order' => 5,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::firstOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        // Keep aliases for quick lookup
        $catArch = $categories['architectural-bim'];
        $catStruct = $categories['structural-bim'];
        $catMep = $categories['mep'];
        $catCoord = $categories['bim-coordination'];
        $catAuto = $categories['computational-bim'];

        // =========================================================================
        // 4. Courses & Curriculum (5 Realistic BIM Courses)
        // =========================================================================

        // Course 1: Architecture (LOD 350)
        $course1 = Course::firstOrCreate(
            ['slug' => 'revit-architecture-lod350-masterclass'],
            [
                'uuid' => (string) Str::uuid(),
                'instructor_id' => $instructor->id,
                'category_id' => $catArch->id,
                'title_ar' => 'الدبلومة الاحترافية في نمذجة العمارة عبر Autodesk Revit (LOD 350)',
                'title_en' => 'Autodesk Revit Architecture LOD 350 Professional Diploma',
                'short_description_ar' => 'تعلم إعداد النماذج المعمارية المعقدة، إخراج المخططات التنفيذية، وإدارة تفاصيل المشروعات وفق الأكواد الدولية.',
                'description_ar' => "تغطي هذه الدبلومة الشاملة كافة مراحل إعداد النموذج المعماري الرقمي بداية من ضبط الإحداثيات المشتركة (Shared Coordinates)، ونمذجة العناصر المركبة، وإنشاء الكتل البارامترية (Families)، وصولاً إلى إخراج الجداول الزمنية للكميات وتصدير ملفات الـ IFC المعيارية.\n\nتركز الدورة على التطبيق الميداني في مشروعات حقيقية وتأهيل المهندس لاجتياز اختبارات الاعتماد من Autodesk.",
                'level' => 'INTERMEDIATE',
                'price' => 1200.00,
                'sale_price' => 899.00,
                'currency' => 'USD',
                'thumbnail_url' => '/images/courses/revit_arch.jpg',
                'promo_video_url' => 'https://cdn.beforbim.com/promo/revit-arch.mp4',
                'software_requirements' => ['Autodesk Revit 2024 أو أحدث', 'Navisworks Manage', 'نظام Windows 11'],
                'prerequisites' => ['معرفة هندسية أساسية بالرسم والتصميم المعماري'],
                'learning_outcomes' => [
                    'بناء نماذج معمارية دقيقة بمستوى تفاصيل LOD 350',
                    'إنشاء الـ Custom Parametric Families المتقدمة',
                    'استخراج وحصر الكميات التنفيذية بدقة فائقة',
                    'تصدير المخططات التنفيذية ولوحات الـ Shop Drawings',
                ],
                'meta_title_ar' => 'دبلومة ريفيت معماري LOD 350 احترافية | Beforbim',
                'meta_title_en' => 'Revit Architecture LOD 350 Diploma | Beforbim',
                'meta_description_ar' => 'تعلم نمذجة العمارة المتقدمة وحصر الكميات التنفيذية عبر Autodesk Revit',
                'meta_description_en' => 'Master architectural BIM modeling at LOD 350 with Autodesk Revit',
                'meta_keywords' => ['Revit', 'LOD 350', 'نمذجة معمارية', 'BIM Architecture'],
                'status' => 'APPROVED',
                'preview_enabled' => true,
                'approved_at' => now()->subDays(10),
                'published_at' => now()->subDays(9),
            ]
        );

        $c1Sec1 = CourseSection::firstOrCreate(
            ['course_id' => $course1->id, 'order_index' => 1],
            ['title_ar' => 'المقدمة وإعداد بيئة عمل الـ BIM', 'title_en' => 'Introduction & BIM Workspace Setup']
        );
        $c1L1 = Lesson::firstOrCreate(
            ['section_id' => $c1Sec1->id, 'order_index' => 1],
            [
                'title_ar' => 'فلسفة نمذجة معلومات البناء وتكامل التخصصات',
                'title_en' => 'BIM Philosophy & Interdisciplinary Integration',
                'lesson_type' => 'VIDEO',
                'duration_seconds' => 1250,
                'is_preview_free' => true,
                'is_preview' => true,
                'is_mandatory' => true,
            ]
        );
        LessonContent::firstOrCreate(
            ['lesson_id' => $c1L1->id],
            [
                'video_provider' => 'HLS',
                'video_asset_id' => 'v-arch-001',
                'video_hls_url' => 'https://stream.beforbim.com/hls/c1l1/master.m3u8',
                'document_markdown' => '## محاور الدرس الأول\nشرح دور نمذجة معلومات البناء في تقليل الهدر وزمن المشروع وفق معايير ISO 19650.',
            ]
        );
        LessonResource::firstOrCreate(
            ['lesson_id' => $c1L1->id, 'file_name' => 'BIM_ISO19650_Summary.pdf'],
            [
                'title_ar' => 'ملخص معيار ISO 19650 للتوثيق الهندسي',
                'title_en' => 'ISO 19650 Engineering Documentation Summary',
                'file_path' => 'courses/c1/resources/BIM_ISO19650_Summary.pdf',
                'file_extension' => 'pdf',
                'file_size_bytes' => 1540000,
                'mime_type' => 'application/pdf',
                'is_downloadable' => true,
            ]
        );

        $c1L2 = Lesson::firstOrCreate(
            ['section_id' => $c1Sec1->id, 'order_index' => 2],
            [
                'title_ar' => 'ضبط الإحداثيات الجغرافية المشتركة (Shared Coordinates)',
                'title_en' => 'Setting Up Shared Coordinates',
                'lesson_type' => 'VIDEO',
                'duration_seconds' => 1840,
                'is_preview_free' => true,
                'is_preview' => true,
                'is_mandatory' => true,
            ]
        );
        LessonContent::firstOrCreate(
            ['lesson_id' => $c1L2->id],
            [
                'video_provider' => 'HLS',
                'video_asset_id' => 'v-arch-002',
                'video_hls_url' => 'https://stream.beforbim.com/hls/c1l2/master.m3u8',
            ]
        );

        $c1Sec2 = CourseSection::firstOrCreate(
            ['course_id' => $course1->id, 'order_index' => 2],
            ['title_ar' => 'النمذجة المعمارية المتقدمة والتفاصيل', 'title_en' => 'Advanced Architectural Detailing']
        );
        $c1L3 = Lesson::firstOrCreate(
            ['section_id' => $c1Sec2->id, 'order_index' => 1],
            [
                'title_ar' => 'نمذجة الحوائط الستائرية والواجهات المعقدة (Curtain Walls)',
                'title_en' => 'Modeling Complex Curtain Walls',
                'lesson_type' => 'VIDEO',
                'duration_seconds' => 2400,
                'is_preview_free' => false,
                'is_preview' => false,
                'is_mandatory' => true,
            ]
        );
        LessonContent::firstOrCreate(
            ['lesson_id' => $c1L3->id],
            [
                'video_provider' => 'HLS',
                'video_asset_id' => 'v-arch-003',
                'video_hls_url' => 'https://stream.beforbim.com/hls/c1l3/master.m3u8',
            ]
        );

        $c1L4 = Lesson::firstOrCreate(
            ['section_id' => $c1Sec2->id, 'order_index' => 2],
            [
                'title_ar' => 'دليل إخراج لوحات الشوب دروينج وحصر الكميات',
                'title_en' => 'Shop Drawings & Quantity Takeoff Guide',
                'lesson_type' => 'DOCUMENT',
                'duration_seconds' => 900,
                'is_preview_free' => false,
                'is_preview' => false,
                'is_mandatory' => true,
            ]
        );
        LessonContent::firstOrCreate(
            ['lesson_id' => $c1L4->id],
            [
                'document_markdown' => "### خطوات إخراج لوحات الـ Shop Drawings المعمارية:\n1. ضبط مقياس الرسم ومرشحات العرض (View Filters).\n2. إدراج الأبعاد التفصيلية ومناسيب التشطيب.\n3. إنشاء جداول التشطيبات وحصر كميات البلوك والدهانات.",
            ]
        );

        // Course 2: Structural (Revit Structure & Rebar)
        $course2 = Course::firstOrCreate(
            ['slug' => 'revit-structure-rebar-detailing-masterclass'],
            [
                'uuid' => (string) Str::uuid(),
                'instructor_id' => $instructor->id,
                'category_id' => $catStruct->id,
                'title_ar' => 'دبلومة النمذجة والتفاصيل الإنشائية المتقدمة وتفريد التسليح (Revit Structure)',
                'title_en' => 'Advanced Structural BIM Detailing & Rebar Modeling Masterclass',
                'short_description_ar' => 'احتراف نمذجة العناصر الإنشائية الخرسانية والمعدنية وتفريد حديد التسليح وحساب أطوال الوصلات وأوزان الحديد.',
                'description_ar' => 'برنامج تطبيقي عميق يركز على إنشاء النماذج التحليلية والفيزيائية للمنشآت، نمذجة شبكات التسليح 3D Rebar للعناصر المعقدة، واستخراج جداول تفريد الحديد Bending Schedules وفق الكود الأمريكي ACI والمصري والسعودي.',
                'level' => 'ADVANCED',
                'price' => 1400.00,
                'sale_price' => 999.00,
                'currency' => 'USD',
                'thumbnail_url' => '/images/courses/revit_struct.jpg',
                'software_requirements' => ['Autodesk Revit 2024 (Structure)', 'Robot Structural Analysis'],
                'prerequisites' => ['فهم أساسيات التصميم الإنشائي الخرساني والمعدني'],
                'learning_outcomes' => [
                    'نمذجة العناصر الإنشائية بدقة هندسية عالية',
                    'تفريد وتفصيل حديد التسليح Rebar 3D وحساب الأطوال والأوزان',
                    'استخراج لوحات الشوب دروينج الإنشائية وحصر حديد التسليح',
                ],
                'meta_title_ar' => 'دورة ريفيت إنشائي وتفريد تسليح Rebar 3D | Beforbim',
                'meta_description_ar' => 'احتراف نمذجة وتفصيل حديد التسليح واستخراج جداول الحصر التنفيذية',
                'meta_keywords' => ['Revit Structure', 'Rebar 3D', 'تفريد حديد', 'BIM إنشائي'],
                'status' => 'APPROVED',
                'preview_enabled' => true,
                'approved_at' => now()->subDays(9),
                'published_at' => now()->subDays(8),
            ]
        );

        $c2Sec1 = CourseSection::firstOrCreate(
            ['course_id' => $course2->id, 'order_index' => 1],
            ['title_ar' => 'أساسيات النمذجة الإنشائية', 'title_en' => 'Structural Modeling Basics']
        );
        $c2L1 = Lesson::firstOrCreate(
            ['section_id' => $c2Sec1->id, 'order_index' => 1],
            [
                'title_ar' => 'النمذجة الفيزيائية والتحليلية للقواعد والأعمدة',
                'title_en' => 'Physical & Analytical Modeling of Foundations',
                'lesson_type' => 'VIDEO',
                'duration_seconds' => 1500,
                'is_preview_free' => true,
                'is_preview' => true,
                'is_mandatory' => true,
            ]
        );
        LessonContent::firstOrCreate(
            ['lesson_id' => $c2L1->id],
            [
                'video_provider' => 'HLS',
                'video_asset_id' => 'v-struct-001',
                'video_hls_url' => 'https://stream.beforbim.com/hls/c2l1/master.m3u8',
            ]
        );
        LessonResource::firstOrCreate(
            ['lesson_id' => $c2L1->id, 'file_name' => 'Structural_Concrete_Codes.pdf'],
            [
                'title_ar' => 'دليل أكواد تصميم وتفاصيل التسليح ACI 318',
                'title_en' => 'ACI 318 Detailing Reference',
                'file_path' => 'courses/c2/resources/Structural_Concrete_Codes.pdf',
                'file_extension' => 'pdf',
                'file_size_bytes' => 2300000,
                'mime_type' => 'application/pdf',
                'is_downloadable' => true,
            ]
        );

        $c2L2 = Lesson::firstOrCreate(
            ['section_id' => $c2Sec1->id, 'order_index' => 2],
            [
                'title_ar' => 'تفريد حديد التسليح ثلاثي الأبعاد Rebar Modeling',
                'title_en' => '3D Rebar Detailing & Bar Schedules',
                'lesson_type' => 'VIDEO',
                'duration_seconds' => 2100,
                'is_preview_free' => false,
                'is_preview' => false,
                'is_mandatory' => true,
            ]
        );
        LessonContent::firstOrCreate(
            ['lesson_id' => $c2L2->id],
            [
                'video_provider' => 'HLS',
                'video_asset_id' => 'v-struct-002',
                'video_hls_url' => 'https://stream.beforbim.com/hls/c2l2/master.m3u8',
            ]
        );

        // Course 3: MEP (Revit MEP)
        $course3 = Course::firstOrCreate(
            ['slug' => 'revit-mep-hvac-plumbing-firefighting'],
            [
                'uuid' => (string) Str::uuid(),
                'instructor_id' => $instructor->id,
                'category_id' => $catMep->id,
                'title_ar' => 'احتراف نمذجة الأنظمة الكهروميكانيكية (Revit MEP: HVAC, Plumbing & Firefighting)',
                'title_en' => 'Comprehensive Revit MEP: HVAC, Plumbing, Firefighting & Electrical',
                'short_description_ar' => 'تصميم ونمذجة شبكات التكييف والمواسير ومكافحة الحريق والإنارة وتنسيق المسارات الهندسية داخل المباني.',
                'description_ar' => 'تأهيل متكامل لمهندسي الميكانيكا والكهرباء لإتقان نمذجة الدكتات، مواسير المياه والصرف، كابلات الكهرباء، مع حسابات الأحمال الهيدروليكية وفقد الضغط مباشرة من داخل Revit.',
                'level' => 'INTERMEDIATE',
                'price' => 1350.00,
                'sale_price' => 950.00,
                'currency' => 'USD',
                'thumbnail_url' => '/images/courses/revit_mep.jpg',
                'software_requirements' => ['Autodesk Revit MEP 2024'],
                'prerequisites' => ['مبادئ التصميم الكهروميكانيكي للمباني'],
                'learning_outcomes' => [
                    'نمذجة مجاري الهواء Ductwork والمواسير Piping بدقة',
                    'توزيع وحدات الإنارة واللوحات الكهربائية',
                    'فحص السريان وفقد الضغط داخل الشبكات',
                ],
                'meta_title_ar' => 'دورة ريفيت كهروميكانيك MEP شاملة | Beforbim',
                'meta_description_ar' => 'تعلم نمذجة شبكات التكييف والحريق والصرف والكهرباء عبر Revit MEP',
                'meta_keywords' => ['Revit MEP', 'HVAC', 'Plumbing', 'Firefighting', 'BIM ميكانيكا'],
                'status' => 'APPROVED',
                'preview_enabled' => true,
                'approved_at' => now()->subDays(7),
                'published_at' => now()->subDays(6),
            ]
        );

        $c3Sec1 = CourseSection::firstOrCreate(
            ['course_id' => $course3->id, 'order_index' => 1],
            ['title_ar' => 'منظومة التكييف والتهوية (HVAC Systems)', 'title_en' => 'HVAC Modeling & Sizing']
        );
        $c3L1 = Lesson::firstOrCreate(
            ['section_id' => $c3Sec1->id, 'order_index' => 1],
            [
                'title_ar' => 'نمذجة شبكات مجاري الهواء وتحديد أقطار الدكتات',
                'title_en' => 'Duct Sizing & Air Distribution',
                'lesson_type' => 'VIDEO',
                'duration_seconds' => 1700,
                'is_preview_free' => true,
                'is_preview' => true,
                'is_mandatory' => true,
            ]
        );
        LessonContent::firstOrCreate(
            ['lesson_id' => $c3L1->id],
            [
                'video_provider' => 'HLS',
                'video_asset_id' => 'v-mep-001',
                'video_hls_url' => 'https://stream.beforbim.com/hls/c3l1/master.m3u8',
            ]
        );
        LessonResource::firstOrCreate(
            ['lesson_id' => $c3L1->id, 'file_name' => 'MEP_Duct_Sizing_Chart.pdf'],
            [
                'title_ar' => 'جدول حسابات مقاسات الدكتات وسرعات الهواء',
                'title_en' => 'Duct Friction & Sizing Chart',
                'file_path' => 'courses/c3/resources/MEP_Duct_Sizing_Chart.pdf',
                'file_extension' => 'pdf',
                'file_size_bytes' => 1100000,
                'mime_type' => 'application/pdf',
                'is_downloadable' => true,
            ]
        );

        // Course 4: Coordination (Navisworks Manage & 4D)
        $course4 = Course::firstOrCreate(
            ['slug' => 'navisworks-clash-detection-4d-bim'],
            [
                'uuid' => (string) Str::uuid(),
                'instructor_id' => $instructor->id,
                'category_id' => $catCoord->id,
                'title_ar' => 'إدارة التنسيق الهندسي واكتشاف التعارضات (Navisworks Manage & 4D BIM)',
                'title_en' => 'BIM Coordination, Clash Detection & 4D Simulation with Navisworks',
                'short_description_ar' => 'احتراف التنسيق الشامل بين المعماري والإشائي والكهروميكانيكي MEP، وإعداد تقارير التعارضات ومحاكاة الجداول الزمنية.',
                'description_ar' => 'تعلم كيف تتجنب التعديلات المكلفة بالموقع عبر التنسيق الرقمي الاستباقي. ستتدرب على دمج النماذج ذات الامتدادات المختلفة NWC, RVT, IFC وإنشاء مصفوفات التعارضات وحل المشكلات الهندسية.',
                'level' => 'ADVANCED',
                'price' => 1500.00,
                'sale_price' => 1099.00,
                'currency' => 'USD',
                'thumbnail_url' => '/images/courses/navisworks_4d.jpg',
                'software_requirements' => ['Autodesk Navisworks Manage 2024', 'Revit 2024'],
                'prerequisites' => ['معرفة مسبقة بأحد برامج الـ BIM مثل Revit'],
                'learning_outcomes' => [
                    'إجراء فحص التعارضات الشامل (Hard & Soft Clashes)',
                    'إعداد تقارير BCF وإسناد المهام لفرق التصميم',
                    'محاكاة حركة التشييد الزمني عبر TimeLiner (4D)',
                ],
                'meta_title_ar' => 'دورة نافيس ووركس وكشف التعارضات والمحاكاة الزمنية 4D | Beforbim',
                'meta_description_ar' => 'احتراف التنسيق الهندسي وكشف التعارضات ومحاكاة الجداول الزمنية عبر Navisworks Manage',
                'meta_keywords' => ['Navisworks', 'Clash Detection', 'TimeLiner', '4D BIM', 'تنسيق هندسي'],
                'status' => 'APPROVED',
                'preview_enabled' => true,
                'approved_at' => now()->subDays(8),
                'published_at' => now()->subDays(7),
            ]
        );

        $c4Sec1 = CourseSection::firstOrCreate(
            ['course_id' => $course4->id, 'order_index' => 1],
            ['title_ar' => 'أساسيات التنسيق واستيراد النماذج', 'title_en' => 'Coordination Basics']
        );
        $c4L1 = Lesson::firstOrCreate(
            ['section_id' => $c4Sec1->id, 'order_index' => 1],
            [
                'title_ar' => 'إنشاء بيئة التنسيق الموحدة الفيدرالية (Federated Model)',
                'title_en' => 'Creating Federated BIM Models',
                'lesson_type' => 'VIDEO',
                'duration_seconds' => 1600,
                'is_preview_free' => true,
                'is_preview' => true,
                'is_mandatory' => true,
            ]
        );
        LessonContent::firstOrCreate(
            ['lesson_id' => $c4L1->id],
            [
                'video_provider' => 'HLS',
                'video_asset_id' => 'v-coord-001',
                'video_hls_url' => 'https://stream.beforbim.com/hls/c4l1/master.m3u8',
            ]
        );
        LessonResource::firstOrCreate(
            ['lesson_id' => $c4L1->id, 'file_name' => 'Clash_Matrix_Template.xlsx'],
            [
                'title_ar' => 'مصفوفة أولويات التعارضات الهندسية (Clash Matrix)',
                'title_en' => 'Clash Resolution Priority Matrix',
                'file_path' => 'courses/c4/resources/Clash_Matrix_Template.xlsx',
                'file_extension' => 'xlsx',
                'file_size_bytes' => 450000,
                'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'is_downloadable' => true,
            ]
        );

        // Course 5: Automation (Dynamo & Python)
        $course5 = Course::firstOrCreate(
            ['slug' => 'computational-bim-dynamo-automation'],
            [
                'uuid' => (string) Str::uuid(),
                'instructor_id' => $instructor->id,
                'category_id' => $catAuto->id,
                'title_ar' => 'أتمتة الأعمال الهندسية والتصميم البرمجي عبر Dynamo و Python',
                'title_en' => 'Parametric Design & Computational BIM with Dynamo and Python',
                'short_description_ar' => 'انتقل بمستواك إلى البرمجة الهندسية وأتمتة النمذجة والحسابات المعقدة لتوفير مئات الساعات في المشروعات الضخمة.',
                'description_ar' => 'دورة متقدمة تركز على البارامترات الحسابية، برمجة الـ Nodes في Dynamo، والتعامل مع عناصر Revit API لإنشاء سكربتات تسليح وتوليد كتل معمارية وتوزيع عناصر MEP تلقائياً.',
                'level' => 'EXPERT',
                'price' => 1800.00,
                'sale_price' => 1350.00,
                'currency' => 'USD',
                'thumbnail_url' => '/images/courses/dynamo_python.jpg',
                'software_requirements' => ['Autodesk Revit 2024 مع Dynamo'],
                'prerequisites' => ['خبرة جيدة في استخدام برنامج Revit'],
                'learning_outcomes' => [
                    'بناء خوارزميات أتمتة المهام الهندسية المتكررة',
                    'التعامل مع ملفات Excel واستيراد وتصدير البيانات البرمجية',
                    'استخدام كود بايثون Python Scripting داخل بيئة Dynamo',
                ],
                'meta_title_ar' => 'دورة دينامو ريفيت وأتمتة التصميم البرمجي | Beforbim',
                'meta_description_ar' => 'تعلم برمجة خوارزميات Dynamo و Python Scripting لأتمتة أعمال الـ BIM',
                'meta_keywords' => ['Dynamo', 'Python', 'Revit API', 'Computational BIM', 'أتمتة التصميم'],
                'status' => 'APPROVED',
                'preview_enabled' => true,
                'approved_at' => now()->subDays(6),
                'published_at' => now()->subDays(5),
            ]
        );

        $c5Sec1 = CourseSection::firstOrCreate(
            ['course_id' => $course5->id, 'order_index' => 1],
            ['title_ar' => 'مقدمة البرمجة البارامترية وعقد Dynamo', 'title_en' => 'Dynamo Visual Programming']
        );
        $c5L1 = Lesson::firstOrCreate(
            ['section_id' => $c5Sec1->id, 'order_index' => 1],
            [
                'title_ar' => 'هندسة البيانات والعقد الأساسية في Dynamo',
                'title_en' => 'Data Structures & Core Nodes',
                'lesson_type' => 'VIDEO',
                'duration_seconds' => 1950,
                'is_preview_free' => true,
                'is_preview' => true,
                'is_mandatory' => true,
            ]
        );
        LessonContent::firstOrCreate(
            ['lesson_id' => $c5L1->id],
            [
                'video_provider' => 'HLS',
                'video_asset_id' => 'v-dynamo-001',
                'video_hls_url' => 'https://stream.beforbim.com/hls/c5l1/master.m3u8',
            ]
        );
        LessonResource::firstOrCreate(
            ['lesson_id' => $c5L1->id, 'file_name' => 'Auto_Rebar_Script.dyn'],
            [
                'title_ar' => 'سكربت Dynamo لتوليد كانات الأعمدة تلقائياً',
                'title_en' => 'Automatic Column Rebar Dynamo Script',
                'file_path' => 'courses/c5/resources/Auto_Rebar_Script.dyn',
                'file_extension' => 'dyn',
                'file_size_bytes' => 180000,
                'mime_type' => 'application/octet-stream',
                'is_downloadable' => true,
            ]
        );

        // Course Announcements
        CourseAnnouncement::firstOrCreate(
            ['course_id' => $course1->id, 'title' => 'مرحباً بجميع المهندسين المنضمين للدبلومة!'],
            [
                'instructor_id' => $instructor->id,
                'content' => 'تم رفع ملفات المشروعات التدريبية والـ Revit Families في قسم الموارد. يرجى تحميلها قبل بدء المحاضرة الثانية.',
                'published_at' => now()->subDays(5),
            ]
        );

        // =========================================================================
        // 5. Commerce (Cart, Orders, Payments, Transactions, Enrollments)
        // =========================================================================

        // 5.1 Cart Example (Student has Course 5 in Cart)
        $cart = Cart::firstOrCreate(
            ['user_id' => $student->id],
            [
                'coupon_code' => null,
                'discount_amount' => 0.00,
            ]
        );
        CartItem::firstOrCreate(
            ['cart_id' => $cart->id, 'purchasable_id' => $course5->id],
            [
                'purchasable_type' => Course::class,
                'course_id' => $course5->id,
                'unit_price' => $course5->sale_price ?? $course5->price,
            ]
        );

        // 5.2 Completed Order (Student purchased Course 1)
        $completedOrder = Order::firstOrCreate(
            ['order_number' => 'ORD-2026-000101'],
            [
                'user_id' => $student->id,
                'subtotal' => 1200.00,
                'discount_amount' => 301.00,
                'tax_amount' => 0.00,
                'total_amount' => 899.00,
                'currency' => 'USD',
                'status' => 'COMPLETED',
                'notes' => 'Paid successfully via Credit Card',
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $completedOrder->id, 'course_id' => $course1->id],
            [
                'purchasable_type' => Course::class,
                'purchasable_id' => $course1->id,
                'title_snapshot' => $course1->title_ar,
                'unit_price' => 899.00,
                'total_price' => 899.00,
            ]
        );

        $payment1 = Payment::firstOrCreate(
            ['order_id' => $completedOrder->id],
            [
                'uuid' => (string) Str::uuid(),
                'payment_method' => 'CREDIT_CARD',
                'gateway' => 'paymob',
                'amount' => 899.00,
                'currency' => 'USD',
                'status' => 'SUCCESS',
                'verified_at' => now()->subDays(5),
            ]
        );

        Transaction::firstOrCreate(
            ['payment_id' => $payment1->id],
            [
                'uuid' => (string) Str::uuid(),
                'transaction_type' => 'CHARGE',
                'gateway_reference' => 'PAYMOB-REF-88992211',
                'amount' => 899.00,
                'currency' => 'USD',
                'fee_amount' => 22.48,
                'net_amount' => 876.52,
                'settled_at' => now()->subDays(5),
            ]
        );

        // 5.3 Pending Order (Student placed order for Course 2 via Bank Transfer)
        $pendingOrder = Order::firstOrCreate(
            ['order_number' => 'ORD-2026-000102'],
            [
                'user_id' => $student->id,
                'subtotal' => 1400.00,
                'discount_amount' => 401.00,
                'tax_amount' => 0.00,
                'total_amount' => 999.00,
                'currency' => 'USD',
                'status' => 'PENDING',
                'notes' => 'Awaiting bank transfer verification',
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $pendingOrder->id, 'course_id' => $course2->id],
            [
                'purchasable_type' => Course::class,
                'purchasable_id' => $course2->id,
                'title_snapshot' => $course2->title_ar,
                'unit_price' => 999.00,
                'total_price' => 999.00,
            ]
        );

        Payment::firstOrCreate(
            ['order_id' => $pendingOrder->id],
            [
                'uuid' => (string) Str::uuid(),
                'payment_method' => 'BANK_TRANSFER',
                'gateway' => 'manual_bank',
                'amount' => 999.00,
                'currency' => 'USD',
                'status' => 'PENDING',
                'receipt_attachment_url' => '/receipts/transfer_sample_01.jpg',
            ]
        );

        // 5.4 Enrollments
        // Enrollment 1: Course 1 (Completed with certificate)
        $enrollment1 = Enrollment::firstOrCreate(
            ['user_id' => $student->id, 'course_id' => $course1->id],
            [
                'enrollable_type' => Course::class,
                'enrollable_id' => $course1->id,
                'order_id' => $completedOrder->id,
                'source' => 'DIRECT_PURCHASE',
                'status' => 'COMPLETED',
                'progress_percentage' => 100.00,
                'enrolled_at' => now()->subDays(5),
                'completed_at' => now()->subDays(2),
            ]
        );

        // Enrollment 2: Course 4 (Active in-progress)
        Enrollment::firstOrCreate(
            ['user_id' => $student->id, 'course_id' => $course4->id],
            [
                'enrollable_type' => Course::class,
                'enrollable_id' => $course4->id,
                'source' => 'DIRECT_PURCHASE',
                'status' => 'ACTIVE',
                'progress_percentage' => 50.00,
                'enrolled_at' => now()->subDays(3),
            ]
        );

        // =========================================================================
        // 6. Learning Progress & Certification
        // =========================================================================

        // Lesson progress for Course 1 (All lessons completed)
        foreach ([$c1L1, $c1L2, $c1L3, $c1L4] as $lesson) {
            LessonProgress::updateOrCreate(
                ['user_id' => $student->id, 'lesson_id' => $lesson->id],
                [
                    'enrollment_id' => $enrollment1->id,
                    'is_completed' => true,
                    'last_playback_position_seconds' => $lesson->duration_seconds,
                    'total_watch_seconds' => $lesson->duration_seconds,
                    'completed_at' => now()->subDays(2),
                ]
            );
        }

        // Issue Certificate for Course 1
        Certificate::firstOrCreate(
            ['user_id' => $student->id, 'course_id' => $course1->id],
            [
                'uuid' => (string) Str::uuid(),
                'certificate_number' => 'BEFORBIM-CERT-2026-8801',
                'enrollment_id' => $enrollment1->id,
                'student_name_snapshot' => $student->name,
                'course_title_snapshot_ar' => $course1->title_ar,
                'course_title_snapshot_en' => $course1->title_en,
                'instructor_name_snapshot' => $instructor->name,
                'grade_percentage' => 96.50,
                'issued_at' => now()->subDays(2),
                'qr_verification_url' => config('app.url').'/verify-certificate/BEFORBIM-CERT-2026-8801',
                'is_revoked' => false,
            ]
        );

        // =========================================================================
        // 7. Course Reviews & Ratings
        // =========================================================================
        CourseReview::updateOrCreate(
            ['course_id' => $course1->id, 'student_id' => $student->id],
            [
                'rating' => 5,
                'review_text' => 'دورة استثنائية وشرح عملي دقيق جداً لمستويات تفاصيل الـ LOD 350. أنصح بشدة كل مهندس معماري بالانضمام.',
                'status' => 'approved',
            ]
        );

        CourseReview::updateOrCreate(
            ['course_id' => $course4->id, 'student_id' => $student->id],
            [
                'rating' => 5,
                'review_text' => 'أفضل دورة عربية في كشف التعارضات وإعداد مصفوفات التنسيق وحسابات الـ 4D عبر Navisworks.',
                'status' => 'approved',
            ]
        );

        CourseReview::updateOrCreate(
            ['course_id' => $course2->id, 'student_id' => $student->id],
            [
                'rating' => 5,
                'review_text' => 'دورة تفريد حديد التسليح 3D Rebar نقلت مستوى المخرجات الإنشائية في مكتبي الفني إلى مستوى عالمي.',
                'status' => 'approved',
            ]
        );

        // =========================================================================
        // 8. Support Tickets
        // =========================================================================
        $ticket1 = SupportTicket::firstOrCreate(
            ['ticket_number' => 'TCK-2026-ENG001'],
            [
                'user_id' => $student->id,
                'assigned_to_user_id' => $superAdmin->id,
                'course_id' => $course1->id,
                'category' => 'TECHNICAL',
                'priority' => 'HIGH',
                'status' => 'OPEN',
                'subject' => 'استفسار بخصوص تثبيت ملحقات النمذجة (Revit Add-ins)',
            ]
        );

        $ticket1->messages()->firstOrCreate(
            ['ticket_id' => $ticket1->id, 'user_id' => $student->id],
            [
                'is_staff_reply' => false,
                'is_internal_note' => false,
                'message' => 'السلام عليكم، أواجه مشكلة في تفعيل إضافة تصدير الـ IFC المتوافقة مع معيار ISO 19650 على إصدار Revit 2024. هل يتطلب ذلك تثبيت حزمة محددة؟',
            ]
        );

        $ticket2 = SupportTicket::firstOrCreate(
            ['ticket_number' => 'TCK-2026-ENG002'],
            [
                'user_id' => $student->id,
                'assigned_to_user_id' => $superAdmin->id,
                'course_id' => $course1->id,
                'category' => 'BILLING',
                'priority' => 'NORMAL',
                'status' => 'RESOLVED',
                'subject' => 'طلب استخراج فاتورة ضريبية رسمية للمؤسسة',
                'resolved_at' => now()->subHours(12),
            ]
        );

        $ticket2->messages()->firstOrCreate(
            ['ticket_id' => $ticket2->id, 'user_id' => $student->id],
            [
                'is_staff_reply' => false,
                'is_internal_note' => false,
                'message' => 'نود تزويدنا بالفاتورة الضريبية للطلب رقم ORD-2026-000101 متضمنة الرقم الضريبي للمنشأة.',
            ]
        );

        $ticket2->messages()->firstOrCreate(
            ['ticket_id' => $ticket2->id, 'user_id' => $superAdmin->id],
            [
                'is_staff_reply' => true,
                'is_internal_note' => false,
                'message' => 'أهلاً بك م. أحمد، تم إصدار الفاتورة الضريبية المعتمدة بنجاح وإرسالها إلى بريدك الإلكتروني.',
            ]
        );

        // =========================================================================
        // 9. Demo Media Records
        // =========================================================================
        $demoMedia = [
            [
                'file_name' => 'beforbim_blueprint_sample.png',
                'collection_name' => 'general',
                'file_path' => 'media/general/blueprint_sample.png',
                'disk' => 'public',
                'mime_type' => 'image/png',
                'file_size' => 245000,
                'uploaded_by' => $superAdmin->id,
            ],
            [
                'file_name' => 'revit_arch_cover.jpg',
                'collection_name' => 'thumbnails',
                'file_path' => 'media/thumbnails/revit_arch_cover.jpg',
                'disk' => 'public',
                'mime_type' => 'image/jpeg',
                'file_size' => 412000,
                'uploaded_by' => $instructor->id,
            ],
            [
                'file_name' => 'structural_reinforcement_diagram.pdf',
                'collection_name' => 'documents',
                'file_path' => 'media/documents/structural_reinforcement_diagram.pdf',
                'disk' => 'public',
                'mime_type' => 'application/pdf',
                'file_size' => 1845000,
                'uploaded_by' => $instructor->id,
            ],
            [
                'file_name' => 'navisworks_federated_coordination.nwc',
                'collection_name' => 'bim_models',
                'file_path' => 'media/bim_models/navisworks_federated_coordination.nwc',
                'disk' => 'public',
                'mime_type' => 'application/octet-stream',
                'file_size' => 12500000,
                'uploaded_by' => $instructor->id,
            ],
            [
                'file_name' => 'iso19650_bep_master_template.docx',
                'collection_name' => 'documents',
                'file_path' => 'media/documents/iso19650_bep_master_template.docx',
                'disk' => 'public',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'file_size' => 640000,
                'uploaded_by' => $superAdmin->id,
            ],
        ];

        foreach ($demoMedia as $media) {
            MediaFile::firstOrCreate(
                ['file_name' => $media['file_name']],
                $media
            );
        }
    }
}
