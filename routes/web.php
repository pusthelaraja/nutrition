<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Debug route for cart testing
Route::get('/debug-cart', function() {
    return response()->json([
        'message' => 'Cart debug route working',
        'products_count' => \App\Models\Product::count(),
        'carts_count' => \App\Models\Cart::count(),
        'cart_items_count' => \App\Models\CartItem::count()
    ]);
});

// Test route to check authentication status
Route::get('/test-auth', function() {
    $cart = null;
    if (Auth::guard('customer')->check()) {
        $cart = App\Models\Cart::where('user_id', Auth::guard('customer')->id())->first();
    } else {
        $cart = App\Models\Cart::where('session_id', session()->getId())->whereNull('user_id')->first();
    }

    return response()->json([
        'is_logged_in' => Auth::guard('customer')->check(),
        'customer' => Auth::guard('customer')->user() ? [
            'id' => Auth::guard('customer')->user()->id,
            'email' => Auth::guard('customer')->user()->email,
        ] : null,
        'session_id' => session()->getId(),
        'intended_url' => session('url.intended'),
        'cart_exists' => $cart ? 'yes' : 'no',
        'cart_items' => $cart ? $cart->total_items : 0,
    ]);
});

// Frontend Routes
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Debug route to check product images
Route::get('/debug-products', function() {
    $products = \App\Models\Product::with(['images', 'featuredImage'])->get();
    $debug = [];

    foreach($products as $product) {
        $debug[] = [
            'id' => $product->id,
            'name' => $product->name,
            'featured_image_path' => $product->featured_image,
            'featured_image_url' => $product->featured_image_url,
            'images_count' => $product->images->count(),
            'images' => $product->images->pluck('image_path')->toArray()
        ];
    }

    return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
});

// Debug route to check file manager directories
Route::get('/debug-filemanager', function() {
    $directories = [
        'shares' => [
            'path' => storage_path('app/public/files/shares'),
            'exists' => is_dir(storage_path('app/public/files/shares')),
            'files' => is_dir(storage_path('app/public/files/shares')) ?
                array_diff(scandir(storage_path('app/public/files/shares')), ['.', '..']) : []
        ],
        'user_products' => [
            'path' => storage_path('app/public/files/1/products'),
            'exists' => is_dir(storage_path('app/public/files/1/products')),
            'files' => is_dir(storage_path('app/public/files/1/products')) ?
                array_diff(scandir(storage_path('app/public/files/1/products')), ['.', '..']) : []
        ],
        'user_photos' => [
            'path' => storage_path('app/public/photos/1/products'),
            'exists' => is_dir(storage_path('app/public/photos/1/products')),
            'files' => is_dir(storage_path('app/public/photos/1/products')) ?
                array_diff(scandir(storage_path('app/public/photos/1/products')), ['.', '..']) : []
        ]
    ];

    return response()->json($directories, 200, [], JSON_PRETTY_PRINT);
});

Route::get('/test-image-picker', function() {
    return view('test-image-picker');
});
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.remove-coupon');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::get('/checkout/shipping-options', [CheckoutController::class, 'shippingOptions'])->name('checkout.shipping-options');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::post('/checkout/enquiry', [CheckoutController::class, 'enquiry'])->name('checkout.enquiry');
Route::get('/order/success/{order}', [CheckoutController::class, 'success'])->name('order.success');

// Razorpay payment routes
Route::middleware(['auth:customer'])->group(function () {
    Route::get('/payment/{order}', [CheckoutController::class, 'payment'])->name('payment.process');
    Route::post('/payment/razorpay/create-order/{order}', [CheckoutController::class, 'createRazorpayOrder'])->name('payment.razorpay.create');
    Route::post('/payment/razorpay/verify', [CheckoutController::class, 'verifyRazorpayPayment'])->name('payment.razorpay.verify');
});

// Order Management Routes
Route::get('/orders/confirmation/{order}', [App\Http\Controllers\OrderController::class, 'confirmation'])->name('orders.confirmation');
Route::get('/orders/tracking', [App\Http\Controllers\OrderController::class, 'tracking'])->name('orders.tracking');
Route::get('/orders/{id}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
Route::post('/orders/{id}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
Route::post('/orders/{id}/reorder', [App\Http\Controllers\OrderController::class, 'reorder'])->name('orders.reorder');
Route::get('/orders/{id}/invoice', [App\Http\Controllers\OrderController::class, 'invoice'])->name('orders.invoice');

// Customer Dashboard Routes (Protected)
Route::middleware(['auth:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\CustomerController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\CustomerController::class, 'updateProfile'])->name('update-profile');
    Route::put('/password', [App\Http\Controllers\CustomerController::class, 'updatePassword'])->name('update-password');
    Route::get('/orders', [App\Http\Controllers\CustomerController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [App\Http\Controllers\CustomerController::class, 'orderDetails'])->name('order-details');
    Route::get('/addresses', [App\Http\Controllers\CustomerController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [App\Http\Controllers\CustomerController::class, 'storeAddress'])->name('store-address');
    Route::put('/addresses/{id}', [App\Http\Controllers\CustomerController::class, 'updateAddress'])->name('update-address');
    Route::delete('/addresses/{id}', [App\Http\Controllers\CustomerController::class, 'deleteAddress'])->name('delete-address');
    Route::get('/wishlist', [App\Http\Controllers\CustomerController::class, 'wishlist'])->name('wishlist');
});

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/search', function() {
    return view('frontend.search');
})->name('search');

// Admin Authentication Routes (no middleware to prevent redirects)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
    Route::get('/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'register']);
    Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');
});

// Test route to debug admin login
Route::get('/admin-test', function() {
    return 'Admin test route works!';
});

// Protected Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Categories - Super Admin bypass or permission check
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)->middleware(['superadmin', 'permission:view-categories|create-categories|edit-categories|delete-categories']);

    // Products - Super Admin bypass or permission check
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class)->middleware(['superadmin', 'permission:view-products|create-products|edit-products|delete-products']);

    // Orders - Super Admin bypass or permission check
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->middleware(['superadmin', 'permission:view-orders|create-orders|edit-orders|delete-orders']);

    // Shipping - Super Admin bypass or permission check
    Route::resource('shipping', App\Http\Controllers\Admin\ShippingController::class)->middleware(['superadmin', 'permission:view-shipping|create-shipping|edit-shipping|delete-shipping']);

    // Coupons - Super Admin bypass or permission check
    Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class)->middleware(['superadmin', 'permission:view-coupons|create-coupons|edit-coupons|delete-coupons']);

    // User Management - Super Admin bypass or permission check
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->middleware(['superadmin', 'permission:view-users|create-users|edit-users|delete-users']);

    // Roles - Super Admin bypass or permission check
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class)->middleware(['superadmin', 'permission:view-roles|create-roles|edit-roles|delete-roles']);

    // Permissions - Super Admin bypass or permission check
    Route::resource('permissions', App\Http\Controllers\Admin\PermissionController::class)->middleware(['superadmin', 'permission:view-permissions|create-permissions|edit-permissions|delete-permissions']);

    // Coupon Management Routes
    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::post('/{coupon}/toggle-status', [App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/{coupon}/statistics', [App\Http\Controllers\Admin\CouponController::class, 'statistics'])->name('statistics');
    });

    // Shipping Management Routes
    Route::prefix('shipping')->name('shipping.')->group(function () {
        Route::get('/{shipping}/rates', [App\Http\Controllers\Admin\ShippingController::class, 'rates'])->name('rates');
        Route::get('/{shipping}/pincodes', [App\Http\Controllers\Admin\ShippingController::class, 'pincodes'])->name('pincodes');
        Route::post('/{shipping}/pincodes', [App\Http\Controllers\Admin\ShippingController::class, 'addPincode'])->name('add-pincode');
        Route::delete('/pincodes/{pincode}', [App\Http\Controllers\Admin\ShippingController::class, 'removePincode'])->name('remove-pincode');

        // Shipping rates management
        Route::post('/{shipping}/rates', [App\Http\Controllers\Admin\ShippingController::class, 'storeRate'])->name('rates.store');
        Route::put('/rates/{rate}', [App\Http\Controllers\Admin\ShippingController::class, 'updateRate'])->name('rates.update');
        Route::delete('/rates/{rate}', [App\Http\Controllers\Admin\ShippingController::class, 'destroyRate'])->name('rates.destroy');
    });

    // File Manager Routes - Super Admin bypass or permission check
    Route::get('/file-manager', [App\Http\Controllers\Admin\FileManagerController::class, 'index'])->name('file-manager')->middleware(['superadmin', 'permission:view-file-manager']);
    Route::get('/file-manager/test', function() {
        return view('admin.file-manager.test');
    })->name('file-manager.test')->middleware(['superadmin', 'permission:view-file-manager']);
    Route::get('/file-manager/diagnostics', [App\Http\Controllers\Admin\FileManagerTestController::class, 'index'])->name('file-manager.diagnostics')->middleware(['superadmin', 'permission:view-file-manager']);
    Route::post('/file-manager/test-upload', [App\Http\Controllers\Admin\FileManagerTestController::class, 'testUpload'])->name('file-manager.test-upload')->middleware(['superadmin', 'permission:view-file-manager']);
    Route::get('/products/image-picker-test', function() {
        return view('admin.products.image-picker-test');
    })->name('products.image-picker-test')->middleware(['superadmin', 'permission:view-file-manager']);
    Route::get('/products/file-manager-verification', function() {
        return view('admin.products.file-manager-verification');
    })->name('products.file-manager-verification')->middleware(['superadmin', 'permission:view-file-manager']);
    Route::post('/admin/upload-image', [App\Http\Controllers\Admin\FileManagerController::class, 'uploadImage'])->name('admin.upload-image')->middleware(['superadmin', 'permission:view-file-manager']);

    // Activity Logs Routes (using Spatie package) - Super Admin bypass or permission check
    Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', function() {
            $activities = \Spatie\Activitylog\Models\Activity::with(['subject', 'causer'])->latest()->paginate(20);
            return view('admin.activity-logs.index', compact('activities'));
        })->name('index');

        Route::get('/{activity}', function(\Spatie\Activitylog\Models\Activity $activity) {
            return view('admin.activity-logs.show', compact('activity'));
        })->name('show');
    });

    // Inventory Management Routes - Super Admin bypass or permission check
    Route::prefix('inventory')->name('inventory.')->middleware(['superadmin', 'permission:view-inventory'])->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('index');
        Route::get('/{product}', [App\Http\Controllers\Admin\InventoryController::class, 'show'])->name('show');
        Route::post('/{product}/adjust-stock', [App\Http\Controllers\Admin\InventoryController::class, 'adjustStock'])->name('adjust-stock')->middleware(['superadmin', 'permission:manage-inventory']);
        Route::post('/generate-reports', [App\Http\Controllers\Admin\InventoryController::class, 'generateReports'])->name('generate-reports')->middleware(['superadmin', 'permission:view-stock-reports']);
        Route::get('/export', [App\Http\Controllers\Admin\InventoryController::class, 'export'])->name('export')->middleware(['superadmin', 'permission:view-stock-reports']);
    });

    // Contact Leads
    Route::resource('contact-leads', App\Http\Controllers\Admin\ContactLeadController::class)->middleware(['superadmin']);
});

// Laravel File Manager Routes
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'admin']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});


// Customer Authentication Routes
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
});

// Redirect old order confirmation URL
Route::get('/order-confirmation', function() {
    return redirect()->route('home')->with('info', 'Please complete your order to view confirmation page.');
});

// Simplified customer auth routes (without prefix)
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Auth::routes();

// Laravel's default home route (for authenticated users)
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
