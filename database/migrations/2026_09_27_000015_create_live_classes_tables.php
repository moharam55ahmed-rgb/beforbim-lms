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
        Schema::create('live_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->string('title_ar', 255);
            $table->string('title_en', 255)->nullable();
            $table->text('description_ar')->nullable();
            $table->string('provider', 30)->default('ZOOM'); // ZOOM, GOOGLE_MEET
            $table->string('meeting_id', 100);
            $table->string('meeting_password', 100)->nullable();
            $table->text('join_url_student');
            $table->text('host_url_instructor')->nullable();
            $table->timestamp('scheduled_start_time');
            $table->unsignedSmallInteger('duration_minutes')->default(60);
            $table->string('status', 30)->default('SCHEDULED'); // SCHEDULED, LIVE, COMPLETED, CANCELLED
            $table->string('recording_url', 500)->nullable();
            $table->timestamps();

            $table->index(['course_id', 'scheduled_start_time']);
        });

        Schema::create('live_class_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_class_id')->constrained('live_classes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('joined_at');
            $table->timestamp('left_at')->nullable();
            $table->unsignedInteger('attended_minutes')->default(0);
            $table->timestamps();

            $table->unique(['live_class_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_class_attendances');
        Schema::dropIfExists('live_classes');
    }
};
