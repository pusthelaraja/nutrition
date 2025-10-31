<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
        'weight_kg',
        'total_price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helper methods
    public function calculateTotal()
    {
        $this->total_price = $this->quantity * $this->price;
        $this->save();
        return $this->total_price;
    }

    public function updateQuantity($quantity)
    {
        $this->quantity = max(1, $quantity);
        $this->calculateTotal();
        $this->cart->calculateTotals();
    }
}
