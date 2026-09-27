<?php

namespace App\Modules\Category\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Category\Models\Category;
use App\Modules\Category\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService
    ) {}

    /**
     * Display categories listing.
     */
    public function index(): View
    {
        $categories = Category::withCount('courses')
            ->with('parent')
            ->orderBy('display_order')
            ->get();

        $rootCategories = Category::whereNull('parent_id')->get();

        return view('categories.index', compact('categories', 'rootCategories'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description_ar' => ['nullable', 'string'],
            'icon_svg' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $this->categoryService->createCategory($validated);

        return back()->with('success', 'تم إنشاء التصنيف الهندسي بنجاح.');
    }

    /**
     * Update an existing category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description_ar' => ['nullable', 'string'],
            'icon_svg' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $this->categoryService->updateCategory($category, $validated);

        return back()->with('success', 'تم تحديث بيانات التصنيف بنجاح.');
    }

    /**
     * Remove the category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        try {
            $this->categoryService->deleteCategory($category);
            return back()->with('success', 'تم حذف التصنيف بنجاح.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
