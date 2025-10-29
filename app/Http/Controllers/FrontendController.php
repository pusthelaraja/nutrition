<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class FrontendController extends Controller
{
    /**
     * Display the homepage
     */
    public function home()
    {
        $products = Product::with(['images', 'featuredImage'])->where('is_active', true)->get();
        $categories = Category::where('is_active', true)->withCount('products')->orderBy('sort_order')->get();
        $featuredProducts = Product::with(['images', 'featuredImage'])->where('is_active', true)->where('is_featured', true)->limit(4)->get();
        $newProducts = Product::with(['images', 'featuredImage'])->where('is_active', true)->latest()->limit(6)->get();

        return view('frontend.home', compact('products', 'categories', 'featuredProducts', 'newProducts'));
    }
}
