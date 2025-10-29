<?php

namespace App\Http\View\Composers;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $cartCount = 0;

        if (Auth::guard('customer')->check()) {
            // For logged-in customers
            $cart = Cart::where('user_id', Auth::guard('customer')->id())->first();
            $cartCount = $cart ? $cart->total_items : 0;
        } else {
            // For guest users
            $cart = Cart::where('session_id', session()->getId())
                        ->whereNull('user_id')
                        ->first();
            $cartCount = $cart ? $cart->total_items : 0;
        }

        $view->with('cartCount', $cartCount);
    }
}
