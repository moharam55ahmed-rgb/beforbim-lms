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
        // 1. Certificate Templates
        if (! Schema::hasTable('certificate_templates')) {
            Schema::create('certificate_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->json('design_config')->nullable(); // Fonts, borders, background, signatures, seal_color
                $table->string('status', 20)->default('active')->index(); // active, inactive
                $table->timestamps();
            });
        }

        // Add template_id to certificates table if not present
        if (Schema::hasTable('certificates') && ! Schema::hasColumn('certificates', 'template_id')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->foreignId('template_id')->nullable()->after('course_id')->constrained('certificate_templates')->nullOnDelete();
            });
        }

        // 2. Assignment Grading Rubrics
        if (! Schema::hasTable('assignment_rubrics')) {
            Schema::create('assignment_rubrics', function (Blueprint $table) {
                $table->id();
                $table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
                $table->string('criteria', 255); // e.g. "BIM LOD 350 Modeling Accuracy"
                $table->decimal('weight', 5, 2)->default(1.00);
                $table->decimal('max_score', 6, 2)->default(25.00);
                $table->text('description')->nullable();
                $table->timestamps();

                $table->index(['assignment_id']);
            });
        }

        // 3. Question Bank Enhancement
        if (Schema::hasTable('assessment_questions')) {
            Schema::table('assessment_questions', function (Blueprint $table) {
                if (! Schema::hasColumn('assessment_questions', 'category')) {
                    $table->string('category', 100)->nullable()->index()->after('question_type');
                }
                if (! Schema::hasColumn('assessment_questions', 'difficulty_level')) {
                    $table->string('difficulty_level', 20)->default('medium')->index()->after('category'); // easy, medium, hard
                }
                if (! Schema::hasColumn('assessment_questions', 'tags')) {
                    $table->json('tags')->nullable()->after('difficulty_level');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('assessment_questions')) {
            Schema::table('assessment_questions', function (Blueprint $table) {
                $table->dropColumn(['category', 'difficulty_level', 'tags']);
            });
        }

        Schema::dropIfExists('assignment_rubrics');

        if (Schema::hasTable('certificates') && Schema::hasColumn('certificates', 'template_id')) {
            Schema::table('certificates', function (Blueprint $table) {
                $table->dropForeign(['template_id']);
                $table->dropColumn('template_id');
            });
        }

        Schema::dropIfExists('certificate_templates');
    }
};
