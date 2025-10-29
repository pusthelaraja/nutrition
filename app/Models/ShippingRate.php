<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_zone_id',
        'shipping_method_id',
        'base_rate',
        'rate_per_kg',
        'free_shipping_threshold',
        'estimated_days',
        'is_active'
    ];

    protected $casts = [
        'base_rate' => 'decimal:2',
        'rate_per_kg' => 'decimal:2',
        'free_shipping_threshold' => 'decimal:2',
        'estimated_days' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get the shipping zone that owns the rate.
     */
    public function shippingZone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class);
    }

    /**
     * Get the shipping method that owns the rate.
     */
    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    /**
     * Scope for active rates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
