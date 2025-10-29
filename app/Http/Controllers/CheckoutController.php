<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index()
    {


        // Redirect to login if not authenticated
        if (!Auth::guard('customer')->check()) {
            // Get the current guest cart before redirecting
            $guestCart = Cart::where('session_id', session()->getId())
                            ->whereNull('user_id')
                            ->first();

            \Log::info('Checkout redirect to login', [
                'current_url' => request()->url(),
                'intended_url' => route('checkout.index'),
                'session_id' => session()->getId(),
                'guest_cart_id' => $guestCart ? $guestCart->id : null,
                'guest_cart_items' => $guestCart ? $guestCart->total_items : 0
            ]);

            // Store the intended URL and guest cart ID manually
            session([
                'url.intended' => route('checkout.index'),
                'guest_cart_id' => $guestCart ? $guestCart->id : null
            ]);

            \Log::info('About to redirect to login', [
                'login_route' => route('login'),
                'intended_stored' => session('url.intended'),
                'guest_cart_stored' => session('guest_cart_id')
            ]);

            return redirect()->route('login')->with('error', 'Please login to proceed with checkout.');
        }



        $customer = Auth::guard('customer')->user();
        $cart = Cart::where('user_id', $customer->id)->with(['items.product.featuredImage', 'coupon'])->first();

        if (!$cart || $cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $addresses = $customer->addresses()->get();

        // Debug: Check what we're getting
        \Log::info('CheckoutController Debug', [
            'customer_id' => $customer->id,
            'customer_name' => $customer->first_name . ' ' . $customer->last_name,
            'addresses_count' => $addresses->count(),
            'addresses_data' => $addresses->toArray(),
            'all_addresses_in_db' => \App\Models\Address::all()->toArray()
        ]);

        return view('frontend.checkout.index', compact('cart', 'addresses', 'customer'));
    }

    /**
     * Process the checkout
     */
    public function process(Request $request)
    {
        \Log::info('Checkout process started', [
            'request_data' => $request->all(),
            'is_authenticated' => Auth::guard('customer')->check(),
            'customer_id' => Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null,
            'save_address_value' => $request->input('save_address'),
            'save_address_boolean' => $request->boolean('save_address')
        ]);

        // Redirect to login if not authenticated
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login')->with('error', 'Please login to proceed with checkout.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'pincode' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'payment_method' => 'required|in:razorpay', // Only Razorpay for now
            'notes' => 'nullable|string|max:500',
            'save_address' => 'boolean',
            'address_option' => 'nullable|in:saved,new',
            'saved_address_id' => 'nullable|integer|exists:addresses,id'
        ]);

        $customer = Auth::guard('customer')->user();
        $cart = Cart::where('user_id', $customer->id)->with(['items.product', 'coupon'])->first();

        if (!$cart || $cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Create or get shipping address
        $shippingAddress = null;

        \Log::info('Address creation logic', [
            'address_option' => $request->input('address_option'),
            'saved_address_id' => $request->input('saved_address_id'),
            'save_address_raw' => $request->input('save_address'),
            'save_address_boolean' => $request->boolean('save_address'),
            'will_save_address' => $request->boolean('save_address')
        ]);

        // Check if using saved address
        if ($request->input('address_option') === 'saved' && $request->input('saved_address_id')) {
            $savedAddress = Address::where('id', $request->input('saved_address_id'))
                                  ->where('customer_id', $customer->id)
                                  ->first();

            if ($savedAddress) {
                \Log::info('Using saved address', ['address_id' => $savedAddress->id]);
                $shippingAddress = $savedAddress;
            } else {
                \Log::warning('Saved address not found or not owned by customer', [
                    'requested_id' => $request->input('saved_address_id'),
                    'customer_id' => $customer->id
                ]);
                // Fall back to creating new address
                $shippingAddress = $this->createNewAddress($request, $customer);
            }
        } else {
            // Create new address
            $shippingAddress = $this->createNewAddress($request, $customer);
        }

        // Create order
        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'ORD-' . time() . '-' . rand(1000, 9999),
            'status' => 'pending',
            'payment_status' => 'pending', // Always pending for Razorpay until payment is verified
            'payment_method' => $request->payment_method,
            'subtotal' => $cart->total_amount,
            'discount_amount' => $cart->discount_amount,
            'shipping_amount' => $cart->shipping_amount,
            'tax_amount' => $cart->tax_amount,
            'total_amount' => $cart->final_amount,
            'shipping_address' => json_encode([
                'first_name' => $shippingAddress->first_name,
                'last_name' => $shippingAddress->last_name,
                'address_line_1' => $shippingAddress->address_line_1,
                'address_line_2' => $shippingAddress->address_line_2,
                'city' => $shippingAddress->city,
                'state' => $shippingAddress->state,
                'postal_code' => $shippingAddress->postal_code,
                'country' => $shippingAddress->country,
                'phone' => $shippingAddress->phone,
            ]),
            'notes' => $request->notes,
            'coupon_code' => $cart->coupon_code,
        ]);

        // Create order items
        foreach ($cart->items as $item) {
            \Log::info('Creating order item', [
                'product_id' => $item->product_id,
                'product_name' => $item->product->name ?? 'NO NAME',
                'product_sku' => $item->product->sku ?? 'NO SKU',
                'quantity' => $item->quantity,
                'unit_price' => $item->price,
                'total_price' => $item->total_price
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'product_sku' => $item->product->sku ?? '',
                'quantity' => $item->quantity,
                'unit_price' => $item->price,
                'total_price' => $item->total_price,
                'product_attributes' => $item->product->attributes ?? []
            ]);
        }

        // Clear cart
        $cart->clear();

        // Send order confirmation email
        $this->sendOrderConfirmationEmail($order);

        // If AJAX request, return Razorpay order details for inline payment
        if ($request->expectsJson() || $request->ajax()) {
            try {
                // Create Razorpay order
                $key = config('services.razorpay.key');
                $secret = config('services.razorpay.secret');
                $currency = config('services.razorpay.currency', 'INR');

                $api = new Api($key, $secret);
                $amountPaise = (int) round(((float) $order->total_amount) * 100);

                $rzpOrder = $api->order->create([
                    'amount' => $amountPaise,
                    'currency' => $currency,
                    'receipt' => $order->order_number,
                    'payment_capture' => 1,
                    'notes' => [
                        'local_order_id' => (string) $order->id,
                    ],
                ]);

                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'razorpay' => [
                        'key_id' => $key,
                        'order_id' => $rzpOrder['id'],
                        'amount' => $amountPaise,
                        'currency' => $currency,
                        'name' => $order->customer->first_name . ' ' . $order->customer->last_name,
                        'email' => $order->customer->email,
                        'contact' => $shippingAddress->phone ?? $order->customer->phone ?? '',
                        'address' => $shippingAddress->address_line_1 ?? '',
                    ]
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to create Razorpay order', [
                    'error' => $e->getMessage(),
                    'order_id' => $order->id,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Order created but failed to initialize payment. Please try again.',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        // Redirect to Razorpay payment (fallback for non-AJAX requests)
        return redirect()->route('payment.process', $order->id);
    }

    /**
     * Show order success page
     */
    public function success($orderId)
    {
        $order = Order::with(['items.product', 'customer'])
                     ->where('id', $orderId)
                     ->where('user_id', Auth::guard('customer')->id())
                     ->firstOrFail();

        return view('frontend.checkout.success', compact('order'));
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
     * Show Razorpay payment page for an order (supports AJAX for inline payment)
     */
    public function payment(Order $order)
    {
        // Load customer relationship
        $order->load('customer');

        // Ensure the user owns the order
        if (!Auth::guard('customer')->check() || $order->user_id !== Auth::guard('customer')->id()) {
            abort(403);
        }

        if ($order->payment_status === 'paid') {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This order is already paid.',
                    'redirect' => route('orders.confirmation', $order->id)
                ]);
            }
            return redirect()->route('orders.confirmation', $order->id);
        }

        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');
        $currency = config('services.razorpay.currency', 'INR');

        // Create a Razorpay order
        $api = new Api($key, $secret);
        $amountPaise = (int) round(((float) $order->total_amount) * 100);

        try {
            $rzpOrder = $api->order->create([
                'amount' => $amountPaise,
                'currency' => $currency,
                'receipt' => $order->order_number,
                'payment_capture' => 1,
                'notes' => [
                    'local_order_id' => (string) $order->id,
                ],
            ]);
        } catch (\Throwable $e) {
            \Log::error('Failed to create Razorpay order', [
                'error' => $e->getMessage(),
            ]);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to initiate payment. Please try again.',
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->route('cart.index')->with('error', 'Unable to initiate payment. Please try again.');
        }

        $razorpayOrderId = $rzpOrder['id'] ?? null;

        // Get shipping address for prefill
        $shippingAddress = null;
        if ($order->shipping_address) {
            $shippingAddress = is_string($order->shipping_address)
                ? json_decode($order->shipping_address, true)
                : $order->shipping_address;
        }

        // If AJAX request, return JSON for inline payment
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'razorpay' => [
                    'key_id' => $key,
                    'order_id' => $razorpayOrderId,
                    'amount' => $amountPaise,
                    'currency' => $currency,
                    'name' => $order->customer->first_name . ' ' . $order->customer->last_name,
                    'email' => $order->customer->email,
                    'contact' => $shippingAddress['phone'] ?? $order->customer->phone ?? '',
                    'address' => $shippingAddress['address_line_1'] ?? '',
                ]
            ]);
        }

        // Return view for non-AJAX requests
        return view('frontend.checkout.payment', [
            'order' => $order,
            'razorpayKey' => $key,
            'razorpayOrderId' => $razorpayOrderId,
            'amountPaise' => $amountPaise,
            'currency' => $currency,
        ]);
    }

    /**
     * Create Razorpay order via AJAX (optional helper)
     */
    public function createRazorpayOrder(Order $order)
    {
        if (!Auth::guard('customer')->check() || $order->user_id !== Auth::guard('customer')->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');
        $currency = config('services.razorpay.currency', 'INR');

        $api = new Api($key, $secret);
        $amountPaise = (int) round(((float) $order->total_amount) * 100);

        try {
            $rzpOrder = $api->order->create([
                'amount' => $amountPaise,
                'currency' => $currency,
                'receipt' => $order->order_number,
                'payment_capture' => 1,
                'notes' => [
                    'local_order_id' => (string) $order->id,
                ],
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $rzpOrder['id'] ?? null,
                'amount' => $amountPaise,
                'currency' => $currency,
            ]);
        } catch (\Throwable $e) {
            \Log::error('Failed to create Razorpay order (AJAX)', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to create payment order'], 500);
        }
    }

    /**
     * Verify Razorpay payment signature and mark order paid
     */
    public function verifyRazorpayPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $order = Order::findOrFail($request->input('order_id'));
        if (!Auth::guard('customer')->check() || $order->user_id !== Auth::guard('customer')->id()) {
            abort(403);
        }

        $secret = config('services.razorpay.secret');
        $generatedSignature = hash_hmac(
            'sha256',
            $request->input('razorpay_order_id') . '|' . $request->input('razorpay_payment_id'),
            $secret
        );

        if (hash_equals($generatedSignature, $request->input('razorpay_signature'))) {
            $order->payment_status = 'paid';
            if ($order->status === 'pending') {
                $order->status = 'processing';
            }
            $order->payment_method = 'razorpay';

            // Append payment details to notes without relying on extra columns
            $notes = trim((string) $order->notes);
            $paymentNote = ' | RZP: ' . $request->input('razorpay_payment_id') . ' / ' . $request->input('razorpay_order_id');
            $order->notes = $notes ? ($notes . $paymentNote) : ltrim($paymentNote, ' |');
            $order->save();

            // Email confirmation (already sent after order create for COD; send again on payment success if needed)
            try {
                $this->sendOrderConfirmationEmail($order);
            } catch (\Throwable $e) {
                \Log::warning('Order confirmation email after payment failed: ' . $e->getMessage());
            }

            return redirect()->route('orders.confirmation', $order->id)->with('success', 'Payment successful');
        }

        return redirect()->back()->with('error', 'Payment verification failed. Please try again.');
    }

    /**
     * Create new address helper method
     */
    private function createNewAddress(Request $request, $customer)
    {
        if ($request->boolean('save_address')) {
            \Log::info('Creating new address for customer', [
                'customer_id' => $customer->id,
                'address_data' => [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'address_line_1' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'postal_code' => $request->pincode,
                    'country' => $request->country,
                    'phone' => $request->phone,
                ]
            ]);

            try {
                // Create new address and save it
                $address = Address::create([
                    'customer_id' => $customer->id,
                    'type' => 'shipping',
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'address_line_1' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'postal_code' => $request->pincode,
                    'country' => $request->country,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'is_default' => false
                ]);

                \Log::info('Address created successfully', [
                    'address_id' => $address->id,
                    'customer_id' => $address->customer_id
                ]);

                return $address;
            } catch (\Exception $e) {
                \Log::error('Failed to create address', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        } else {
            \Log::info('Creating temporary address object (not saving to DB)');
            // Create temporary address object for order
            $address = new \stdClass();
            $address->first_name = $request->first_name;
            $address->last_name = $request->last_name;
            $address->address_line_1 = $request->address;
            $address->address_line_2 = '';
            $address->city = $request->city;
            $address->state = $request->state;
            $address->postal_code = $request->pincode;
            $address->country = $request->country;
            $address->phone = $request->phone;
            $address->email = $request->email;

            return $address;
        }
    }
}
