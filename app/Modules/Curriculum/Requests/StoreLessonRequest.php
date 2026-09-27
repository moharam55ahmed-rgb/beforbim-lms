<?php

namespace App\Modules\Curriculum\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole(['super_admin', 'admin', 'instructor']);
    }

    public function rules(): array
    {
        return [
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'lesson_type' => ['required', 'in:VIDEO,ARTICLE,DOCUMENT,LIVE_SESSION,ASSESSMENT,ASSIGNMENT'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'order_index' => ['nullable', 'integer', 'min:1'],
            'is_preview_free' => ['nullable', 'boolean'],
            'is_mandatory' => ['nullable', 'boolean'],
            'video_url' => ['nullable', 'string', 'max:500'],
            'video_provider' => ['nullable', 'string', 'in:LOCAL,VIMEO,YOUTUBE,S3'],
            'article_body' => ['nullable', 'string'],
        ];
    }
}
