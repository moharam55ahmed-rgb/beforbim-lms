<?php

namespace App\Modules\Media\Models;

use App\Modules\Lesson\Models\Lesson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonResource extends Model
{
    protected $fillable = [
        'lesson_id',
        'title_ar',
        'title_en',
        'file_name',
        'file_path',
        'file_extension',
        'file_size_bytes',
        'mime_type',
        'is_downloadable',
    ];

    protected function casts(): array
    {
        return [
            'file_size_bytes' => 'integer',
            'is_downloadable' => 'boolean',
        ];
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
