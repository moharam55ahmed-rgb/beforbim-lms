<?php

namespace App\Modules\Curriculum\Services;

use App\Modules\Course\Models\Course;
use App\Modules\Curriculum\Models\CourseSection;
use App\Modules\Lesson\Models\Lesson;
use App\Modules\Media\Models\LessonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CurriculumService
{
    /**
     * Add a new section to a course.
     */
    public function addSection(Course $course, array $data): CourseSection
    {
        return DB::transaction(function () use ($course, $data) {
            $nextOrder = (int) ($course->sections()->max('order_index') ?? 0) + 1;

            return $course->sections()->create([
                'title_ar' => $data['title_ar'],
                'title_en' => $data['title_en'] ?? null,
                'description_ar' => $data['description_ar'] ?? null,
                'order_index' => $data['order_index'] ?? $nextOrder,
            ]);
        });
    }

    /**
     * Update an existing section.
     */
    public function updateSection(CourseSection $section, array $data): CourseSection
    {
        $section->update([
            'title_ar' => $data['title_ar'] ?? $section->title_ar,
            'title_en' => $data['title_en'] ?? $section->title_en,
            'description_ar' => $data['description_ar'] ?? $section->description_ar,
            'order_index' => $data['order_index'] ?? $section->order_index,
        ]);

        return $section;
    }

    /**
     * Delete a section and associated lessons.
     */
    public function deleteSection(CourseSection $section): bool
    {
        return DB::transaction(function () use ($section) {
            // Delete resources files if any
            foreach ($section->lessons as $lesson) {
                $this->deleteLesson($lesson);
            }

            return (bool) $section->delete();
        });
    }

    /**
     * Reorder sections of a course.
     */
    public function reorderSections(Course $course, array $orderedIds): void
    {
        DB::transaction(function () use ($orderedIds) {
            foreach ($orderedIds as $index => $id) {
                CourseSection::where('id', $id)->update(['order_index' => $index + 1]);
            }
        });
    }

    /**
     * Add a lesson to a section.
     */
    public function addLesson(CourseSection $section, array $data): Lesson
    {
        return DB::transaction(function () use ($section, $data) {
            $nextOrder = (int) ($section->lessons()->max('order_index') ?? 0) + 1;

            $lesson = $section->lessons()->create([
                'title_ar' => $data['title_ar'],
                'title_en' => $data['title_en'] ?? null,
                'lesson_type' => $data['lesson_type'] ?? 'VIDEO',
                'duration_seconds' => $data['duration_seconds'] ?? 0,
                'order_index' => $data['order_index'] ?? $nextOrder,
                'is_preview_free' => (bool) ($data['is_preview_free'] ?? false),
                'is_mandatory' => (bool) ($data['is_mandatory'] ?? true),
            ]);

            // Add optional initial lesson content (e.g. video URL or text body)
            if (! empty($data['video_url']) || ! empty($data['article_body'])) {
                $lesson->content()->create([
                    'content_type' => $lesson->lesson_type,
                    'video_provider' => $data['video_provider'] ?? 'LOCAL',
                    'video_url' => $data['video_url'] ?? null,
                    'article_body_ar' => $data['article_body'] ?? null,
                ]);
            }

            return $lesson;
        });
    }

    /**
     * Update an existing lesson.
     */
    public function updateLesson(Lesson $lesson, array $data): Lesson
    {
        return DB::transaction(function () use ($lesson, $data) {
            $lesson->update([
                'title_ar' => $data['title_ar'] ?? $lesson->title_ar,
                'title_en' => $data['title_en'] ?? $lesson->title_en,
                'lesson_type' => $data['lesson_type'] ?? $lesson->lesson_type,
                'duration_seconds' => $data['duration_seconds'] ?? $lesson->duration_seconds,
                'order_index' => $data['order_index'] ?? $lesson->order_index,
                'is_preview_free' => array_key_exists('is_preview_free', $data) ? (bool) $data['is_preview_free'] : $lesson->is_preview_free,
                'is_mandatory' => array_key_exists('is_mandatory', $data) ? (bool) $data['is_mandatory'] : $lesson->is_mandatory,
            ]);

            if (isset($data['video_url']) || isset($data['article_body'])) {
                $lesson->content()->updateOrCreate(
                    ['lesson_id' => $lesson->id],
                    [
                        'content_type' => $lesson->lesson_type,
                        'video_provider' => $data['video_provider'] ?? 'LOCAL',
                        'video_url' => $data['video_url'] ?? null,
                        'article_body_ar' => $data['article_body'] ?? null,
                    ]
                );
            }

            return $lesson;
        });
    }

    /**
     * Delete a lesson.
     */
    public function deleteLesson(Lesson $lesson): bool
    {
        return DB::transaction(function () use ($lesson) {
            // Delete attached resources
            foreach ($lesson->resources as $res) {
                $this->deleteResource($res);
            }

            if ($lesson->content) {
                $lesson->content->delete();
            }

            return (bool) $lesson->delete();
        });
    }

    /**
     * Reorder lessons within a section.
     */
    public function reorderLessons(CourseSection $section, array $orderedIds): void
    {
        DB::transaction(function () use ($orderedIds) {
            foreach ($orderedIds as $index => $id) {
                Lesson::where('id', $id)->update(['order_index' => $index + 1]);
            }
        });
    }

    /**
     * Add a downloadable engineering resource (Revit file, CAD, PDF, etc.) to a lesson.
     */
    public function addResource(Lesson $lesson, array $data): LessonResource
    {
        return DB::transaction(function () use ($lesson, $data) {
            return $lesson->resources()->create([
                'title_ar' => $data['title_ar'],
                'title_en' => $data['title_en'] ?? null,
                'file_name' => $data['file_name'],
                'file_path' => $data['file_path'],
                'file_extension' => $data['file_extension'] ?? pathinfo($data['file_name'], PATHINFO_EXTENSION),
                'file_size_bytes' => $data['file_size_bytes'] ?? 0,
                'mime_type' => $data['mime_type'] ?? 'application/octet-stream',
                'is_downloadable' => (bool) ($data['is_downloadable'] ?? true),
            ]);
        });
    }

    /**
     * Delete a downloadable resource.
     */
    public function deleteResource(LessonResource $resource): bool
    {
        if ($resource->file_path && Storage::disk('public')->exists($resource->file_path)) {
            Storage::disk('public')->delete($resource->file_path);
        }

        return (bool) $resource->delete();
    }
}
