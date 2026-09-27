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
        Schema::create('device_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('session_token_hash', 64)->unique();
            $table->string('ip_address', 45);
            $table->string('user_agent', 500);
            $table->string('device_type', 30)->default('DESKTOP');
            $table->string('browser', 50)->nullable();
            $table->string('operating_system', 50)->nullable();
            $table->string('location_country', 50)->nullable();
            $table->string('location_city', 50)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('last_activity_at')->index();
            $table->timestamp('revoked_at')->nullable();
            $table->string('revocation_reason', 100)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_active', 'session_token_hash'], 'idx_user_active_device');
        });

        Schema::create('security_warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('warning_type', 60); // CONCURRENT_LOGIN, EXAM_TAB_SWITCH, TAMPER_ATTEMPT
            $table->string('severity', 20)->default('MEDIUM'); // LOW, MEDIUM, HIGH, CRITICAL
            $table->text('details');
            $table->string('ip_address', 45)->nullable();
            $table->boolean('is_acknowledged')->default(false);
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();
        });

        Schema::create('account_restrictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('imposed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('restriction_type', 50); // TEMPORARY_LOCK, EXAM_BAN, DEVICE_RESTRICTION
            $table->text('reason');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_restrictions');
        Schema::dropIfExists('security_warnings');
        Schema::dropIfExists('device_sessions');
    }
};
