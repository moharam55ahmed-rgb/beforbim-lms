<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Course\Models\Course;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_get_api_courses_returns_published_courses_with_required_fields(): void
    {
        $response = $this->getJson('/api/courses');

        $response->assertStatus(200);

        // Verify JSON contains items
        $data = $response->json();
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        // Verify each course includes all required fields
        foreach ($data as $course) {
            $this->assertArrayHasKey('id', $course);
            $this->assertArrayHasKey('title', $course);
            $this->assertArrayHasKey('slug', $course);
            $this->assertArrayHasKey('image', $course);
            $this->assertArrayHasKey('price', $course);
            $this->assertArrayHasKey('instructor', $course);
            $this->assertArrayHasKey('category', $course);
            $this->assertArrayHasKey('rating', $course);

            $this->assertIsInt($course['id']);
            $this->assertIsString($course['title']);
            $this->assertIsString($course['slug']);
            $this->assertIsString($course['image']);
            $this->assertIsNumeric($course['price']);
            $this->assertNotNull($course['instructor']);
            $this->assertNotNull($course['category']);
            $this->assertIsNumeric($course['rating']);
        }
    }

    public function test_get_api_courses_show_returns_course_details(): void
    {
        $course = Course::published()->first();
        $courseId = $course ? $course->id : 159;

        $response = $this->getJson("/api/courses/{$courseId}");

        $response->assertStatus(200)
            ->assertJsonPath('id', $courseId);
    }

    public function test_get_api_instructors_returns_instructors_list(): void
    {
        $response = $this->getJson('/api/instructors');

        $response->assertStatus(200);

        $data = $response->json();
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        $first = $data[0];
        $this->assertArrayHasKey('id', $first);
        $this->assertArrayHasKey('name', $first);
        $this->assertArrayHasKey('courses_count', $first);
        $this->assertArrayHasKey('courses', $first);
    }

    public function test_get_api_instructors_show_returns_instructor_details(): void
    {
        $instructor = User::whereHas('roles', fn ($q) => $q->where('name', 'instructor'))
            ->where('status', 'active')
            ->first();
        $instructorId = $instructor ? $instructor->id : 279;

        $response = $this->getJson("/api/instructors/{$instructorId}");

        $response->assertStatus(200)
            ->assertJsonPath('id', $instructorId);
    }

    public function test_existing_web_routes_remain_functional(): void
    {
        $coursesWebResponse = $this->get('/courses');
        $coursesWebResponse->assertStatus(200);

        $instructorsWebResponse = $this->get('/instructors');
        $instructorsWebResponse->assertStatus(200);
    }
}
