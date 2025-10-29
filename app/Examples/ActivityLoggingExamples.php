<?php

namespace App\Examples;

use App\Services\ActivityLogService;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

/**
 * Examples of how to use the Activity Logging System
 */
class ActivityLoggingExamples
{
    /**
     * Example 1: Basic Activity Logging
     */
    public function basicLogging()
    {
        // Log a simple activity
        ActivityLogService::log(
            'User viewed dashboard',
            null, // no subject
            auth()->user(), // causer
            'view', // event
            'dashboard', // log name
            ['page' => 'dashboard'] // properties
        );
    }

    /**
     * Example 2: CRUD Operations Logging
     */
    public function crudLogging()
    {
        $product = Product::find(1);

        // Log when product is created
        ActivityLogService::logCrud('created', $product, [
            'product_name' => $product->name,
            'price' => $product->price
        ]);

        // Log when product is updated
        ActivityLogService::logCrud('updated', $product, [
            'old_price' => $product->getOriginal('price'),
            'new_price' => $product->price
        ]);

        // Log when product is deleted
        ActivityLogService::logCrud('deleted', $product, [
            'product_name' => $product->name
        ]);
    }

    /**
     * Example 3: Authentication Logging
     */
    public function authLogging()
    {
        $user = User::find(1);

        // Log user login
        ActivityLogService::logAuth('logged in', $user, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        // Log user logout
        ActivityLogService::logAuth('logged out', $user);

        // Log failed login attempt
        ActivityLogService::logAuth('failed login attempt', null, [
            'email' => 'user@example.com',
            'ip_address' => request()->ip()
        ]);
    }

    /**
     * Example 4: Order Management Logging
     */
    public function orderLogging()
    {
        $order = Order::find(1);

        // Log order creation
        ActivityLogService::logOrder('created', $order, [
            'order_number' => $order->order_number,
            'total_amount' => $order->total_amount
        ]);

        // Log order status change
        ActivityLogService::logOrder('status changed', $order, [
            'old_status' => 'pending',
            'new_status' => 'processing'
        ]);

        // Log order cancellation
        ActivityLogService::logOrder('cancelled', $order, [
            'reason' => 'Customer request'
        ]);
    }

    /**
     * Example 5: Payment Logging
     */
    public function paymentLogging()
    {
        $order = Order::find(1);

        // Log payment success
        ActivityLogService::logPayment('captured', $order, [
            'payment_id' => 'pay_123456789',
            'amount' => $order->total_amount,
            'gateway' => 'razorpay'
        ]);

        // Log payment failure
        ActivityLogService::logPayment('failed', $order, [
            'error_code' => 'CARD_DECLINED',
            'error_message' => 'Insufficient funds'
        ]);

        // Log refund
        ActivityLogService::logPayment('refunded', $order, [
            'refund_id' => 'rfnd_123456789',
            'refund_amount' => 100.00
        ]);
    }

    /**
     * Example 6: Shipping Logging
     */
    public function shippingLogging()
    {
        $order = Order::find(1);

        // Log shipment creation
        ActivityLogService::logShipping('created', $order, [
            'tracking_number' => 'DTDC123456789',
            'courier' => 'DTDC',
            'service_type' => 'Express'
        ]);

        // Log shipment status update
        ActivityLogService::logShipping('status updated', $order, [
            'old_status' => 'in_transit',
            'new_status' => 'delivered',
            'delivery_date' => now()->format('Y-m-d')
        ]);
    }

    /**
     * Example 7: System Logging
     */
    public function systemLogging()
    {
        // Log system maintenance
        ActivityLogService::logSystem('System maintenance started', [
            'maintenance_type' => 'database_optimization',
            'estimated_duration' => '2 hours'
        ]);

        // Log error
        ActivityLogService::logError('Database connection failed', [
            'error_code' => 'DB_CONNECTION_FAILED',
            'host' => 'localhost',
            'database' => 'laravel'
        ]);

        // Log critical system event
        ActivityLogService::logSystem('Critical system alert', [
            'alert_type' => 'high_cpu_usage',
            'cpu_percentage' => 95
        ], 'critical');
    }

    /**
     * Example 8: Using the LogsActivity Trait
     */
    public function traitLogging()
    {
        // Add the trait to your models
        // use App\Traits\LogsActivity;

        $product = new Product();
        $product->name = 'New Product';
        $product->price = 100.00;
        $product->save(); // This will automatically log the creation

        $product->price = 120.00;
        $product->save(); // This will automatically log the update

        $product->delete(); // This will automatically log the deletion

        // Custom activity logging
        $product->logActivity('Product featured on homepage', 'featured', [
            'featured_date' => now()->format('Y-m-d')
        ]);
    }

    /**
     * Example 9: Querying Activity Logs
     */
    public function queryingLogs()
    {
        // Get all logs for a specific user
        $userLogs = ActivityLogService::getLogs(['causer_id' => 1]);

        // Get all logs for a specific model
        $productLogs = ActivityLogService::getLogs(['subject_type' => Product::class]);

        // Get all error logs
        $errorLogs = ActivityLogService::getLogs(['log_level' => 'error']);

        // Get logs for a specific date range
        $recentLogs = ActivityLogService::getLogs([
            'date_from' => now()->subDays(7),
            'date_to' => now()
        ]);

        // Get statistics
        $stats = ActivityLogService::getStats([
            'date_from' => now()->subMonth()
        ]);
    }

    /**
     * Example 10: Middleware Integration
     */
    public function middlewareIntegration()
    {
        // The LogActivity middleware automatically logs:
        // - All admin panel activities
        // - User actions
        // - Page views
        // - Form submissions
        // - API calls

        // To enable, add to your routes:
        // Route::middleware(['admin', 'log.activity'])->group(function () {
        //     // Your admin routes
        // });
    }
}
