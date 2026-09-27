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
        Schema::table('certificates', function (Blueprint $table) {
            if (! Schema::hasColumn('certificates', 'verification_code')) {
                $table->string('verification_code', 64)->nullable()->unique()->after('certificate_number');
            }
            if (! Schema::hasColumn('certificates', 'status')) {
                $table->string('status', 20)->default('active')->index()->after('issued_at'); // active, revoked, expired
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            if (Schema::hasColumn('certificates', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('certificates', 'verification_code')) {
                $table->dropColumn('verification_code');
            }
        });
    }
};
