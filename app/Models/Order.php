<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Order extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'total_amount',
        'payment_status',
        'payment_method',
        'shipping_address',
        'notes',
        'shipped_at',
        'delivered_at',
        // Razorpay fields
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'razorpay_status',
        'razorpay_response',
        // DTDC fields
        'dtdc_awb_number',
        'dtdc_consignment_number',
        'dtdc_status',
        'dtdc_pickup_date',
        'dtdc_delivery_date',
        'dtdc_tracking_details'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipping_address' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'razorpay_response' => 'array',
        'dtdc_pickup_date' => 'datetime',
        'dtdc_delivery_date' => 'datetime',
        'dtdc_tracking_details' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Activity log configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'payment_status', 'total_amount', 'shipped_at', 'delivered_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('orders');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Order #{$this->order_number} {$eventName}";
    }
}
