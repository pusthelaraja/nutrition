<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Category;
use App\Models\Product;

class NavigationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $categories = Category::where('is_active', true)
            ->with(['products' => function($query) {
                $query->where('is_active', true)->limit(5);
            }])
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        // Debug: Let's also get all products to see what's available
        $allProducts = Product::where('is_active', true)->with('category')->get();

        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->limit(4)
            ->get();

        $view->with([
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'allProducts' => $allProducts
        ]);
    }
}
