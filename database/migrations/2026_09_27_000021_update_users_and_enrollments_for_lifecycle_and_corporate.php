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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 25)->nullable()->after('phone_number');
            $table->string('avatar', 255)->nullable()->after('avatar_url');
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            $table->timestamp('last_login_at')->nullable()->after('max_allowed_devices');
        });

        // Ensure status column accommodates lowercase values: 'active', 'pending_verification', 'suspended', 'blocked'
        Schema::table('users', function (Blueprint $table) {
            $table->string('status', 30)->default('active')->change();
        });

        // Update enrollments source column to support CORPORATE_TRAINING
        Schema::table('enrollments', function (Blueprint $table) {
            $table->string('source', 40)->default('DIRECT_PURCHASE')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'avatar', 'phone_verified_at', 'last_login_at']);
        });
    }
};
