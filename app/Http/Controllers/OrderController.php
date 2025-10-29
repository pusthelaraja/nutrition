<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display order confirmation page
     */
    public function confirmation($orderId)
    {
        $order = Order::with(['items.product.featuredImage', 'customer'])
                     ->where('id', $orderId)
                     ->firstOrFail();

        // If user is logged in, verify they own this order
        if (Auth::guard('customer')->check()) {
            if ($order->user_id !== Auth::guard('customer')->id()) {
                abort(403, 'Unauthorized access to order.');
            }
        }

        return view('frontend.orders.confirmation', compact('order'));
    }

    /**
     * Display order tracking page
     */
    public function tracking(Request $request)
    {
        $order = null;
        $orderNumber = $request->get('order_number');

        if ($orderNumber) {
            $order = Order::with(['items.product', 'customer'])
                          ->where('order_number', $orderNumber)
                          ->first();

            if (!$order) {
                return redirect()->back()->with('error', 'Order not found. Please check your order number.');
            }

            // If user is logged in, verify they own this order
            if (Auth::guard('customer')->check()) {
                if ($order->user_id !== Auth::guard('customer')->id()) {
                    abort(403, 'Unauthorized access to order.');
                }
            }
        }

        return view('frontend.orders.tracking', compact('order', 'orderNumber'));
    }

    /**
     * Display order details (for logged-in customers)
     */
    public function show($id)
    {
        $customer = Auth::guard('customer')->user();
        $order = Order::with(['items.product.featuredImage', 'customer'])
                     ->where('id', $id)
                     ->where('user_id', $customer->id)
                     ->firstOrFail();

        return view('frontend.orders.details', compact('order'));
    }

    /**
     * Cancel an order
     */
    public function cancel($id)
    {
        $customer = Auth::guard('customer')->user();
        $order = Order::where('id', $id)
                     ->where('user_id', $customer->id)
                     ->firstOrFail();

        // Only allow cancellation if order is pending
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Order cannot be cancelled. It has already been processed.');
        }

        DB::transaction(function () use ($order) {
            // Update order status
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => 'Cancelled by customer'
            ]);

            // Restore stock quantities
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product && $product->manage_stock) {
                    $product->increment('stock_quantity', $item->quantity);
                }
            }

            // Send cancellation email
            $this->sendOrderCancellationEmail($order);
        });

        return redirect()->back()->with('success', 'Order has been cancelled successfully.');
    }

    /**
     * Reorder items from a previous order
     */
    public function reorder($id)
    {
        $customer = Auth::guard('customer')->user();
        $order = Order::with(['items.product'])
                     ->where('id', $id)
                     ->where('user_id', $customer->id)
                     ->firstOrFail();

        // Get or create customer's cart
        $cart = \App\Models\Cart::where('user_id', $customer->id)->first();
        if (!$cart) {
            $cart = \App\Models\Cart::create([
                'user_id' => $customer->id,
                'session_id' => session()->getId(),
                'total_amount' => 0,
                'total_items' => 0,
                'discount_amount' => 0,
                'shipping_amount' => 0,
                'tax_amount' => 0,
                'final_amount' => 0
            ]);
        }

        $addedItems = 0;
        $skippedItems = 0;

        foreach ($order->items as $orderItem) {
            $product = $orderItem->product;

            // Check if product is still available
            if (!$product || !$product->is_active || !$product->in_stock) {
                $skippedItems++;
                continue;
            }

            // Check if item already exists in cart
            $existingCartItem = $cart->items()->where('product_id', $product->id)->first();

            if ($existingCartItem) {
                // Update quantity
                $existingCartItem->updateQuantity($existingCartItem->quantity + $orderItem->quantity);
            } else {
                // Add new item to cart
                \App\Models\CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $orderItem->quantity,
                    'price' => $product->sale_price ?? $product->price,
                    'total_price' => ($product->sale_price ?? $product->price) * $orderItem->quantity
                ]);
            }

            $addedItems++;
        }

        $cart->calculateTotals();

        if ($addedItems > 0) {
            $message = "Successfully added {$addedItems} items to your cart.";
            if ($skippedItems > 0) {
                $message .= " {$skippedItems} items were skipped (unavailable or out of stock).";
            }
            return redirect()->route('cart.index')->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'No items could be added to cart. All items are unavailable or out of stock.');
        }
    }

    /**
     * Download order invoice
     */
    public function invoice($id)
    {
        $customer = Auth::guard('customer')->user();
        $order = Order::with(['items.product', 'customer'])
                     ->where('id', $id)
                     ->where('user_id', $customer->id)
                     ->firstOrFail();

        // Generate PDF invoice (placeholder - you can implement PDF generation)
        return view('frontend.orders.invoice', compact('order'));
    }

    /**
     * Send order confirmation email
     */
    private function sendOrderConfirmationEmail($order)
    {
        try {
            Mail::send('emails.order-confirmation', ['order' => $order], function ($message) use ($order) {
                $message->to($order->customer->email, $order->customer->first_name . ' ' . $order->customer->last_name)
                        ->subject('Order Confirmation - ' . $order->order_number);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
        }
    }

    /**
     * Send order cancellation email
     */
    private function sendOrderCancellationEmail($order)
    {
        try {
            Mail::send('emails.order-cancellation', ['order' => $order], function ($message) use ($order) {
                $message->to($order->customer->email, $order->customer->first_name . ' ' . $order->customer->last_name)
                        ->subject('Order Cancelled - ' . $order->order_number);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send order cancellation email: ' . $e->getMessage());
        }
    }

    /**
     * Get order status timeline
     */
    public function getStatusTimeline($id)
    {
        $customer = Auth::guard('customer')->user();
        $order = Order::where('id', $id)
                     ->where('user_id', $customer->id)
                     ->firstOrFail();

        $timeline = [
            [
                'status' => 'Order Placed',
                'date' => $order->created_at,
                'completed' => true,
                'description' => 'Your order has been placed successfully.'
            ],
            [
                'status' => 'Payment Confirmed',
                'date' => $order->payment_status === 'paid' ? $order->updated_at : null,
                'completed' => $order->payment_status === 'paid',
                'description' => $order->payment_status === 'paid' ? 'Payment has been confirmed.' : 'Waiting for payment confirmation.'
            ],
            [
                'status' => 'Processing',
                'date' => $order->status === 'processing' ? $order->updated_at : null,
                'completed' => in_array($order->status, ['processing', 'shipped', 'delivered', 'completed']),
                'description' => 'Your order is being prepared for shipment.'
            ],
            [
                'status' => 'Shipped',
                'date' => $order->status === 'shipped' ? $order->updated_at : null,
                'completed' => in_array($order->status, ['shipped', 'delivered', 'completed']),
                'description' => 'Your order has been shipped.'
            ],
            [
                'status' => 'Delivered',
                'date' => $order->status === 'delivered' ? $order->updated_at : null,
                'completed' => in_array($order->status, ['delivered', 'completed']),
                'description' => 'Your order has been delivered.'
            ]
        ];

        return response()->json($timeline);
    }
}
