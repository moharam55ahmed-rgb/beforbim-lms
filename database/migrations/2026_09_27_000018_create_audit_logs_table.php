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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('actor_role', 50)->nullable();
            $table->string('module', 50)->index(); // Course, Payment, Enrollment, Security, User, Assessment, Support
            $table->string('action', 100)->index(); // e.g. PAYMENT_STATUS_CHANGED, ENROLLMENT_APPROVED, COURSE_PUBLISHED
            $table->string('target_type', 120)->nullable(); // Eloquent class name
            $table->unsignedBigInteger('target_id')->nullable()->index();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45);
            $table->string('user_agent', 500)->nullable();
            $table->text('reason')->nullable();
            $table->timestamp('created_at')->index();

            $table->index(['module', 'action', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
