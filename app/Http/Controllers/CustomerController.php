<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display customer dashboard
     */
    public function dashboard()
    {
        $customer = Auth::guard('customer')->user();

        // Get recent orders
        $recentOrders = Order::where('user_id', $customer->id)
                            ->with(['items.product'])
                            ->latest()
                            ->limit(5)
                            ->get();

        // Get order statistics
        $orderStats = [
            'total_orders' => Order::where('user_id', $customer->id)->count(),
            'pending_orders' => Order::where('user_id', $customer->id)->where('status', 'pending')->count(),
            'completed_orders' => Order::where('user_id', $customer->id)->where('status', 'completed')->count(),
            'total_spent' => Order::where('user_id', $customer->id)->where('status', 'completed')->sum('total_amount'),
        ];

        // Get current cart
        $cart = Cart::where('user_id', $customer->id)->with(['items.product.featuredImage'])->first();

        // Get saved addresses
        $addresses = Address::where('customer_id', $customer->id)->get();

        return view('frontend.customer.dashboard', compact('customer', 'recentOrders', 'orderStats', 'cart', 'addresses'));
    }

    /**
     * Display customer profile
     */
    public function profile()
    {
        $customer = Auth::guard('customer')->user();
        return view('frontend.customer.profile', compact('customer'));
    }

    /**
     * Update customer profile
     */
    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $customer->update($request->only([
            'first_name', 'last_name', 'email', 'phone', 'date_of_birth', 'gender'
        ]));

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update customer password
     */
    public function updatePassword(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $customer->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $customer->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    /**
     * Display customer orders
     */
    public function orders()
    {
        $customer = Auth::guard('customer')->user();
        $orders = Order::where('user_id', $customer->id)
                      ->with(['items.product'])
                      ->latest()
                      ->paginate(10);

        return view('frontend.customer.orders', compact('orders'));
    }

    /**
     * Display specific order details
     */
    public function orderDetails($id)
    {
        $customer = Auth::guard('customer')->user();
        $order = Order::where('user_id', $customer->id)
                     ->where('id', $id)
                     ->with(['items.product', 'customer'])
                     ->firstOrFail();

        return view('frontend.customer.order-details', compact('order'));
    }

    /**
     * Display customer addresses
     */
    public function addresses()
    {
        $customer = Auth::guard('customer')->user();
        $addresses = Address::where('customer_id', $customer->id)->get();

        return view('frontend.customer.addresses', compact('addresses'));
    }

    /**
     * Store new address
     */
    public function storeAddress(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        // If this is set as default, unset other defaults
        if ($request->is_default) {
            Address::where('customer_id', $customer->id)->update(['is_default' => false]);
        }

        $address = Address::create([
            'customer_id' => $customer->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'phone' => $request->phone,
            'is_default' => $request->is_default ?? false,
        ]);

        return redirect()->back()->with('success', 'Address added successfully!');
    }

    /**
     * Update address
     */
    public function updateAddress(Request $request, $id)
    {
        $customer = Auth::guard('customer')->user();
        $address = Address::where('customer_id', $customer->id)->where('id', $id)->firstOrFail();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        // If this is set as default, unset other defaults
        if ($request->is_default) {
            Address::where('customer_id', $customer->id)->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $address->update($request->only([
            'first_name', 'last_name', 'address_line_1', 'address_line_2',
            'city', 'state', 'postal_code', 'country', 'phone', 'is_default'
        ]));

        return redirect()->back()->with('success', 'Address updated successfully!');
    }

    /**
     * Delete address
     */
    public function deleteAddress($id)
    {
        $customer = Auth::guard('customer')->user();
        $address = Address::where('customer_id', $customer->id)->where('id', $id)->firstOrFail();

        $address->delete();

        return redirect()->back()->with('success', 'Address deleted successfully!');
    }

    /**
     * Display wishlist (placeholder for future implementation)
     */
    public function wishlist()
    {
        $customer = Auth::guard('customer')->user();
        // TODO: Implement wishlist functionality
        return view('frontend.customer.wishlist', compact('customer'));
    }
}
