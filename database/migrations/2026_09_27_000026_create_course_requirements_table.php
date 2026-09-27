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
        Schema::create('course_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('requirement_type', 40)->default('knowledge'); // knowledge, software, skill, language
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->unsignedInteger('order')->default(1);
            $table->timestamps();

            $table->index(['course_id', 'order']);
            $table->index(['course_id', 'requirement_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_requirements');
    }
};
