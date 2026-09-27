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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('title_ar', 255);
            $table->string('title_en', 255);
            $table->string('slug', 255)->unique();
            $table->string('short_description_ar', 500);
            $table->longText('description_ar');
            $table->string('level', 30)->default('INTERMEDIATE'); // BEGINNER, INTERMEDIATE, ADVANCED, EXPERT
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('currency', 3)->default('SAR');
            $table->string('thumbnail_url', 255)->nullable();
            $table->string('promo_video_url', 255)->nullable();
            $table->json('software_requirements')->nullable(); // e.g. ["Revit 2024", "Navisworks"]
            $table->json('prerequisites')->nullable();
            $table->json('learning_outcomes')->nullable();
            $table->string('status', 30)->default('DRAFT')->index(); // DRAFT, SUBMITTED, APPROVED, REJECTED, ARCHIVED
            $table->text('rejection_feedback')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'category_id', 'level', 'price'], 'idx_course_search');
            $table->fullText(['title_ar', 'title_en', 'short_description_ar'], 'ft_course_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
