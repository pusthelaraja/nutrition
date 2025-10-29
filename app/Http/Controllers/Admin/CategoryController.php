<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\HasImagePicker;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use HasImagePicker;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|min:3',
                'slug' => 'nullable|string|max:255|unique:categories,slug|regex:/^[a-z0-9\-]+$/',
                'description' => 'nullable|string|max:2000',
                'image' => 'nullable|string|max:500',
                'is_active' => 'boolean',
                'sort_order' => 'nullable|integer|min:0|max:9999'
            ], [
                'name.required' => 'Category name is required.',
                'name.min' => 'Category name must be at least 3 characters.',
                'name.max' => 'Category name cannot exceed 255 characters.',
                'slug.unique' => 'This slug is already taken.',
                'slug.regex' => 'Slug can only contain lowercase letters, numbers, and hyphens.',
                'description.max' => 'Description cannot exceed 2000 characters.',
                'sort_order.max' => 'Sort order cannot exceed 9999.'
            ]);

            $data = $request->all();

            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($request->name);
            }

            $data['is_active'] = $request->has('is_active');

            // Handle image upload from file manager
            if ($request->has('image') && !empty($request->image)) {
                $data['image'] = $request->image;
            } elseif ($request->hasFile('image')) {
                // Fallback to direct file upload
                $imagePath = $this->processImage($request, 'image', [
                    'generate_thumbnail' => true,
                    'thumbnail_size' => 300
                ]);
                $data['image'] = $imagePath;
            }

            Category::create($data);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Category creation failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create category. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load('products');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|min:3',
                'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id . '|regex:/^[a-z0-9\-]+$/',
                'description' => 'nullable|string|max:2000',
                'image' => 'nullable|string|max:500',
                'is_active' => 'boolean',
                'sort_order' => 'nullable|integer|min:0|max:9999'
            ], [
                'name.required' => 'Category name is required.',
                'name.min' => 'Category name must be at least 3 characters.',
                'name.max' => 'Category name cannot exceed 255 characters.',
                'slug.unique' => 'This slug is already taken.',
                'slug.regex' => 'Slug can only contain lowercase letters, numbers, and hyphens.',
                'description.max' => 'Description cannot exceed 2000 characters.',
                'sort_order.max' => 'Sort order cannot exceed 9999.'
            ]);

            $data = $request->all();

            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($request->name);
            }

            $data['is_active'] = $request->has('is_active');

            // Handle image upload from file manager
            if ($request->has('image') && !empty($request->image)) {
                $data['image'] = $request->image;
            } elseif ($request->hasFile('image')) {
                // Fallback to direct file upload
                $imagePath = $this->processImage($request, 'image', [
                    'generate_thumbnail' => true,
                    'thumbnail_size' => 300
                ]);
                $data['image'] = $imagePath;
            }

            $category->update($data);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Category update failed: ' . $e->getMessage(), [
                'category_id' => $category->id,
                'request_data' => $request->all(),
                'exception' => $e
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update category. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            // Check if category has products
            if ($category->products()->count() > 0) {
                return redirect()->route('admin.categories.index')
                    ->with('error', 'Cannot delete category with products.');
            }

            $category->delete();

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Category deletion failed: ' . $e->getMessage(), [
                'category_id' => $category->id,
                'exception' => $e
            ]);

            return redirect()->route('admin.categories.index')
                ->with('error', 'Failed to delete category. Please try again.');
        }
    }
}
