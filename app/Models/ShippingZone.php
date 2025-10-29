<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Get the shipping rates for the zone.
     */
    public function shippingRates(): HasMany
    {
        return $this->hasMany(ShippingRate::class);
    }

    /**
     * Get the pincode zones for the zone.
     */
    public function pincodeZones(): HasMany
    {
        return $this->hasMany(PincodeZone::class);
    }

    /**
     * Get the warehouse shipping rates for the zone.
     */
    public function warehouseShippingRates(): HasMany
    {
        return $this->hasMany(WarehouseShippingRate::class);
    }

    /**
     * Scope for active zones.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
