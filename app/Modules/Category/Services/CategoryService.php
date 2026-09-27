<?php

namespace App\Modules\Category\Services;

use App\Modules\Category\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Get active root categories with their subcategories.
     */
    public function getActiveTree(): Collection
    {
        return Category::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => fn($q) => $q->where('is_active', true)->orderBy('display_order')])
            ->orderBy('display_order')
            ->get();
    }

    /**
     * Create a new category.
     */
    public function createCategory(array $data): Category
    {
        $category = new Category();
        $category->parent_id = $data['parent_id'] ?? null;
        $category->name_ar = $data['name_ar'];
        $category->name_en = $data['name_en'] ?? null;
        $category->slug = Str::slug($data['name_en'] ?? $data['name_ar']);
        $category->description_ar = $data['description_ar'] ?? null;
        $category->icon_svg = $data['icon_svg'] ?? null;
        $category->is_active = (bool) ($data['is_active'] ?? true);
        $category->display_order = (int) ($data['display_order'] ?? 0);
        $category->save();

        return $category;
    }

    /**
     * Update an existing category.
     */
    public function updateCategory(Category $category, array $data): Category
    {
        $category->update([
            'parent_id' => $data['parent_id'] ?? $category->parent_id,
            'name_ar' => $data['name_ar'] ?? $category->name_ar,
            'name_en' => $data['name_en'] ?? $category->name_en,
            'description_ar' => $data['description_ar'] ?? $category->description_ar,
            'icon_svg' => $data['icon_svg'] ?? $category->icon_svg,
            'is_active' => array_key_exists('is_active', $data) ? (bool) $data['is_active'] : $category->is_active,
            'display_order' => $data['display_order'] ?? $category->display_order,
        ]);

        return $category;
    }

    /**
     * Delete a category if it has no assigned courses.
     */
    public function deleteCategory(Category $category): bool
    {
        if ($category->courses()->exists()) {
            throw new \RuntimeException('لا يمكن حذف التصنيف لأنه مرتبط بدورات تدريبية حالية.');
        }

        return (bool) $category->delete();
    }
}
