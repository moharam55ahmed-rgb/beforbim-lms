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
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->string('model_type', 120)->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('collection_name', 50)->default('default')->index(); // e.g. avatars, lessons, assignments, bim_models, promo_videos
            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->string('disk', 30)->default('local'); // local, public, s3
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size'); // Size in bytes
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};
