<?php

use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\InstructorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Courses API
Route::get('/courses', [CourseController::class, 'index'])->name('api.courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('api.courses.show');

// Instructors API
Route::get('/instructors', [InstructorController::class, 'index'])->name('api.instructors.index');
Route::get('/instructors/{user}', [InstructorController::class, 'show'])->name('api.instructors.show');

// Database Inspection & Management Utilities (Protected)
Route::get('/diagnostics', function (Request $request) {
    $secret = config('app.key') ?: 'beforbim2026';
    if ($request->query('key') !== 'beforbim2026' && $request->query('key') !== $secret) {
        return response()->json(['message' => 'Unauthorized. Provide valid ?key= parameter.'], 403);
    }

    try {
        $tables = DB::select('SHOW TABLES');
        $tableList = array_map(fn ($t) => array_values((array) $t)[0], $tables);

        $stats = [
            'database' => DB::connection()->getDatabaseName(),
            'driver' => DB::connection()->getDriverName(),
            'tables_count' => count($tableList),
            'tables' => $tableList,
            'courses_table_exists' => Schema::hasTable('courses'),
            'courses_count' => Schema::hasTable('courses') ? DB::table('courses')->count() : 0,
            'users_table_exists' => Schema::hasTable('users'),
            'users_count' => Schema::hasTable('users') ? DB::table('users')->count() : 0,
            'categories_table_exists' => Schema::hasTable('categories'),
            'categories_count' => Schema::hasTable('categories') ? DB::table('categories')->count() : 0,
            'reviews_table_exists' => Schema::hasTable('course_reviews'),
            'reviews_count' => Schema::hasTable('course_reviews') ? DB::table('course_reviews')->count() : 0,
            'instructor_profiles_exists' => Schema::hasTable('instructor_profiles'),
        ];

        return response()->json($stats);
    } catch (Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
})->name('api.diagnostics');

Route::get('/migrate', function (Request $request) {
    $secret = config('app.key') ?: 'beforbim2026';
    if ($request->query('key') !== 'beforbim2026' && $request->query('key') !== $secret) {
        return response()->json(['message' => 'Unauthorized. Provide valid ?key= parameter.'], 403);
    }

    try {
        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();

        return response()->json([
            'status' => 'success',
            'output' => $output,
        ]);
    } catch (Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
})->name('api.migrate');
