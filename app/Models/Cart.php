<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'total_amount',
        'total_items',
        'coupon_code',
        'discount_amount',
        'shipping_amount',
        'tax_amount',
        'final_amount'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }

    // Helper methods
    public function calculateTotals()
    {
        $this->total_items = $this->items->sum('quantity');
        $this->total_amount = $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        // Calculate discount
        $this->discount_amount = 0;
        if ($this->coupon_code && $this->coupon) {
            $this->discount_amount = $this->coupon->calculateDiscount($this->total_amount);
        }

        // Calculate shipping (simplified - free shipping over ₹500)
        $this->shipping_amount = $this->total_amount >= 500 ? 0 : 50;

        // Calculate tax (18% GST)
        $this->tax_amount = ($this->total_amount - $this->discount_amount) * 0.18;

        // Final amount
        $this->final_amount = $this->total_amount - $this->discount_amount + $this->shipping_amount + $this->tax_amount;

        $this->save();
    }

    public function isEmpty()
    {
        return $this->items->count() === 0;
    }

    public function clear()
    {
        $this->items()->delete();
        $this->update([
            'total_amount' => 0,
            'total_items' => 0,
            'discount_amount' => 0,
            'shipping_amount' => 0,
            'tax_amount' => 0,
            'final_amount' => 0,
            'coupon_code' => null
        ]);
    }
}
