<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Category;

class NavigationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $categories = Category::where('is_active', true)
            ->with(['products' => function ($query) {
                $query->select('id', 'category_id', 'name', 'slug')
                    ->where('is_active', true)
                    ->orderBy('name');
            }])
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        $view->with('categories', $categories);
    }
}
