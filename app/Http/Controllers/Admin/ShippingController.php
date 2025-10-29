<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use App\Models\ShippingRate;
use App\Models\PincodeZone;
use App\Models\Warehouse;
use App\Models\WarehouseShippingRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShippingController extends Controller
{
    /**
     * Display a listing of shipping zones.
     */
    public function index()
    {
        try {
            $shippingZones = ShippingZone::with(['shippingRates', 'pincodeZones'])
                ->orderBy('sort_order')
                ->paginate(15);

            return view('admin.shipping.index', compact('shippingZones'));
        } catch (\Exception $e) {
            \Log::error('Shipping zones listing failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load shipping zones.');
        }
    }

    /**
     * Show the form for creating a new shipping zone.
     */
    public function create()
    {
        return view('admin.shipping.create');
    }

    /**
     * Store a newly created shipping zone.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'is_active' => 'boolean',
                'sort_order' => 'nullable|integer|min:0'
            ]);

            ShippingZone::create([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
                'sort_order' => $request->sort_order ?? 0
            ]);

            return redirect()->route('admin.shipping.index')
                ->with('success', 'Shipping zone created successfully.');

        } catch (\Exception $e) {
            \Log::error('Shipping zone creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create shipping zone.');
        }
    }

    /**
     * Display the specified shipping zone.
     */
    public function show(ShippingZone $shipping)
    {
        try {
            $shipping->load(['shippingRates', 'pincodeZones']);
            return view('admin.shipping.show', compact('shipping'));
        } catch (\Exception $e) {
            \Log::error('Shipping zone details failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load shipping zone details.');
        }
    }

    /**
     * Show the form for editing the specified shipping zone.
     */
    public function edit(ShippingZone $shipping)
    {
        return view('admin.shipping.edit', compact('shipping'));
    }

    /**
     * Update the specified shipping zone.
     */
    public function update(Request $request, ShippingZone $shipping)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'is_active' => 'boolean',
                'sort_order' => 'nullable|integer|min:0'
            ]);

            $shipping->update([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
                'sort_order' => $request->sort_order ?? 0
            ]);

            return redirect()->route('admin.shipping.index')
                ->with('success', 'Shipping zone updated successfully.');

        } catch (\Exception $e) {
            \Log::error('Shipping zone update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update shipping zone.');
        }
    }

    /**
     * Remove the specified shipping zone.
     */
    public function destroy(ShippingZone $shipping)
    {
        try {
            // Check if zone has associated rates or pincodes
            if ($shipping->shippingRates()->count() > 0 || $shipping->pincodeZones()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete shipping zone with associated rates or pincodes.');
            }

            $shipping->delete();

            return redirect()->route('admin.shipping.index')
                ->with('success', 'Shipping zone deleted successfully.');

        } catch (\Exception $e) {
            \Log::error('Shipping zone deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete shipping zone.');
        }
    }

    /**
     * Manage shipping rates for a zone.
     */
    public function rates(ShippingZone $shipping)
    {
        try {
            $rates = $shipping->shippingRates()->get();
            return view('admin.shipping.rates', compact('shipping', 'rates'));
        } catch (\Exception $e) {
            \Log::error('Shipping rates failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load shipping rates.');
        }
    }

    /**
     * Manage pincodes for a zone.
     */
    public function pincodes(ShippingZone $shipping)
    {
        try {
            $pincodes = $shipping->pincodeZones()->paginate(20);
            return view('admin.shipping.pincodes', compact('shipping', 'pincodes'));
        } catch (\Exception $e) {
            \Log::error('Shipping pincodes failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load pincodes.');
        }
    }

    /**
     * Add pincode to zone.
     */
    public function addPincode(Request $request, ShippingZone $shipping)
    {
        try {
            $request->validate([
                'pincode' => 'required|string|max:10',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'country' => 'required|string|max:100',
                'is_active' => 'boolean'
            ]);

            PincodeZone::create([
                'shipping_zone_id' => $shipping->id,
                'pincode' => $request->pincode,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'is_active' => $request->has('is_active')
            ]);

            return redirect()->back()
                ->with('success', 'Pincode added successfully.');

        } catch (\Exception $e) {
            \Log::error('Pincode addition failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add pincode.');
        }
    }

    /**
     * Remove pincode from zone.
     */
    public function removePincode(PincodeZone $pincode)
    {
        try {
            $pincode->delete();
            return redirect()->back()
                ->with('success', 'Pincode removed successfully.');
        } catch (\Exception $e) {
            \Log::error('Pincode removal failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to remove pincode.');
        }
    }

    /**
     * Store a new shipping rate.
     */
    public function storeRate(Request $request, ShippingZone $shipping)
    {
        try {
            $request->validate([
                'shipping_method_id' => 'required|integer',
                'base_rate' => 'required|numeric|min:0',
                'rate_per_kg' => 'nullable|numeric|min:0',
                'free_shipping_threshold' => 'nullable|numeric|min:0',
                'estimated_days' => 'required|integer|min:1',
                'is_active' => 'boolean'
            ]);

            ShippingRate::create([
                'shipping_zone_id' => $shipping->id,
                'shipping_method_id' => $request->shipping_method_id,
                'base_rate' => $request->base_rate,
                'rate_per_kg' => $request->rate_per_kg ?? 0,
                'free_shipping_threshold' => $request->free_shipping_threshold ?? 0,
                'estimated_days' => $request->estimated_days,
                'is_active' => $request->has('is_active')
            ]);

            return redirect()->back()
                ->with('success', 'Shipping rate added successfully.');

        } catch (\Exception $e) {
            \Log::error('Shipping rate creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add shipping rate.');
        }
    }

    /**
     * Update a shipping rate.
     */
    public function updateRate(Request $request, ShippingRate $rate)
    {
        try {
            $request->validate([
                'base_rate' => 'required|numeric|min:0',
                'rate_per_kg' => 'nullable|numeric|min:0',
                'free_shipping_threshold' => 'nullable|numeric|min:0',
                'estimated_days' => 'required|integer|min:1',
                'is_active' => 'boolean'
            ]);

            $rate->update([
                'base_rate' => $request->base_rate,
                'rate_per_kg' => $request->rate_per_kg ?? 0,
                'free_shipping_threshold' => $request->free_shipping_threshold ?? 0,
                'estimated_days' => $request->estimated_days,
                'is_active' => $request->has('is_active')
            ]);

            return redirect()->back()
                ->with('success', 'Shipping rate updated successfully.');

        } catch (\Exception $e) {
            \Log::error('Shipping rate update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update shipping rate.');
        }
    }

    /**
     * Delete a shipping rate.
     */
    public function destroyRate(ShippingRate $rate)
    {
        try {
            $rate->delete();
            return redirect()->back()
                ->with('success', 'Shipping rate deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Shipping rate deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete shipping rate.');
        }
    }
}
