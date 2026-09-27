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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained('lessons')->nullOnDelete();
            $table->string('title_ar', 255);
            $table->string('title_en', 255)->nullable();
            $table->text('description_ar')->nullable();
            $table->string('type', 30)->default('LESSON_QUIZ'); // LESSON_QUIZ, MIDTERM_EXAM, FINAL_CERTIFICATION_EXAM
            $table->unsignedSmallInteger('time_limit_minutes')->nullable(); // null = unlimited
            $table->decimal('passing_score_percentage', 5, 2)->default(70.00);
            $table->unsignedTinyInteger('max_attempts')->default(1); // 0 = unlimited
            $table->boolean('shuffle_questions')->default(true);
            $table->boolean('shuffle_options')->default(true);
            
            // Configurable per exam security parameters
            $table->boolean('is_proctored_mode')->default(false);
            $table->boolean('monitor_tab_switch')->default(false);
            $table->boolean('monitor_fullscreen_exit')->default(false);
            $table->unsignedTinyInteger('max_violations_allowed')->default(3);
            $table->boolean('requires_manual_audit')->default(false); // Flags for instructor/admin manual audit
            
            $table->timestamps();

            $table->index(['course_id', 'type']);
        });

        Schema::create('assessment_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->text('question_text_ar');
            $table->text('question_text_en')->nullable();
            $table->string('question_type', 30)->default('MULTIPLE_CHOICE'); // SINGLE_CHOICE, MULTIPLE_CHOICE, TRUE_FALSE, NUMERICAL, CODE_SNIPPET, FILE_UPLOAD
            $table->decimal('points', 6, 2)->default(1.00);
            $table->json('options')->nullable(); // [{"id": 1, "text_ar": "...", "is_correct": true}, ...]
            $table->text('explanation_ar')->nullable();
            $table->unsignedInteger('order_index')->default(1);
            $table->timestamps();

            $table->index(['assessment_id', 'order_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_questions');
        Schema::dropIfExists('assessments');
    }
};
