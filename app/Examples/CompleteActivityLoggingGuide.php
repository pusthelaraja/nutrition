<?php

namespace App\Examples;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Services\ActivityLogService;
use Spatie\Activitylog\Models\Activity;

/**
 * Complete Guide to Activity Logging in E-commerce Admin Panel
 *
 * This guide shows how to use both:
 * 1. Spatie Laravel Activity Log Package (for automatic model logging)
 * 2. Custom ActivityLogService (for manual and specialized logging)
 */
class CompleteActivityLoggingGuide
{
    /**
     * ========================================
     * SPATIE PACKAGE USAGE (Automatic Logging)
     * ========================================
     */

    /**
     * Example 1: Automatic Model Logging with Spatie
     *
     * The LogsActivity trait automatically logs:
     * - Model creation, updates, and deletion
     * - Only dirty attributes (changed fields)
     * - Custom log names and descriptions
     */
    public function spatieAutomaticLogging()
    {
        // Create a product - automatically logged
        $product = Product::create([
            'name' => 'New Product',
            'price' => 100.00,
            'category_id' => 1
        ]);
        // Log: "Product created" with log name "products"

        // Update product - automatically logged
        $product->price = 120.00;
        $product->save();
        // Log: "Product updated" with old/new price values

        // Delete product - automatically logged
        $product->delete();
        // Log: "Product deleted"

        // Order status changes - automatically logged
        $order = Order::find(1);
        $order->status = 'processing';
        $order->save();
        // Log: "Order #12345 updated" with status change
    }

    /**
     * Example 2: Spatie Manual Activity Logging
     */
    public function spatieManualLogging()
    {
        $product = Product::find(1);
        $user = auth()->user();

        // Log custom activities
        activity()
            ->performedOn($product)
            ->causedBy($user)
            ->log('Product featured on homepage');

        // Log with properties
        activity()
            ->performedOn($product)
            ->causedBy($user)
            ->withProperties([
                'featured_date' => now(),
                'featured_by' => $user->name
            ])
            ->log('Product featured');

        // Log with specific log name
        activity('orders')
            ->performedOn(Order::find(1))
            ->causedBy($user)
            ->log('Order status manually changed');

        // System activities
        activity('system')
            ->log('System maintenance started');
    }

    /**
     * ========================================
     * CUSTOM ACTIVITY LOG SERVICE
     * ========================================
     */

    /**
     * Example 3: Custom Activity Log Service
     *
     * Use our custom service for specialized logging:
     * - Authentication events
     * - Payment processing
     * - Shipping updates
     * - System errors
     */
    public function customActivityLogging()
    {
        $user = auth()->user();
        $order = Order::find(1);

        // Authentication logging
        ActivityLogService::logAuth('logged in', $user, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        // Payment logging
        ActivityLogService::logPayment('captured', $order, [
            'payment_id' => 'pay_123456789',
            'amount' => $order->total_amount,
            'gateway' => 'razorpay'
        ]);

        // Shipping logging
        ActivityLogService::logShipping('created', $order, [
            'tracking_number' => 'DTDC123456789',
            'courier' => 'DTDC'
        ]);

        // System logging
        ActivityLogService::logSystem('Database backup completed', [
            'backup_size' => '2.5GB',
            'duration' => '15 minutes'
        ]);

        // Error logging
        ActivityLogService::logError('Payment gateway timeout', [
            'gateway' => 'razorpay',
            'timeout_duration' => '30 seconds'
        ]);
    }

    /**
     * ========================================
     * QUERYING ACTIVITY LOGS
     * ========================================
     */

    /**
     * Example 4: Querying Activity Logs
     */
    public function queryingActivityLogs()
    {
        // Spatie package queries
        $allActivities = Activity::all();
        $productActivities = Activity::forSubject(Product::find(1))->get();
        $userActivities = Activity::causedBy(User::find(1))->get();
        $orderActivities = Activity::inLog('orders')->get();

        // Custom service queries
        $filteredLogs = ActivityLogService::getLogs([
            'log_name' => 'orders',
            'event' => 'updated',
            'date_from' => now()->subDays(7)
        ]);

        $stats = ActivityLogService::getStats([
            'date_from' => now()->subMonth()
        ]);
    }

    /**
     * ========================================
     * MIDDLEWARE INTEGRATION
     * ========================================
     */

    /**
     * Example 5: Middleware for Automatic Request Logging
     */
    public function middlewareIntegration()
    {
        // Our LogActivity middleware automatically logs:
        // - All admin panel page visits
        // - Form submissions
        // - API calls
        // - User actions

        // To enable, add to routes:
        /*
        Route::middleware(['admin', 'log.activity'])->group(function () {
            Route::get('/admin/products', [ProductController::class, 'index']);
            // All these routes will be automatically logged
        });
        */
    }

    /**
     * ========================================
     * REAL-WORLD E-COMMERCE EXAMPLES
     * ========================================
     */

    /**
     * Example 6: Complete E-commerce Workflow
     */
    public function ecommerceWorkflow()
    {
        // 1. Product Management
        $product = Product::create([
            'name' => 'Premium Headphones',
            'price' => 299.99
        ]);
        // Auto-logged: "Product created"

        $product->price = 249.99;
        $product->save();
        // Auto-logged: "Product updated" with price change

        // 2. Order Processing
        $order = Order::create([
            'order_number' => 'ORD-001',
            'user_id' => 1,
            'total_amount' => 249.99
        ]);
        // Auto-logged: "Order #ORD-001 created"

        // Manual logging for specific events
        ActivityLogService::logOrder('status changed', $order, [
            'old_status' => 'pending',
            'new_status' => 'processing'
        ]);

        // 3. Payment Processing
        ActivityLogService::logPayment('initiated', $order, [
            'payment_method' => 'razorpay',
            'amount' => 249.99
        ]);

        ActivityLogService::logPayment('captured', $order, [
            'payment_id' => 'pay_123456789',
            'transaction_id' => 'txn_987654321'
        ]);

        // 4. Shipping
        ActivityLogService::logShipping('created', $order, [
            'tracking_number' => 'DTDC123456789',
            'courier' => 'DTDC',
            'service_type' => 'Express'
        ]);

        $order->status = 'shipped';
        $order->save();
        // Auto-logged: "Order #ORD-001 updated"

        // 5. Delivery
        ActivityLogService::logShipping('delivered', $order, [
            'delivery_date' => now(),
            'delivered_to' => 'John Doe'
        ]);

        $order->status = 'delivered';
        $order->save();
        // Auto-logged: "Order #ORD-001 updated"
    }

    /**
     * ========================================
     * ADMIN PANEL INTEGRATION
     * ========================================
     */

    /**
     * Example 7: Admin Panel Activity Dashboard
     */
    public function adminPanelIntegration()
    {
        // Routes are already set up:
        // GET /admin/activity-logs - View all activities
        // GET /admin/activity-logs/{id} - View specific activity
        // GET /admin/activity-logs/api/data - AJAX data
        // GET /admin/activity-logs/export - Export to CSV

        // Features available:
        // - Filter by log name, event, user, date range
        // - View activity statistics
        // - Export logs to CSV
        // - Real-time activity monitoring
        // - Search and pagination
    }

    /**
     * ========================================
     * BEST PRACTICES
     * ========================================
     */

    /**
     * Example 8: Best Practices
     */
    public function bestPractices()
    {
        // 1. Use Spatie for automatic model logging
        //    - Add LogsActivity trait to models
        //    - Configure what fields to log
        //    - Set custom log names and descriptions

        // 2. Use custom service for specialized events
        //    - Authentication events
        //    - Payment processing
        //    - Shipping updates
        //    - System events

        // 3. Use middleware for request logging
        //    - Automatic page visit logging
        //    - Performance monitoring
        //    - Security auditing

        // 4. Regular maintenance
        //    - Archive old logs
        //    - Monitor log size
        //    - Set up log rotation

        // 5. Security considerations
        //    - Don't log sensitive data (passwords, tokens)
        //    - Use appropriate log levels
        //    - Implement log access controls
    }
}
