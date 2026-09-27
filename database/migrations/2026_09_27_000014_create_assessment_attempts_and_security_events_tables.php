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
        Schema::create('assessment_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('attempt_number')->default(1);
            $table->decimal('total_points_possible', 6, 2)->default(0.00);
            $table->decimal('total_points_earned', 6, 2)->default(0.00);
            $table->decimal('score_percentage', 5, 2)->default(0.00);
            $table->boolean('passed')->default(false);
            $table->string('status', 30)->default('IN_PROGRESS'); // IN_PROGRESS, SUBMITTED, UNDER_MANUAL_REVIEW, GRADED, DISQUALIFIED
            $table->unsignedInteger('anti_cheat_violations_count')->default(0);
            $table->text('audit_notes')->nullable();
            $table->foreignId('audited_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->index(['assessment_id', 'user_id', 'status']);
        });

        Schema::create('exam_security_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('assessment_attempts')->cascadeOnDelete();
            $table->string('event_type', 50); // TAB_BLUR, FULLSCREEN_EXIT, COPY_PASTE, WINDOW_RESIZE, HEARTBEAT_TIMEOUT
            $table->string('severity', 20)->default('WARNING'); // INFO, WARNING, VIOLATION, CRITICAL
            $table->text('details')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('occurred_at');

            $table->index(['attempt_id', 'event_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_security_events');
        Schema::dropIfExists('assessment_attempts');
    }
};
