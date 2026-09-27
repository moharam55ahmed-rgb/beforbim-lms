<?php

namespace App\Modules\Media\Models;

use App\Modules\Lesson\Models\Lesson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LessonContent extends Model
{
    protected $fillable = [
        'lesson_id',
        'title',
        'type', // video, text, document, external_video, quiz, assignment
        'content_data',
        'ordering',
        'duration',
        'visibility_status', // visible, hidden, draft
        'preview_availability',
        'linked_entity_type',
        'linked_entity_id',
        'video_provider',
        'video_asset_id',
        'video_hls_url',
        'document_markdown',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'ordering' => 'integer',
            'duration' => 'integer',
            'preview_availability' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function linkedEntity(): MorphTo
    {
        return $this->morphTo('linked_entity');
    }

    public function isPreviewable(): bool
    {
        return $this->preview_availability;
    }

    public function isVisible(): bool
    {
        return $this->visibility_status === 'visible';
    }
}
