<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Get or create cart for current customer/session
     */
    private function getOrCreateCart()
    {
        if (Auth::guard('customer')->check()) {
            $cart = Cart::where('user_id', Auth::guard('customer')->id())->first();
            if (!$cart) {
                \Log::info('Creating new customer cart', [
                    'user_id' => Auth::guard('customer')->id(),
                    'session_id' => Session::getId()
                ]);
                $cart = Cart::create([
                    'user_id' => Auth::guard('customer')->id(),
                    'session_id' => Session::getId(),
                    'total_amount' => 0,
                    'total_items' => 0,
                    'discount_amount' => 0,
                    'shipping_amount' => 0,
                    'tax_amount' => 0,
                    'final_amount' => 0
                ]);
            }
        } else {
            $cart = Cart::where('session_id', Session::getId())->first();
            if (!$cart) {
                \Log::info('Creating new guest cart', [
                    'session_id' => Session::getId()
                ]);
                $cart = Cart::create([
                    'user_id' => null,
                    'session_id' => Session::getId(),
                    'total_amount' => 0,
                    'total_items' => 0,
                    'discount_amount' => 0,
                    'shipping_amount' => 0,
                    'tax_amount' => 0,
                    'final_amount' => 0
                ]);
            }
        }

        return $cart;
    }

    /**
     * Display the shopping cart
     */
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load(['items.product.featuredImage', 'coupon']);
        // Preview shipping and tax (based on default address if available)
        $customer = Auth::guard('customer')->user();
        $pincode = null;
        if ($customer && method_exists($customer, 'addresses')) {
            $defaultAddress = $customer->addresses()->orderByDesc('is_default')->orderByDesc('id')->first();
            if ($defaultAddress) { $pincode = $defaultAddress->postal_code; }
        }

        $shippingOptions = [];
        $previewShipping = 0;
        // Compute physical and chargeable weights for summary
        $cartWeightKg = (float) ($cart->items->sum(function ($item) {
            $productWeight = (float) ($item->product->weight ?? 0);
            return $productWeight * (int) $item->quantity;
        }));
        // DTDC-style 0.5 kg slab rounding
        $chargeableWeightKg = $cartWeightKg > 0 ? (ceil($cartWeightKg * 2) / 2) : 0.0;
        if ($pincode) {
            $service = new \App\Services\ShippingService();
            $shippingOptions = $service->getOptionsForCart($cart, $pincode, null);
            if (!empty($shippingOptions)) {
                $previewShipping = $shippingOptions[0]['price'];
            }
        }

        $gstEnabled = (bool) env('GST_ENABLED', false);
        $gstRate = (float) env('GST_RATE', 18);
        $previewTax = 0;
        $previewTotal = $cart->total_amount - $cart->discount_amount + $previewShipping;
        if ($gstEnabled) {
            $previewTax = round($previewTotal * ($gstRate/100), 2);
            $previewTotal += $previewTax;
        }

        return view('frontend.cart.index', compact('cart', 'shippingOptions', 'previewShipping', 'gstEnabled', 'gstRate', 'previewTax', 'previewTotal', 'cartWeightKg', 'chargeableWeightKg'));
    }

    /**
     * Get cart count for AJAX requests
     */
    public function count()
    {
        $cart = $this->getOrCreateCart();
        // Return number of distinct items, not total quantity
        return response()->json(['count' => $cart->items()->count()]);
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $requestId = uniqid('req_');
        try {
            \Log::info('Cart add request received', [
                'request_id' => $requestId,
                'client_request_id' => $request->input('request_id'),
                'is_ajax' => $request->ajax(),
                'wants_json' => $request->wantsJson(),
                'content_type' => $request->header('Content-Type'),
                'requested_with' => $request->header('X-Requested-With'),
                'data' => $request->all(),
                'session_id' => session()->getId()
            ]);

            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1|max:10'
            ]);

            $product = Product::findOrFail($request->product_id);
            $cart = $this->getOrCreateCart();

            \Log::info('Cart retrieved/created', [
                'request_id' => $requestId,
                'cart_id' => $cart->id,
                'cart_user_id' => $cart->user_id,
                'cart_session_id' => $cart->session_id,
                'existing_items_count' => $cart->items()->count()
            ]);

            // Check if item already exists in cart
            $existingItem = $cart->items()->where('product_id', $product->id)->first();

            if ($existingItem) {
                // Update quantity
                $oldQuantity = $existingItem->quantity;
                $existingItem->updateQuantity($existingItem->quantity + $request->quantity);
                \Log::info('Updated existing item', [
                    'request_id' => $requestId,
                    'product_id' => $product->id,
                    'old_quantity' => $oldQuantity,
                    'new_quantity' => $existingItem->quantity
                ]);
            } else {
                // Add new item
                $itemData = [
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'price' => $product->sale_price ?? $product->price,
                    'total_price' => ($product->sale_price ?? $product->price) * $request->quantity
                ];
                // Add weight snapshot if column exists
                if (Schema::hasColumn('cart_items', 'weight_kg')) {
                    $itemData['weight_kg'] = $product->weight;
                }
                $cartItem = CartItem::create($itemData);
                \Log::info('Created new cart item', [
                    'request_id' => $requestId,
                    'cart_item_id' => $cartItem->id,
                    'product_id' => $product->id,
                    'quantity' => $request->quantity
                ]);
            }

            $cart->calculateTotals();

            \Log::info('Cart add successful', [
                'cart_id' => $cart->id,
                'total_items' => $cart->total_items,
                'final_amount' => $cart->final_amount
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product added to cart successfully!',
                    // Return distinct item count for UI badges
                    'cart_count' => $cart->items()->count(),
                    'cart_total' => number_format($cart->final_amount, 2)
                ]);
            }

            return redirect()->back()->with('success', 'Product added to cart successfully!');
        } catch (\Exception $e) {
            \Log::error('Cart add error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error adding product to cart: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error adding product to cart.');
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $cartItem = CartItem::findOrFail($id);
        $cartItem->updateQuantity($request->quantity);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'item_total' => number_format($cartItem->total_price, 2),
                'cart_total' => number_format($cartItem->cart->final_amount, 2),
                // Do not increment item count when changing quantity
                'cart_count' => $cartItem->cart->items()->count()
            ]);
        }

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cart = $cartItem->cart;
        $cartItem->delete();

        $cart->calculateTotals();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart!',
                'cart_total' => number_format($cart->final_amount, 2),
                'cart_count' => $cart->items()->count()
            ]);
        }

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    /**
     * Apply coupon code
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        $cart = $this->getOrCreateCart();
        $coupon = Coupon::where('code', $request->coupon_code)
                        ->where('is_active', true)
                        ->where('expires_at', '>', now())
                        ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired coupon code!'
            ]);
        }

        // Check if coupon is already used by this user
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon usage limit exceeded!'
            ]);
        }

        $cart->update(['coupon_code' => $coupon->code]);
        $cart->calculateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'discount_amount' => number_format($cart->discount_amount, 2),
            'final_amount' => number_format($cart->final_amount, 2)
        ]);
    }

    /**
     * Remove coupon code
     */
    public function removeCoupon()
    {
        $cart = $this->getOrCreateCart();
        $cart->update(['coupon_code' => null]);
        $cart->calculateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed successfully!',
            'final_amount' => number_format($cart->final_amount, 2)
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->clear();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully!'
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully!');
    }

    /**
     * Get cart count for header
     */
    public function getCount()
    {
        $cart = $this->getOrCreateCart();
        return response()->json([
            'count' => $cart->total_items,
            'total' => number_format($cart->final_amount, 2)
        ]);
    }
}
