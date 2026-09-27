<?php

namespace App\Modules\Course\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        $course = $this->route('course');

        return $this->user()->can('update', $course);
    }

    public function rules(): array
    {
        return [
            'title_ar' => ['sometimes', 'required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'short_description_ar' => ['nullable', 'string', 'max:500'],
            'description_ar' => ['nullable', 'string'],
            'level' => ['sometimes', 'required', 'in:BEGINNER,INTERMEDIATE,ADVANCED,ALL_LEVELS'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'thumbnail_url' => ['nullable', 'string', 'max:500'],
            'promo_video_url' => ['nullable', 'string', 'max:500'],
            'software_requirements' => ['nullable', 'array'],
            'prerequisites' => ['nullable', 'array'],
            'learning_outcomes' => ['nullable', 'array'],
        ];
    }
}
