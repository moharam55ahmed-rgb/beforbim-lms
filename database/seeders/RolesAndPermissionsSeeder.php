<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\AccessControl\Models\Permission;
use App\Modules\AccessControl\Models\Role;
use App\Modules\Category\Models\Category;
use App\Modules\Setting\Models\CmsSetting;
use App\Modules\User\Models\InstructorProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Define Standard Roles
        $roles = [
            'super_admin' => [
                'display_name_ar' => 'مدير عام المنصة',
                'display_name_en' => 'Super Administrator',
                'description' => 'Full uncontrolled platform governance and technical authority',
            ],
            'admin' => [
                'display_name_ar' => 'مدير أكاديمي وعمليات',
                'display_name_en' => 'Operations & Academic Manager',
                'description' => 'Course audits, payment verification, student dispute resolution',
            ],
            'instructor' => [
                'display_name_ar' => 'مهندس محاضر معتمد',
                'display_name_en' => 'BIM Certified Instructor',
                'description' => 'Course authoring, curriculum management, assignment grading',
            ],
            'student' => [
                'display_name_ar' => 'مهندس متدرب',
                'display_name_en' => 'Engineering Student',
                'description' => 'Course enrollment, video learning, exams, certification',
            ],
        ];

        $roleModels = [];
        foreach ($roles as $name => $meta) {
            $roleModels[$name] = Role::firstOrCreate(
                ['name' => $name],
                [
                    'display_name_ar' => $meta['display_name_ar'],
                    'display_name_en' => $meta['display_name_en'],
                    'description' => $meta['description'],
                ]
            );
        }

        // 2. Define Granular Permissions
        $permissions = [
            // User & Access
            ['name' => 'users.view', 'group_name' => 'User', 'description_ar' => 'عرض بيانات المستخدمين'],
            ['name' => 'users.manage', 'group_name' => 'User', 'description_ar' => 'تعديل وتجميد وحظر المستخدمين'],
            ['name' => 'roles.manage', 'group_name' => 'AccessControl', 'description_ar' => 'إدارة الأدوار والصلاحيات'],

            // Course Authoring & Approval
            ['name' => 'courses.create', 'group_name' => 'Course', 'description_ar' => 'إنشاء دورات تدريبية'],
            ['name' => 'courses.edit_own', 'group_name' => 'Course', 'description_ar' => 'تعديل الدورات الخاصة بالمدرب'],
            ['name' => 'courses.audit_publish', 'group_name' => 'Course', 'description_ar' => 'مراجعة ونشر الدورات في المنصة'],
            ['name' => 'categories.manage', 'group_name' => 'Category', 'description_ar' => 'إدارة الأقسام الهندسية'],

            // Course Interaction
            ['name' => 'reviews.moderate', 'group_name' => 'CourseReview', 'description_ar' => 'مراجعة واعتماد تقييمات الطلاب'],
            ['name' => 'announcements.create', 'group_name' => 'CourseAnnouncement', 'description_ar' => 'نشر إعلانات وتحديثات الدورة'],
            ['name' => 'discussions.moderate', 'group_name' => 'CourseDiscussion', 'description_ar' => 'إدارة حلقات النقاش وتثبيت الأسئلة'],

            // Financial & Enrollment
            ['name' => 'payments.view', 'group_name' => 'Payment', 'description_ar' => 'عرض المدفوعات والتقارير المالية'],
            ['name' => 'payments.verify_manual', 'group_name' => 'Payment', 'description_ar' => 'اعتماد وتدقيق الحوالات البنكية'],
            ['name' => 'enrollments.manage', 'group_name' => 'Enrollment', 'description_ar' => 'إدارة وتفعيل اشتراكات الطلاب'],

            // Academic & Assessment
            ['name' => 'assignments.grade', 'group_name' => 'Assignment', 'description_ar' => 'تصحيح المشاريع وتكليفات الـ BIM'],
            ['name' => 'assessments.audit', 'group_name' => 'Assessment', 'description_ar' => 'تدقيق مخالفات الاختبارات والنزاهة'],
            ['name' => 'certificates.issue', 'group_name' => 'Certificate', 'description_ar' => 'إصدار واعتماد الشهادات المهنية'],

            // Support & Audit
            ['name' => 'instructor_profiles.approve', 'group_name' => 'User', 'description_ar' => 'اعتماد وتدقيق ملفات المدربين'],
            ['name' => 'support.reply', 'group_name' => 'SupportTicket', 'description_ar' => 'الرد على تذاكر الدعم الفني'],
            ['name' => 'audit.view', 'group_name' => 'AuditLog', 'description_ar' => 'عرض سجل التدقيق الإداري والأمني'],
            ['name' => 'settings.manage', 'group_name' => 'Setting', 'description_ar' => 'تعديل إعدادات المنصة وبوابات الدفع'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], $perm);
        }

        // 3. Assign Permissions to Roles
        // Admin gets operations, audit, payment verification, course approval, reviews, discussions, support
        $adminPermissions = Permission::whereIn('name', [
            'users.view',
            'users.manage',
            'courses.audit_publish',
            'categories.manage',
            'reviews.moderate',
            'discussions.moderate',
            'payments.view',
            'payments.verify_manual',
            'enrollments.manage',
            'assessments.audit',
            'certificates.issue',
            'support.reply',
            'audit.view',
        ])->get();
        $roleModels['admin']->permissions()->sync($adminPermissions->pluck('id'));

        // Instructor gets course authoring, own course edit, announcements, discussions, grading, support
        $instructorPermissions = Permission::whereIn('name', [
            'courses.create',
            'courses.edit_own',
            'announcements.create',
            'discussions.moderate',
            'assignments.grade',
            'support.reply',
        ])->get();
        $roleModels['instructor']->permissions()->sync($instructorPermissions->pluck('id'));

        // 4. Seed Baseline Default Users
        $defaultPassword = Hash::make('password123');

        $users = [
            [
                'name' => 'مدير النظام التنفيذي',
                'email' => 'superadmin@beforbim.com',
                'phone' => '+966500000001',
                'phone_country_code' => '+966',
                'phone_number' => '500000001',
                'engineering_title' => 'Chief Technology Officer / BIM Director',
                'status' => 'active',
                'role' => 'super_admin',
            ],
            [
                'name' => 'مسؤول العمليات والاعتماد',
                'email' => 'admin@beforbim.com',
                'phone' => '+966500000002',
                'phone_country_code' => '+966',
                'phone_number' => '500000002',
                'engineering_title' => 'Operations Manager',
                'status' => 'active',
                'role' => 'admin',
            ],
            [
                'name' => 'م. خالد الدوسري',
                'email' => 'instructor@beforbim.com',
                'phone' => '+966500000003',
                'phone_country_code' => '+966',
                'phone_number' => '500000003',
                'engineering_title' => 'Senior Structural BIM Specialist (Autodesk Certified)',
                'bio' => 'مهندس إنشائي متخصص في تطبيقات النمذجة وإدارة التنسيق باستخدام Revit و Navisworks بخبرة 12 عاماً.',
                'status' => 'active',
                'role' => 'instructor',
            ],
            [
                'name' => 'م. أحمد الشمري',
                'email' => 'student@beforbim.com',
                'phone' => '+966500000004',
                'phone_country_code' => '+966',
                'phone_number' => '500000004',
                'engineering_title' => 'Civil Site Engineer',
                'bio' => 'مهندس مدني مهتم بتطوير المهارات الهندسية في نمذجة المباني وإدارة المشروعات.',
                'status' => 'active',
                'role' => 'student',
            ],
        ];

        foreach ($users as $userData) {
            $roleSlug = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => $defaultPassword,
                    'uuid' => (string) Str::uuid(),
                    'email_verified_at' => now(),
                    'phone_verified_at' => now(),
                ])
            );

            // Update status if it was previously uppercase
            $user->update(['status' => 'active']);
            $user->assignRole($roleSlug);

            if ($roleSlug === 'instructor') {
                InstructorProfile::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'bio' => 'مهندس استشاري وخبير معتمد في نمذجة معلومات البناء وتنسيق المشروعات الهندسية الكبرى.',
                        'specialization' => 'Senior Structural BIM Specialist & Coordinator',
                        'experience_years' => 12,
                        'education' => 'ماجستير الهندسة الإنشائية — جامعة القاهرة',
                        'certifications' => ['Autodesk Certified Professional (Revit Structure)', 'BIM Manager (ISO 19650)'],
                        'linkedin_url' => 'https://linkedin.com/in/beforbim-instructor',
                        'website_url' => 'https://beforbim.com/instructors/khaled',
                        'profile_status' => 'approved',
                    ]
                );
            }
        }

        // 5. Seed Engineering BIM Categories
        $categories = [
            [
                'name_ar' => 'نمذجة العمارة (Revit Architecture)',
                'name_en' => 'Architectural BIM (Revit)',
                'slug' => 'architectural-bim',
                'description_ar' => 'دورات احتراف إعداد النماذج المعمارية، إخراج المخططات التنفيذية وتفاصيل الـ LOD 350.',
            ],
            [
                'name_ar' => 'نمذجة وتفاصيل الإنشاءات (Structural BIM)',
                'name_en' => 'Structural BIM & Detailing',
                'slug' => 'structural-bim',
                'description_ar' => 'نمذجة المنشآت الخرسانية والمعدنية، تفاصيل التسليح واستخراج جداول الكميات عبر Revit و Tekla.',
            ],
            [
                'name_ar' => 'التنسيق واكتشاف التعارضات (Navisworks Clash Detection)',
                'name_en' => 'Coordination & Clash Detection',
                'slug' => 'bim-coordination',
                'description_ar' => 'التنسيق بين التخصصات المعمارية والإنشائية والكهروميكانيكية MEP وإدارة التعارضات.',
            ],
            [
                'name_ar' => 'التصميم البرمجي والحسابي (Dynamo & Python BIM)',
                'name_en' => 'Computational BIM (Dynamo)',
                'slug' => 'computational-bim',
                'description_ar' => 'أتمتة المهام الهندسية المتكررة وبرمجة المعاملات البارامترية عبر خوارزميات Dynamo.',
            ],
            [
                'name_ar' => 'إدارة مشروعات الـ BIM والأبعاد 4D/5D',
                'name_en' => '4D/5D BIM Project Management',
                'slug' => 'bim-project-management',
                'description_ar' => 'ربط النماذج بالجداول الزمنية وإدارة التكلفة وحسابات الكميات للمشروعات الكبرى.',
            ],
        ];

        foreach ($categories as $index => $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, ['display_order' => $index + 1])
            );
        }

        // 6. Seed Baseline CMS Settings
        $settings = [
            ['key' => 'site_name', 'group' => 'branding', 'value' => 'Beforbim — بيفور بيم', 'type' => 'string', 'is_public' => true],
            ['key' => 'site_name_ar', 'group' => 'general', 'value' => 'Beforbim — بيفور بيم', 'type' => 'string', 'is_public' => true],
            ['key' => 'site_name_en', 'group' => 'general', 'value' => 'Beforbim — Engineering & BIM LMS', 'type' => 'string', 'is_public' => true],
            ['key' => 'site_tagline', 'group' => 'branding', 'value' => 'المنصة الهندسية الأولى لاحتراف نمذجة معلومات البناء BIM وإدارة المشروعات', 'type' => 'string', 'is_public' => true],
            ['key' => 'contact_email', 'group' => 'general', 'value' => 'support@beforbim.com', 'type' => 'string', 'is_public' => true],
            ['key' => 'contact_phone', 'group' => 'general', 'value' => '+966 50 000 0000', 'type' => 'string', 'is_public' => true],
            ['key' => 'contact_address', 'group' => 'general', 'value' => 'المملكة العربية السعودية — الرياض / جمهورية مصر العربية — القاهرة', 'type' => 'string', 'is_public' => true],
            ['key' => 'hero_badge', 'group' => 'branding', 'value' => 'الاعتماد الأكاديمي الدولي وفق مواصفة ISO 19650', 'type' => 'string', 'is_public' => true],
            ['key' => 'hero_title_ar', 'group' => 'branding', 'value' => 'المنصة الهندسية الأولى المعتمدة لمهندسي الـ BIM وإدارة المشروعات الرقمية', 'type' => 'string', 'is_public' => true],
            ['key' => 'hero_subtitle_ar', 'group' => 'branding', 'value' => 'اكتسب مهارات متقدمة في Revit, Navisworks, Civil 3D, Dynamo مع نخبة من الخبراء والاستشاريين المعتمدين.', 'type' => 'string', 'is_public' => true],
            ['key' => 'about_mission', 'group' => 'branding', 'value' => 'جسر الفجوة بين التعليم الهندسي الأكاديمي والواقع العملي في المشروعات الضخمة.', 'type' => 'string', 'is_public' => true],
            ['key' => 'about_vision', 'group' => 'branding', 'value' => 'أن نكون المرجع الهندسي الرقمي الأول في الشرق الأوسط وإفريقيا لاعتماد وتأهيل مديري ومنسقي BIM.', 'type' => 'string', 'is_public' => true],
            ['key' => 'seo_meta_title', 'group' => 'branding', 'value' => 'Beforbim — أكاديمية نمذجة معلومات البناء وهندسة التشييد الرقمي', 'type' => 'string', 'is_public' => true],
            ['key' => 'seo_meta_description', 'group' => 'branding', 'value' => 'أكاديمية Beforbim الرائدة في برامج دبلومات BIM المعتمدة، هندسة التشييد الرقمي، وتطبيقات Revit, Navisworks, Civil 3D, و Dynamo مع نخبة من الاستشاريين الدوليين.', 'type' => 'string', 'is_public' => true],
            ['key' => 'seo_meta_keywords', 'group' => 'branding', 'value' => 'BIM, Revit, Navisworks, Civil 3D, Dynamo, نمذجة معلومات البناء, هندسة مدنية, كورسات هندسية', 'type' => 'string', 'is_public' => true],
            ['key' => 'default_currency', 'group' => 'payment', 'value' => 'SAR', 'type' => 'string', 'is_public' => true],
            ['key' => 'enforce_single_device', 'group' => 'security', 'value' => 'true', 'type' => 'boolean', 'is_public' => false],
            ['key' => 'vat_percentage', 'group' => 'payment', 'value' => '15.00', 'type' => 'string', 'is_public' => true],
        ];

        foreach ($settings as $setting) {
            CmsSetting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
