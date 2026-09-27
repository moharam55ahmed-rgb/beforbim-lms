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
        Schema::create('lesson_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->unique()->constrained('lessons')->cascadeOnDelete();
            $table->string('video_provider', 30)->nullable(); // HLS, S3_STREAM, VIMEO_PRO, YOUTUBE_UNLISTED
            $table->string('video_asset_id', 255)->nullable();
            $table->string('video_hls_url', 500)->nullable();
            $table->longText('document_markdown')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('lesson_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->string('title_ar', 255);
            $table->string('title_en', 255)->nullable();
            $table->string('file_name', 255);
            $table->string('file_path', 500); // S3 / private storage path
            $table->string('file_extension', 15); // rvt, ifc, dwg, pdf, zip, dyn
            $table->unsignedBigInteger('file_size_bytes');
            $table->string('mime_type', 100);
            $table->boolean('is_downloadable')->default(true);
            $table->timestamps();

            $table->index(['lesson_id', 'file_extension']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_resources');
        Schema::dropIfExists('lesson_contents');
    }
};
