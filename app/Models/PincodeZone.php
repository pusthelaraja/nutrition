<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PincodeZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_zone_id',
        'pincode',
        'city',
        'state',
        'country',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the shipping zone that owns the pincode.
     */
    public function shippingZone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class);
    }

    /**
     * Scope for active pincodes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific pincode.
     */
    public function scopePincode($query, $pincode)
    {
        return $query->where('pincode', $pincode);
    }
}
