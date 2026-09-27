<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained('lessons')->nullOnDelete();
            $table->string('title_ar', 255);
            $table->string('title_en', 255)->nullable();
            $table->longText('instructions_ar');
            $table->decimal('total_points', 6, 2)->default(100.00);
            $table->timestamp('due_date')->nullable();
            $table->json('allowed_file_types')->nullable(); // ["rvt", "dwg", "ifc", "pdf", "zip"]
            $table->unsignedInteger('max_file_size_mb')->default(150); // Large engineering files support
            $table->timestamps();

            $table->index(['course_id', 'due_date']);
        });

        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('student_notes')->nullable();
            $table->string('file_path', 500);
            $table->string('file_name', 255);
            $table->unsignedBigInteger('file_size_bytes');
            $table->string('status', 30)->default('SUBMITTED'); // SUBMITTED, UNDER_REVIEW, GRADED, REVISION_REQUESTED
            $table->decimal('grade', 6, 2)->nullable();
            $table->text('instructor_feedback')->nullable();
            $table->foreignId('graded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at');
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();

            $table->unique(['assignment_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('assignments');
    }
};
