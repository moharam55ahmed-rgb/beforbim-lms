<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('short_description_ar', 500)->nullable()->change();
            $table->longText('description_ar')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('short_description_ar', 500)->nullable(false)->change();
            $table->longText('description_ar')->nullable(false)->change();
        });
    }
};
