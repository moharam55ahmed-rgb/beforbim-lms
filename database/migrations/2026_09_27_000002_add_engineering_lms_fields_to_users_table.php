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
            $table->uuid('uuid')->unique()->nullable()->after('id');
            $table->string('phone_country_code', 8)->nullable()->after('password');
            $table->string('phone_number', 20)->nullable()->index()->after('phone_country_code');
            $table->string('avatar_url', 255)->nullable()->after('phone_number');
            $table->string('engineering_title', 120)->nullable()->after('avatar_url');
            $table->text('bio')->nullable()->after('engineering_title');
            $table->string('status', 30)->default('ACTIVE')->index()->after('bio');
            $table->unsignedTinyInteger('max_allowed_devices')->default(1)->after('status');
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'uuid',
                'phone_country_code',
                'phone_number',
                'avatar_url',
                'engineering_title',
                'bio',
                'status',
                'max_allowed_devices',
            ]);
        });
    }
};
