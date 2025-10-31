<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\ShippingMethod;
use App\Models\ShippingRate;
use App\Models\WarehouseShippingRate;
use Illuminate\Support\Arr;

class ShippingService
{
    /**
     * Resolve zone id by pincode.
     * Expect a helper/pivot table shipping_zone_pincodes (zone_id, pincode) or a custom resolver.
     * For now, try simple ShippingZone::whereHas('pincodes'...) else return null.
     */
    public function resolveZoneIdByPincode(string $pincode): ?int
    {
        // If you have a ShippingZonePincode model, use it. Fallback: null.
        if (class_exists('App\\Models\\PincodeZone')) {
            $m = \App\Models\PincodeZone::where('pincode', $pincode)->active()->first();
            return $m?->shipping_zone_id;
        }
        return null;
    }

    /**
     * Compute chargeable weight in kg (DTDC style): ceil to 0.5kg slab, min 0.5kg.
     */
    public function computeChargeableWeightKg(float $actualWeightKg, ?float $volumetricWeightKg = null): float
    {
        $w = max($actualWeightKg, $volumetricWeightKg ?: 0);
        if ($w <= 0) { $w = 0.5; }
        $slabs = (int) ceil($w / 0.5);
        return $slabs * 0.5;
    }

    /**
     * Compute cart actual weight (kg). Uses product->weight if available; defaults to 0.
     */
    public function getCartActualWeightKg(Cart $cart): float
    {
        $total = 0.0;
        $cart->loadMissing('items.product');
        foreach ($cart->items as $item) {
            $w = (float) ($item->product->weight ?? 0);
            $total += $w * $item->quantity;
        }
        return $total;
    }

    /**
     * Get available methods and computed prices for a given cart and pincode.
     * Optionally pass warehouse_id to use overrides.
     */
    public function getOptionsForCart(Cart $cart, string $pincode, ?int $warehouseId = null): array
    {
        $zoneId = $this->resolveZoneIdByPincode($pincode);
        // If zone is unknown, do not fall back silently; return no options
        if (!$zoneId) {
            return [];
        }
        $actualWeight = $this->getCartActualWeightKg($cart);
        $chargeable = $this->computeChargeableWeightKg($actualWeight, null);

        $methods = ShippingMethod::query()->where('is_active', true)->get();
        $options = [];

        foreach ($methods as $method) {
            $rateRow = null;
            if ($warehouseId && class_exists(WarehouseShippingRate::class)) {
                $rateRow = WarehouseShippingRate::where('warehouse_id', $warehouseId)
                    ->where('shipping_method_id', $method->id)
                    ->where('shipping_zone_id', $zoneId)
                    ->first();
            }
            if (!$rateRow) {
                $rateRow = ShippingRate::where('shipping_method_id', $method->id)
                    ->where('shipping_zone_id', $zoneId)
                    ->first();
            }
            if (!$rateRow) { continue; }

            // Support both DTDC slab style and generic base+per_kg style
            if (isset($rateRow->base_rate)) {
                $price = $this->computePriceBasePerKg($chargeable, [
                    'base_rate'    => (float) $rateRow->base_rate,
                    'rate_per_kg'  => (float) ($rateRow->rate_per_kg ?? 0),
                    'min_charge'   => 0,
                ]);
                $tat = $rateRow->estimated_days ?? $method->default_tat_days ?? null;
            } else {
                $price = $this->computePriceFromRate($chargeable, [
                    'first_half_kg'   => (float) ($rateRow->first_half_kg ?? 0),
                    'addl_half_kg'    => (float) ($rateRow->addl_half_kg ?? 0),
                    'fuel_pct'        => (float) ($rateRow->fuel_surcharge_pct ?? 0),
                    'handling_fee'    => (float) ($rateRow->handling_fee ?? 0),
                    'min_charge'      => (float) ($rateRow->min_charge ?? 0),
                ]);
                $tat = $rateRow->tat_days ?? $method->default_tat_days ?? null;
            }

            $options[] = [
                'method_id'   => $method->id,
                'method_name' => $method->name,
                'tat_days'    => $tat,
                'price'       => round($price, 2),
                'chargeable_weight' => $chargeable,
            ];
        }

        return $options;
    }

    /**
     * Compute price using DTDC slabs + surcharges. chargeable is kg.
     */
    public function computePriceFromRate(float $chargeable, array $rate): float
    {
        $first = (float) Arr::get($rate, 'first_half_kg', 0);
        $addl  = (float) Arr::get($rate, 'addl_half_kg', 0);
        $fuel  = (float) Arr::get($rate, 'fuel_pct', 0);
        $handling = (float) Arr::get($rate, 'handling_fee', 0);
        $min = (float) Arr::get($rate, 'min_charge', 0);

        // 0.5kg slab math
        $slabs = (int) ceil($chargeable / 0.5);
        $base = 0.0;
        if ($slabs <= 1) {
            $base = $first;
        } else {
            $base = $first + ($slabs - 1) * $addl;
        }
        if ($min > 0 && $base < $min) { $base = $min; }

        $fuelAmt = $base * ($fuel / 100);
        return $base + $fuelAmt + $handling;
    }

    /** Generic base + per kg computation */
    public function computePriceBasePerKg(float $chargeable, array $rate): float
    {
        $base = (float) Arr::get($rate, 'base_rate', 0);
        $perKg = (float) Arr::get($rate, 'rate_per_kg', 0);
        // charge for weight above first kg
        $extraKg = max(0, $chargeable - 1);
        return $base + ($extraKg * $perKg);
    }
}


