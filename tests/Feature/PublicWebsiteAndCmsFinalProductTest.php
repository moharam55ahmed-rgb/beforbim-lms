<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Course\Models\Course;
use App\Modules\Media\Models\MediaFile;
use App\Modules\Setting\Models\CmsSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicWebsiteAndCmsFinalProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_homepage_renders_successfully(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Beforbim');
        $response->assertSee('المسارات والتخصصات');
    }

    public function test_about_us_page_renders_successfully(): void
    {
        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertSee('رسالتنا الأكاديمية');
        $response->assertSee('رؤيتنا المستقبلية');
    }

    public function test_contact_page_renders_and_handles_inquiry_submission(): void
    {
        $response = $this->get(route('contact'));
        $response->assertOk();
        $response->assertSee('أرسل لنا استفسارك');

        $student = User::where('email', 'student@beforbim.com')->first();

        $submitResponse = $this->actingAs($student)->post(route('contact.submit'), [
            'name' => 'مهندس زائر',
            'email' => 'visitor@example.com',
            'phone' => '+966555123456',
            'subject' => 'استفسار عن دبلومة الـ BIM',
            'message' => 'أرغب في الاستفسار عن مواعيد المحاضرات التفاعلية والشهادات.',
        ]);

        $submitResponse->assertRedirect();
        $submitResponse->assertSessionHas('success');
    }

    public function test_blog_index_and_details_render_successfully(): void
    {
        $indexResponse = $this->get(route('blog.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('مقالات ودراسات حالة في عالم الـ BIM');

        $showResponse = $this->get(route('blog.show', 'iso-19650-bim-execution-plan-guide'));
        $showResponse->assertOk();
        $showResponse->assertSee('الدليل الشامل لإعداد خطة تنفيذ الـ BIM');
    }

    public function test_instructors_index_and_profile_render_successfully(): void
    {
        $indexResponse = $this->get(route('instructors.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('خبراء واستشاريو هندسة الـ BIM');

        $instructor = User::where('email', 'instructor@beforbim.com')->first();
        $showResponse = $this->get(route('instructors.show', $instructor->id));
        $showResponse->assertOk();
        $showResponse->assertSee($instructor->name);
        $showResponse->assertSee('مدرب معتمد بالأكاديمية');
    }

    public function test_course_details_renders_with_curriculum_and_reviews(): void
    {
        $course = Course::where('status', 'APPROVED')->first();

        $response = $this->get(route('courses.show', $course->id));

        $response->assertOk();
        $response->assertSee($course->title_ar);
        $response->assertSee('إضافة إلى السلة والتسجيل');
        $response->assertSee('منهج ومحاور الدورة');
    }

    public function test_admin_can_manage_cms_settings_including_seo_and_hero(): void
    {
        $admin = User::where('email', 'admin@beforbim.com')->first();

        $indexResponse = $this->actingAs($admin)->get(route('admin.settings.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('واجهة الموقع (Hero & About)', false);
        $indexResponse->assertSee('تهيئة محركات البحث (SEO)', false);

        $updateResponse = $this->actingAs($admin)->put(route('admin.settings.update'), [
            'site_name_ar' => 'Beforbim Pro',
            'hero_title_ar' => 'الأكاديمية الهندسية الاحترافية للـ BIM',
            'seo_meta_title' => 'Beforbim - منصة التميز الهندسي',
        ]);

        $updateResponse->assertRedirect();
        $updateResponse->assertSessionHas('status');

        $this->assertEquals('Beforbim Pro', CmsSetting::get('site_name_ar'));
    }

    public function test_admin_can_access_media_library_and_upload_file(): void
    {
        Storage::fake('public');

        $admin = User::where('email', 'admin@beforbim.com')->first();

        $indexResponse = $this->actingAs($admin)->get(route('admin.media.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('مكتبة الوسائط والملفات الهندسية');

        $file = UploadedFile::fake()->image('test_blueprint.png', 800, 600);

        $uploadResponse = $this->actingAs($admin)->post(route('admin.media.store'), [
            'file' => $file,
            'collection_name' => 'blueprints',
            'disk' => 'public',
        ]);

        $uploadResponse->assertRedirect();
        $uploadResponse->assertSessionHas('status');

        $this->assertDatabaseHas('media_files', [
            'collection_name' => 'blueprints',
            'file_name' => 'test_blueprint.png',
        ]);

        $media = MediaFile::where('file_name', 'test_blueprint.png')->first();
        Storage::disk('public')->assertExists($media->file_path);

        // Test delete
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.media.destroy', $media->id));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('media_files', ['id' => $media->id]);
    }
}
