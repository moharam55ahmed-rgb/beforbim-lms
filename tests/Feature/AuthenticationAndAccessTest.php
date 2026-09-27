<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Models\Course;
use App\Modules\Curriculum\Models\CourseSection;
use App\Modules\DeviceSession\Models\DeviceSession;
use App\Modules\Lesson\Models\Lesson;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AuthenticationAndAccessTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Beforbim');
        $response->assertSee('دخول إلى المنصة');
    }

    public function test_users_can_authenticate_and_device_session_is_registered(): void
    {
        $user = User::where('email', 'student@beforbim.com')->first();

        $response = $this->post('/login', [
            'email' => 'student@beforbim.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('student.dashboard'));

        $this->assertDatabaseHas('device_sessions', [
            'user_id' => $user->id,
            'is_active' => true,
        ]);
    }

    public function test_subsequent_login_terminates_previous_device_session(): void
    {
        $user = User::where('email', 'student@beforbim.com')->first();

        // First login
        $this->post('/login', [
            'email' => 'student@beforbim.com',
            'password' => 'password123',
        ]);

        $firstSession = DeviceSession::where('user_id', $user->id)->where('is_active', true)->first();
        $this->assertNotNull($firstSession);

        // Second login from a different device/browser (new session without logging out Device 1)
        $this->app['auth']->forgetGuards();
        $this->flushSession();

        $this->post('/login', [
            'email' => 'student@beforbim.com',
            'password' => 'password123',
        ]);

        // First session must now be revoked
        $this->assertDatabaseHas('device_sessions', [
            'id' => $firstSession->id,
            'is_active' => false,
            'revocation_reason' => 'CONCURRENT_LOGIN_KICK',
        ]);
    }

    public function test_student_cannot_access_admin_dashboard(): void
    {
        $student = User::where('email', 'student@beforbim.com')->first();

        $response = $this->actingAs($student)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::where('email', 'admin@beforbim.com')->first();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('مركز التحكم الإداري');
    }

    public function test_suspended_user_cannot_login(): void
    {
        $user = User::where('email', 'student@beforbim.com')->first();
        $user->update(['status' => 'suspended']);

        $response = $this->post('/login', [
            'email' => 'student@beforbim.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');

        // Restore status
        $user->update(['status' => 'active']);
    }

    public function test_instructor_cannot_publish_own_course_without_admin(): void
    {
        $instructor = User::where('email', 'instructor@beforbim.com')->first();
        $category = Category::first();
        $course = Course::firstOrCreate(
            ['instructor_id' => $instructor->id],
            [
                'category_id' => $category?->id,
                'title_ar' => 'دورة أساسيات BIM',
                'title_en' => 'BIM Basics',
                'price' => 100,
                'currency' => 'SAR',
                'status' => 'DRAFT',
            ]
        );

        $this->assertTrue(Gate::forUser($instructor)->allows('update', $course));
        $this->assertFalse(Gate::forUser($instructor)->allows('publish', $course));
    }

    public function test_unenrolled_student_cannot_access_private_lesson(): void
    {
        $student = User::where('email', 'student@beforbim.com')->first();
        $instructor = User::where('email', 'instructor@beforbim.com')->first();
        $category = Category::first();
        $course = Course::firstOrCreate(
            ['instructor_id' => $instructor->id, 'title_ar' => 'دورة أساسيات BIM'],
            [
                'category_id' => $category?->id,
                'title_en' => 'BIM Basics',
                'price' => 100,
                'currency' => 'SAR',
                'status' => 'DRAFT',
            ]
        );

        $section = CourseSection::firstOrCreate(
            ['course_id' => $course->id, 'title_ar' => 'الوحدة التمهيدية'],
            ['order_index' => 1]
        );

        $privateLesson = Lesson::firstOrCreate(
            ['section_id' => $section->id, 'title_ar' => 'نمذجة الأعمدة الخرسانية'],
            ['is_preview_free' => false, 'is_preview' => false]
        );

        $this->assertFalse(Gate::forUser($student)->allows('view', $privateLesson));
    }
}
