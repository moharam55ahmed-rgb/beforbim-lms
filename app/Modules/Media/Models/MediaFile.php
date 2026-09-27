<?php

namespace App\Modules\Media\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class MediaFile extends Model
{
    protected $fillable = [
        'model_type',
        'model_id',
        'collection_name',
        'file_name',
        'file_path',
        'disk',
        'mime_type',
        'file_size',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrl(): string
    {
        if ($this->disk === 'public') {
            return Storage::disk('public')->url($this->file_path);
        }

        // For private/local assets, never expose raw storage path; generate secure signed route
        return URL::temporarySignedRoute(
            'media.download',
            now()->addMinutes(30),
            ['media' => $this->id]
        );
    }

    public function getReadableSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . ($units[$i] ?? 'B');
    }
}
