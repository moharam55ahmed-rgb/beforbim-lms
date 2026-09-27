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
        Schema::create('instructor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('specialization', 150)->nullable(); // e.g. Senior BIM Manager, Structural Detailing
            $table->unsignedTinyInteger('experience_years')->default(0);
            $table->string('education', 255)->nullable(); // e.g. B.Sc. Civil Engineering
            $table->json('certifications')->nullable(); // e.g. ["Autodesk Certified Professional", "BIM Manager ISO 19650"]
            $table->string('linkedin_url', 255)->nullable();
            $table->string('website_url', 255)->nullable();
            $table->string('profile_image', 255)->nullable();
            $table->string('profile_status', 30)->default('pending')->index(); // pending, approved, rejected
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->index(['profile_status', 'specialization']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_profiles');
    }
};
