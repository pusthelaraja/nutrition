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
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        $featuredProducts = Product::with(['category:id,name,slug', 'featuredImage'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(8)
            ->get();

        return view('frontend.home', compact('categories', 'featuredProducts'));
    }
}
