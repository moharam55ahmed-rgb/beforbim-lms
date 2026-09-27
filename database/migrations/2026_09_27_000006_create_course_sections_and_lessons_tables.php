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
        Schema::create('course_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title_ar', 255);
            $table->string('title_en', 255)->nullable();
            $table->text('description_ar')->nullable();
            $table->unsignedInteger('order_index')->default(1);
            $table->timestamps();

            $table->index(['course_id', 'order_index']);
        });

        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('course_sections')->cascadeOnDelete();
            $table->string('title_ar', 255);
            $table->string('title_en', 255)->nullable();
            $table->string('lesson_type', 30)->default('VIDEO'); // VIDEO, DOCUMENT, QUIZ, ASSIGNMENT, LIVE_SESSION
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->unsignedInteger('order_index')->default(1);
            $table->boolean('is_preview_free')->default(false)->index();
            $table->boolean('is_mandatory')->default(true);
            $table->timestamps();

            $table->index(['section_id', 'order_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('course_sections');
    }
};
