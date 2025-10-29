<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating customers for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect customers after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:customer')->except('logout');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('customer');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Get the post-login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        return '/';
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Merge guest cart with customer cart if exists
        $this->mergeGuestCart($user);

        // Debug: Log the intended URL
        \Log::info('Login authenticated', [
            'intended_url' => session('url.intended'),
            'redirect_to' => $this->redirectTo,
            'redirect_path' => $this->redirectPath()
        ]);

        // Use intended redirect if available, otherwise use default
        $intended = session('url.intended');
        if ($intended) {
            \Log::info('Redirecting to intended URL: ' . $intended);
            return redirect($intended);
        }

        \Log::info('Redirecting to default path: ' . $this->redirectPath());
        return redirect($this->redirectPath());
    }

    /**
     * Merge guest cart with customer cart
     */
    private function mergeGuestCart($customer)
    {
        \Log::info('Starting cart merge process', [
            'customer_id' => $customer->id,
            'session_id' => session()->getId(),
            'stored_guest_cart_id' => session('guest_cart_id')
        ]);

        // First try to find guest cart by stored ID, then by session ID
        $guestCart = null;
        if (session('guest_cart_id')) {
            $guestCart = \App\Models\Cart::where('id', session('guest_cart_id'))
                                        ->whereNull('user_id')
                                        ->first();
            \Log::info('Found guest cart by stored ID', [
                'stored_id' => session('guest_cart_id'),
                'found' => $guestCart ? 'yes' : 'no'
            ]);
        }

        // Fallback to session ID if stored ID didn't work
        if (!$guestCart) {
            $guestCart = \App\Models\Cart::where('session_id', session()->getId())
                                        ->whereNull('user_id')
                                        ->first();
            \Log::info('Fallback: Found guest cart by session ID', [
                'session_id' => session()->getId(),
                'found' => $guestCart ? 'yes' : 'no'
            ]);
        }

        \Log::info('Guest cart found', [
            'guest_cart_exists' => $guestCart ? 'yes' : 'no',
            'guest_cart_items' => $guestCart ? $guestCart->items()->count() : 0,
            'guest_cart_id' => $guestCart ? $guestCart->id : null
        ]);

        if ($guestCart && $guestCart->items()->count() > 0) {
            $customerCart = \App\Models\Cart::where('user_id', $customer->id)->first();

            \Log::info('Customer cart status', [
                'customer_cart_exists' => $customerCart ? 'yes' : 'no',
                'customer_cart_id' => $customerCart ? $customerCart->id : null
            ]);

            if (!$customerCart) {
                // Create customer cart
                $customerCart = \App\Models\Cart::create([
                    'user_id' => $customer->id,
                    'session_id' => session()->getId(),
                    'total_amount' => 0,
                    'total_items' => 0,
                    'discount_amount' => 0,
                    'shipping_amount' => 0,
                    'tax_amount' => 0,
                    'final_amount' => 0
                ]);

                \Log::info('Created new customer cart', [
                    'new_cart_id' => $customerCart->id
                ]);
            }

            // Move guest cart items to customer cart
            $itemsMoved = 0;
            foreach ($guestCart->items as $item) {
                $existingItem = $customerCart->items()
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($existingItem) {
                    $existingItem->updateQuantity($existingItem->quantity + $item->quantity);
                    \Log::info('Updated existing item', [
                        'product_id' => $item->product_id,
                        'new_quantity' => $existingItem->quantity
                    ]);
                } else {
                    $item->update(['cart_id' => $customerCart->id]);
                    \Log::info('Moved item to customer cart', [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity
                    ]);
                }
                $itemsMoved++;
            }

            $customerCart->calculateTotals();
            $guestCart->delete();

            // Clean up stored guest cart ID
            session()->forget('guest_cart_id');

            \Log::info('Cart merge completed', [
                'items_moved' => $itemsMoved,
                'final_cart_items' => $customerCart->total_items,
                'guest_cart_deleted' => 'yes',
                'stored_cart_id_cleaned' => 'yes'
            ]);
        } else {
            \Log::info('No guest cart to merge', [
                'reason' => $guestCart ? 'empty_cart' : 'no_cart'
            ]);
        }
    }
}
