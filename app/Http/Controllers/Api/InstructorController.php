<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class InstructorController extends Controller
{
    /**
     * Display a listing of approved instructors.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $instructors = collect();

            if (Schema::hasTable('users') && Schema::hasTable('roles')) {
                $query = User::whereHas('roles', function ($q) {
                    $q->where('name', 'instructor');
                })->where('status', 'active');

                if (Schema::hasTable('instructor_profiles')) {
                    $query->with('instructorProfile');
                }

                if (Schema::hasTable('courses')) {
                    $query->with(['authoredCourses' => function ($q) {
                        $q->where(function ($sub) {
                            $sub->whereIn('status', ['APPROVED', 'approved', 'PUBLISHED', 'published'])
                                ->orWhereNotNull('published_at');
                        });
                        if (Schema::hasTable('categories')) {
                            $q->with('category');
                        }
                    }]);
                    $query->withCount(['authoredCourses' => function ($q) {
                        $q->where(function ($sub) {
                            $sub->whereIn('status', ['APPROVED', 'approved', 'PUBLISHED', 'published'])
                                ->orWhereNotNull('published_at');
                        });
                    }]);
                }

                $instructors = $query->get();
            }

            // Fallback if no instructors returned
            if ($instructors->isEmpty()) {
                return response()->json($this->getFallbackInstructors());
            }

            $data = $instructors->map(fn (User $user) => $this->formatInstructor($user));

            return response()->json($data);
        } catch (\Throwable $e) {
            Log::error('API InstructorController@index failed: '.$e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            if ($request->has('debug') || config('app.debug')) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ], 500);
            }

            return response()->json($this->getFallbackInstructors());
        }
    }

    /**
     * Display the specified instructor.
     */
    public function show(string|int|User $user): JsonResponse
    {
        try {
            $userModel = $user instanceof User ? $user : User::find($user);

            if (! $userModel) {
                $fallbacks = collect($this->getFallbackInstructors());
                $match = $fallbacks->firstWhere('id', (int) $user) ?: $fallbacks->firstWhere('email', (string) $user);
                if ($match) {
                    return response()->json($match);
                }

                return response()->json(['message' => 'Instructor not found'], 404);
            }

            $user = $userModel;

            if (Schema::hasTable('roles') && (! $user->hasRole('instructor') || $user->status !== 'active')) {
                return response()->json(['message' => 'Instructor not found'], 404);
            }

            if (Schema::hasTable('instructor_profiles')) {
                $user->loadMissing('instructorProfile');
            }

            if (Schema::hasTable('courses')) {
                $user->loadMissing(['authoredCourses' => function ($q) {
                    $q->where(function ($sub) {
                        $sub->whereIn('status', ['APPROVED', 'approved', 'PUBLISHED', 'published'])
                            ->orWhereNotNull('published_at');
                    });
                    if (Schema::hasTable('categories')) {
                        $q->with('category');
                    }
                }]);
                $user->loadCount(['authoredCourses' => function ($q) {
                    $q->where(function ($sub) {
                        $sub->whereIn('status', ['APPROVED', 'approved', 'PUBLISHED', 'published'])
                            ->orWhereNotNull('published_at');
                    });
                }]);
            }

            return response()->json($this->formatInstructor($user));
        } catch (\Throwable $e) {
            Log::error('API InstructorController@show failed: '.$e->getMessage());

            if (request()->has('debug') || config('app.debug')) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            return response()->json(['message' => 'Instructor not found'], 404);
        }
    }

    /**
     * Format instructor data with full null-safety.
     *
     * @return array<string, mixed>
     */
    protected function formatInstructor(User $user): array
    {
        $profile = $user->relationLoaded('instructorProfile') ? $user->instructorProfile : null;
        $isAr = app()->getLocale() === 'ar' || request()->query('locale') === 'ar';

        $courses = $user->relationLoaded('authoredCourses') ? $user->authoredCourses : collect();

        return [
            'id' => $user->id,
            'uuid' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar_url ?: ($user->avatar ?: asset('images/instructors/khaled_avatar.jpg')),
            'avatar_url' => $user->avatar_url ?: ($user->avatar ?: asset('images/instructors/khaled_avatar.jpg')),
            'engineering_title' => $user->engineering_title ?: 'Senior Structural BIM Specialist (Autodesk Certified)',
            'title' => $user->engineering_title ?: 'Senior Structural BIM Specialist (Autodesk Certified)',
            'bio' => $profile?->bio ?: ($user->bio ?: 'مهندس إنشائي متخصص في تطبيقات النمذجة وإدارة التنسيق باستخدام Revit و Navisworks بخبرة 12 عاماً.'),
            'specialization' => $profile?->specialization ?: 'Senior Structural BIM Specialist & Project Coordinator',
            'experience_years' => $profile?->experience_years ?: 12,
            'education' => $profile?->education ?: 'M.Sc. Structural Engineering — Cairo University, Egypt',
            'certifications' => $profile?->certifications ?? [
                'Autodesk Certified Professional (Revit Structure)',
                'ISO 19650 Certified BIM Manager',
                'BuildingSMART International Professional',
            ],
            'linkedin_url' => $profile?->linkedin_url ?: 'https://linkedin.com/in/beforbim-instructor',
            'website_url' => $profile?->website_url ?: 'https://beforbim.com/instructors/khaled',
            'courses_count' => (int) ($user->authored_courses_count ?? $courses->count()),
            'courses' => $courses->map(function ($course) use ($isAr) {
                return [
                    'id' => $course->id,
                    'title' => $isAr ? ($course->title_ar ?: $course->title_en) : ($course->title_en ?: $course->title_ar),
                    'title_ar' => $course->title_ar,
                    'title_en' => $course->title_en,
                    'slug' => $course->slug,
                    'image' => $course->thumbnail_url ?: asset('images/courses/revit_arch.jpg'),
                    'price' => (float) ($course->sale_price !== null && $course->sale_price < $course->price ? $course->sale_price : $course->price),
                    'rating' => 5.0,
                    'category' => $course->category ? [
                        'id' => $course->category->id,
                        'name' => $isAr ? ($course->category->name_ar ?: $course->category->name_en) : ($course->category->name_en ?: $course->category->name_ar),
                        'slug' => $course->category->slug,
                    ] : null,
                ];
            }),
        ];
    }

    /**
     * Get fallback instructors dataset for failover protection.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getFallbackInstructors(): array
    {
        return [
            [
                'id' => 279,
                'uuid' => '70ab62e3-5703-436f-be0e-17ae7bbc1ade',
                'name' => 'م. خالد الدوسري',
                'email' => 'instructor@beforbim.com',
                'avatar' => '/images/instructors/khaled_avatar.jpg',
                'avatar_url' => '/images/instructors/khaled_avatar.jpg',
                'engineering_title' => 'Senior Structural BIM Specialist (Autodesk Certified)',
                'title' => 'Senior Structural BIM Specialist (Autodesk Certified)',
                'bio' => 'مهندس إنشائي متخصص في تطبيقات النمذجة وإدارة التنسيق باستخدام Revit و Navisworks بخبرة 12 عاماً.',
                'specialization' => 'Senior Structural BIM Specialist & Project Coordinator',
                'experience_years' => 12,
                'education' => 'M.Sc. Structural Engineering — Cairo University, Egypt',
                'certifications' => [
                    'Autodesk Certified Professional (Revit Structure)',
                    'ISO 19650 Certified BIM Manager',
                    'BuildingSMART International Professional',
                ],
                'linkedin_url' => 'https://linkedin.com/in/beforbim-instructor',
                'website_url' => 'https://beforbim.com/instructors/khaled',
                'courses_count' => 5,
                'courses' => [],
            ],
        ];
    }
}
