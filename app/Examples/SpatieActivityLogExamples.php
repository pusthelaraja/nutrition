<?php

namespace App\Examples;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Examples of using Spatie Laravel Activity Log Package
 */
class SpatieActivityLogExamples
{
    /**
     * Example 1: Basic Activity Logging with Spatie
     */
    public function basicLogging()
    {
        // Log a simple activity
        activity()
            ->log('User viewed dashboard')
            ->causedBy(auth()->user())
            ->withProperties(['page' => 'dashboard'])
            ->save();
    }

    /**
     * Example 2: Using the LogsActivity Trait
     */
    public function traitLogging()
    {
        // Add the trait to your models
        // use Spatie\Activitylog\Traits\LogsActivity;

        $product = new Product();
        $product->name = 'New Product';
        $product->price = 100.00;
        $product->save(); // This will automatically log the creation

        $product->price = 120.00;
        $product->save(); // This will automatically log the update

        $product->delete(); // This will automatically log the deletion
    }

    /**
     * Example 3: Custom Activity Logging
     */
    public function customLogging()
    {
        $product = Product::find(1);

        // Log custom activities
        activity()
            ->performedOn($product)
            ->causedBy(auth()->user())
            ->log('Product featured on homepage');

        activity()
            ->performedOn($product)
            ->causedBy(auth()->user())
            ->withProperties(['featured_date' => now()])
            ->log('Product featured');
    }

    /**
     * Example 4: Order Management Logging
     */
    public function orderLogging()
    {
        $order = Order::find(1);

        // Log order status changes
        activity()
            ->performedOn($order)
            ->causedBy(auth()->user())
            ->withProperties([
                'old_status' => 'pending',
                'new_status' => 'processing'
            ])
            ->log('Order status changed');

        // Log payment activities
        activity()
            ->performedOn($order)
            ->causedBy(auth()->user())
            ->withProperties([
                'payment_id' => 'pay_123456789',
                'amount' => $order->total_amount
            ])
            ->log('Payment captured');
    }

    /**
     * Example 5: Querying Activity Logs
     */
    public function queryingLogs()
    {
        // Get all activities
        $activities = Activity::all();

        // Get activities for a specific model
        $productActivities = Activity::forSubject(Product::find(1))->get();

        // Get activities caused by a specific user
        $userActivities = Activity::causedBy(User::find(1))->get();

        // Get activities with specific log name
        $orderActivities = Activity::inLog('orders')->get();

        // Get recent activities
        $recentActivities = Activity::latest()->take(10)->get();
    }

    /**
     * Example 6: Using Log Names
     */
    public function logNames()
    {
        // Log with specific log name
        activity('orders')
            ->performedOn(Order::find(1))
            ->causedBy(auth()->user())
            ->log('Order created');

        activity('payments')
            ->performedOn(Order::find(1))
            ->causedBy(auth()->user())
            ->log('Payment processed');

        activity('system')
            ->log('System maintenance started');
    }

    /**
     * Example 7: Batch Logging
     */
    public function batchLogging()
    {
        // Create a batch of activities
        $batch = activity()
            ->useLog('bulk_operations')
            ->causedBy(auth()->user());

        $products = Product::all();

        foreach ($products as $product) {
            $batch->performedOn($product)
                  ->log('Bulk price update');
        }

        $batch->save();
    }

    /**
     * Example 8: Model Configuration
     */
    public function modelConfiguration()
    {
        // In your model, you can configure what gets logged
        /*
        class Product extends Model
        {
            use LogsActivity;

            protected static $logAttributes = ['name', 'price', 'status'];
            protected static $logOnlyDirty = true;
            protected static $logName = 'products';
            protected static $submitEmptyLogs = false;

            public function getDescriptionForEvent(string $eventName): string
            {
                return "Product {$eventName}";
            }
        }
        */
    }

    /**
     * Example 9: Custom Activity Model
     */
    public function customActivityModel()
    {
        // You can extend the Activity model
        /*
        class CustomActivity extends Activity
        {
            protected $table = 'activity_logs';

            public function getFormattedDescriptionAttribute()
            {
                return "{$this->causer->name} {$this->description}";
            }
        }
        */
    }

    /**
     * Example 10: Middleware Integration
     */
    public function middlewareIntegration()
    {
        // Create middleware to automatically log requests
        /*
        class LogActivityMiddleware
        {
            public function handle($request, Closure $next)
            {
                $response = $next($request);

                if (auth()->check()) {
                    activity()
                        ->causedBy(auth()->user())
                        ->withProperties([
                            'url' => $request->fullUrl(),
                            'method' => $request->method(),
                            'ip' => $request->ip()
                        ])
                        ->log('Page visited');
                }

                return $response;
            }
        }
        */
    }
}
