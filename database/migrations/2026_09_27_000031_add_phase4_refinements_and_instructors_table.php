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
        // 1. Multiple Course Instructors Support
        if (! Schema::hasTable('course_instructors')) {
            Schema::create('course_instructors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('role', 30)->default('assistant_instructor'); // main_instructor, assistant_instructor
                $table->timestamps();

                $table->unique(['course_id', 'user_id']);
                $table->index(['course_id', 'role']);
            });
        }

        // 2. Advanced Course Preview & SEO Fields on Courses
        Schema::table('courses', function (Blueprint $table) {
            if (! Schema::hasColumn('courses', 'preview_enabled')) {
                $table->boolean('preview_enabled')->default(true)->after('status');
            }
            if (! Schema::hasColumn('courses', 'preview_description')) {
                $table->text('preview_description')->nullable()->after('preview_enabled');
            }
            if (! Schema::hasColumn('courses', 'meta_title_ar')) {
                $table->string('meta_title_ar', 255)->nullable()->after('preview_description');
            }
            if (! Schema::hasColumn('courses', 'meta_title_en')) {
                $table->string('meta_title_en', 255)->nullable()->after('meta_title_ar');
            }
            if (! Schema::hasColumn('courses', 'meta_description_ar')) {
                $table->text('meta_description_ar')->nullable()->after('meta_title_en');
            }
            if (! Schema::hasColumn('courses', 'meta_description_en')) {
                $table->text('meta_description_en')->nullable()->after('meta_description_ar');
            }
            if (! Schema::hasColumn('courses', 'meta_keywords')) {
                $table->json('meta_keywords')->nullable()->after('meta_description_en');
            }
        });

        // 3. Lesson Preview Alignment
        Schema::table('lessons', function (Blueprint $table) {
            if (! Schema::hasColumn('lessons', 'is_preview')) {
                $table->boolean('is_preview')->default(false)->after('is_preview_free');
            }
        });

        // 4. Enhanced Lesson Content Architecture
        if (! Schema::hasColumn('lesson_contents', 'type')) {
            Schema::table('lesson_contents', function (Blueprint $table) {
                $table->dropForeign(['lesson_id']);
                $table->dropUnique(['lesson_id']);
                $table->foreign('lesson_id')->references('id')->on('lessons')->cascadeOnDelete();

                $table->string('title', 255)->nullable()->after('lesson_id');
                $table->string('type', 30)->default('video')->after('title'); // video, text, document, external_video, quiz, assignment
                $table->longText('content_data')->nullable()->after('type');
                $table->unsignedInteger('ordering')->default(1)->after('content_data');
                $table->unsignedInteger('duration')->default(0)->after('ordering');
                $table->string('visibility_status', 20)->default('visible')->after('duration'); // visible, hidden, draft
                $table->boolean('preview_availability')->default(false)->after('visibility_status');
                $table->string('linked_entity_type', 120)->nullable()->after('preview_availability');
                $table->unsignedBigInteger('linked_entity_id')->nullable()->after('linked_entity_type');

                $table->index(['lesson_id', 'ordering']);
                $table->index(['linked_entity_type', 'linked_entity_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('lesson_contents', 'type')) {
            Schema::table('lesson_contents', function (Blueprint $table) {
                $table->dropIndex(['linked_entity_type', 'linked_entity_id']);
                $table->dropIndex(['lesson_id', 'ordering']);
                $table->dropColumn([
                    'title',
                    'type',
                    'content_data',
                    'ordering',
                    'duration',
                    'visibility_status',
                    'preview_availability',
                    'linked_entity_type',
                    'linked_entity_id',
                ]);
            });
        }

        if (Schema::hasColumn('lessons', 'is_preview')) {
            Schema::table('lessons', function (Blueprint $table) {
                $table->dropColumn('is_preview');
            });
        }

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'preview_enabled',
                'preview_description',
                'meta_title_ar',
                'meta_title_en',
                'meta_description_ar',
                'meta_description_en',
                'meta_keywords',
            ]);
        });

        Schema::dropIfExists('course_instructors');
    }
};
