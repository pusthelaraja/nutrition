<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Traits\HasImagePicker;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use HasImagePicker;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            // Validation is handled by StoreProductRequest
            $data = $request->all();

            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($request->name);
            }

            // Handle boolean fields
            $data['manage_stock'] = $request->has('manage_stock');
            $data['in_stock'] = $request->has('manage_stock') ? ($request->stock_quantity > 0) : true;
            $data['is_active'] = $request->has('is_active');
            $data['is_featured'] = $request->has('is_featured');

            // Handle attributes JSON
            if ($request->has('attributes') && $request->attributes) {
                // Handle different types of attributes input
                if (is_array($request->attributes)) {
                    $data['attributes'] = $request->attributes;
                } elseif (is_string($request->attributes)) {
                    $data['attributes'] = json_decode($request->attributes, true);
                } else {
                    // Handle ParameterBag or other object types
                    $data['attributes'] = $request->attributes->all();
                }
            }

            // Create the product first
            $product = Product::create($data);

            // Handle featured image
            if ($request->has('featured_image') && !empty($request->featured_image)) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $request->featured_image,
                    'is_featured' => true,
                    'sort_order' => 0
                ]);
            }

            // Handle additional images from file manager
            if ($request->has('images')) {
                $images = [];
                if (is_array($request->images)) {
                    // Handle array of image paths from multiple image picker
                    $images = array_filter($request->images);
                } elseif (is_string($request->images)) {
                    // Handle JSON string of images
                    $decodedImages = json_decode($request->images, true);
                    if (is_array($decodedImages)) {
                        $images = array_filter($decodedImages);
                    }
                }

                // Store additional images in separate table
                if (!empty($images)) {
                    $sortOrder = $request->has('featured_image') && !empty($request->featured_image) ? 1 : 0;
                    foreach ($images as $imagePath) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePath,
                            'is_featured' => false,
                            'sort_order' => $sortOrder++
                        ]);
                    }
                }
            } elseif ($request->hasFile('images')) {
                // Fallback to direct file upload
                $images = $this->processImages($request, 'images', [
                    'generate_thumbnail' => true,
                    'thumbnail_size' => 300
                ]);

                if (!empty($images)) {
                    $sortOrder = $request->has('featured_image') && !empty($request->featured_image) ? 1 : 0;
                    foreach ($images as $imagePath) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePath,
                            'is_featured' => false,
                            'sort_order' => $sortOrder++
                        ]);
                    }
                }
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Product creation failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create product. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category', 'orderItems');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load(['images', 'featuredImage', 'category']);
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            // Validation is handled by UpdateProductRequest
            $data = $request->all();

            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($request->name);
            }

            // Handle boolean fields
            $data['manage_stock'] = $request->has('manage_stock');
            $data['in_stock'] = $request->has('manage_stock') ? ($request->stock_quantity > 0) : true;
            $data['is_active'] = $request->has('is_active');
            $data['is_featured'] = $request->has('is_featured');

            // Handle attributes JSON
            if ($request->has('attributes') && $request->attributes) {
                // Handle different types of attributes input
                if (is_array($request->attributes)) {
                    $data['attributes'] = $request->attributes;
                } elseif (is_string($request->attributes)) {
                    $data['attributes'] = json_decode($request->attributes, true);
                } else {
                    // Handle ParameterBag or other object types
                    $data['attributes'] = $request->attributes->all();
                }
            }

            // Update the product first
            $product->update($data);

            // Handle featured image
            if ($request->has('featured_image') && !empty($request->featured_image)) {
                // Delete existing featured image
                $product->images()->where('is_featured', true)->delete();

                // Create new featured image
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $request->featured_image,
                    'is_featured' => true,
                    'sort_order' => 0
                ]);
            }

            // Handle additional images from file manager
            if ($request->has('images')) {
                $images = [];
                if (is_array($request->images)) {
                    // Handle array of image paths from multiple image picker
                    $images = array_filter($request->images);
                } elseif (is_string($request->images)) {
                    // Handle JSON string of images
                    $decodedImages = json_decode($request->images, true);
                    if (is_array($decodedImages)) {
                        $images = array_filter($decodedImages);
                    }
                }

                // Delete existing non-featured images and create new ones
                if (!empty($images)) {
                    // Delete old non-featured images
                    $product->images()->where('is_featured', false)->delete();

                    // Create new additional images
                    $sortOrder = $request->has('featured_image') && !empty($request->featured_image) ? 1 : 0;
                    foreach ($images as $imagePath) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePath,
                            'is_featured' => false,
                            'sort_order' => $sortOrder++
                        ]);
                    }
                }
            } elseif ($request->hasFile('images')) {
                // Fallback to direct file upload
                $images = $this->processImages($request, 'images', [
                    'generate_thumbnail' => true,
                    'thumbnail_size' => 300
                ]);

                if (!empty($images)) {
                    // Delete old non-featured images
                    $product->images()->where('is_featured', false)->delete();

                    // Create new additional images
                    $sortOrder = $request->has('featured_image') && !empty($request->featured_image) ? 1 : 0;
                    foreach ($images as $imagePath) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePath,
                            'is_featured' => false,
                            'sort_order' => $sortOrder++
                        ]);
                    }
                }
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Product update failed: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'request_data' => $request->all(),
                'exception' => $e
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update product. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Check if product has order history
            if ($product->orderItems()->count() > 0) {
                return redirect()->route('admin.products.index')
                    ->with('error', 'Cannot delete product with order history.');
            }

            // Delete associated images first
            $product->images()->delete();

            // Delete the product
            $product->delete();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Product deletion failed: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'exception' => $e
            ]);

            return redirect()->route('admin.products.index')
                ->with('error', 'Failed to delete product. Please try again.');
        }
    }
}
