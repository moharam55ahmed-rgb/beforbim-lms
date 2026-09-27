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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            
            // Polymorphic extendability for future Bundles, Learning Paths, Subscriptions
            $table->string('enrollable_type', 120)->default('App\\Modules\\Course\\Models\\Course');
            $table->unsignedBigInteger('enrollable_id');
            
            // Direct course relation for fast indexed filtering & FK integrity
            $table->foreignId('course_id')->nullable()->constrained('courses')->restrictOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            
            $table->string('source', 30)->default('DIRECT_PURCHASE'); // DIRECT_PURCHASE, BUNDLE, LEARNING_PATH, SUBSCRIPTION, ADMIN_GRANT
            $table->string('status', 30)->default('ACTIVE')->index(); // ACTIVE, COMPLETED, SUSPENDED, EXPIRED, CANCELLED
            $table->decimal('progress_percentage', 5, 2)->default(0.00);
            
            $table->timestamp('enrolled_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // Null = lifetime access
            $table->softDeletes();
            $table->timestamps();

            // Strict uniqueness: prevents duplicate active enrollments for the same user and entity
            $table->unique(['user_id', 'enrollable_type', 'enrollable_id'], 'uniq_user_enrollable');
            $table->index(['user_id', 'course_id', 'status'], 'idx_enrollment_gate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
