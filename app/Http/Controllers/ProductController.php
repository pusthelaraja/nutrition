<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        // Debug: Log all request parameters
        \Log::info('Product filter request:', $request->all());

        $query = Product::with(['category', 'images', 'featuredImage'])->where('is_active', true);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by featured products
        if ($request->has('featured') && $request->featured) {
            $query->where('is_featured', true);
        }

        // Filter by sale products
        if ($request->has('sale') && $request->sale) {
            $query->whereNotNull('sale_price')->where('sale_price', '>', 0);
        }

        // Filter by in stock
        if ($request->has('in_stock') && $request->in_stock) {
            $query->where('in_stock', true);
        }

        // Filter by price range
        if ($request->has('price_range') && $request->price_range) {
            $priceRange = $request->price_range;
            if ($priceRange === '0-500') {
                $query->where('price', '<=', 500);
            } elseif ($priceRange === '500-1000') {
                $query->whereBetween('price', [500, 1000]);
            } elseif ($priceRange === '1000-2000') {
                $query->whereBetween('price', [1000, 2000]);
            } elseif ($priceRange === '2000+') {
                $query->where('price', '>', 2000);
            }
        }

        // Sort products
        if ($request->has('sort') && $request->sort) {
            switch ($request->sort) {
                case 'newest':
                    $query->latest();
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function($query) {
                $query->where('is_active', true);
            }])
            ->get();

        // If it's an AJAX request, return only the products section
        if ($request->ajax()) {
            return response()->json([
                'products_html' => view('frontend.products.partials.products-grid', compact('products'))->render(),
                'results_info_html' => view('frontend.products.partials.results-info', compact('products'))->render()
            ]);
        }

        return view('frontend.products.index', compact('products', 'categories'));
    }

    /**
     * Display the specified product by slug
     */
    public function show($slug)
    {
        // Debug: Log the slug being requested
        \Log::info('Product slug requested: ' . $slug);

        $product = Product::with(['category', 'images', 'featuredImage'])->where('is_active', true)->where('slug', $slug)->firstOrFail();

        // Debug: Log the product found
        \Log::info('Product found: ' . $product->name . ' (ID: ' . $product->id . ')');

        $relatedProducts = Product::with(['images', 'featuredImage'])->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        // Get all product images
        $productImages = $product->images->pluck('image_path')->toArray();

        return view('frontend.products.show', compact('product', 'relatedProducts', 'productImages'));
    }
}
