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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // Public verification code
            $table->string('certificate_number', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('course_id')->constrained('courses')->restrictOnDelete();
            $table->foreignId('enrollment_id')->constrained('enrollments')->restrictOnDelete();
            $table->string('student_name_snapshot', 191);
            $table->string('course_title_snapshot_ar', 255);
            $table->string('course_title_snapshot_en', 255);
            $table->string('instructor_name_snapshot', 191);
            $table->decimal('grade_percentage', 5, 2)->nullable();
            $table->timestamp('issued_at');
            $table->string('pdf_storage_path', 500)->nullable();
            $table->string('qr_verification_url', 500);
            $table->boolean('is_revoked')->default(false);
            $table->text('revocation_reason')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
            $table->index(['uuid', 'is_revoked']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
