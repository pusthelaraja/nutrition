<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingZone;
use App\Models\ShippingRate;
use Illuminate\Support\Facades\DB;

class ShippingRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get shipping zones
        $zones = ShippingZone::all();

        if ($zones->isEmpty()) {
            $this->command->info('No shipping zones found. Please create zones first.');
            return;
        }

        // Get shipping methods
        $methods = DB::table('shipping_methods')->get();

        if ($methods->isEmpty()) {
            $this->command->info('No shipping methods found. Please create methods first.');
            return;
        }

        $standardMethod = $methods->where('name', 'Standard Delivery')->first();
        if (!$standardMethod) {
            $standardMethod = $methods->first();
        }

        // Create sample rates for each zone
        foreach ($zones as $zone) {
            // Skip if zone already has rates
            if ($zone->shippingRates()->count() > 0) {
                continue;
            }

            // Determine rates based on zone name
            $baseRate = $this->getBaseRateForZone($zone->name);
            $ratePerKg = $this->getRatePerKgForZone($zone->name);
            $freeShippingThreshold = $this->getFreeShippingThresholdForZone($zone->name);
            $estimatedDays = $this->getEstimatedDaysForZone($zone->name);

            ShippingRate::create([
                'shipping_zone_id' => $zone->id,
                'shipping_method_id' => $standardMethod->id,
                'base_rate' => $baseRate,
                'rate_per_kg' => $ratePerKg,
                'free_shipping_threshold' => $freeShippingThreshold,
                'estimated_days' => $estimatedDays,
                'is_active' => true
            ]);

            $this->command->info("Added shipping rate for zone: {$zone->name}");
        }

        $this->command->info('Shipping rates created successfully!');
    }

    private function getBaseRateForZone($zoneName)
    {
        $rates = [
            'Hyderabad Metro' => 50,
            'Hyderabad Outer' => 75,
            'Telangana State' => 100,
            'Metro Cities' => 120,
            'Tier-1 Cities' => 150,
            'South India' => 180,
            'North India' => 200,
            'East India' => 220,
            'West India' => 200,
            'Remote Areas' => 300
        ];
        return $rates[$zoneName] ?? 150;
    }

    private function getRatePerKgForZone($zoneName)
    {
        $rates = [
            'Hyderabad Metro' => 20,
            'Hyderabad Outer' => 25,
            'Telangana State' => 30,
            'Metro Cities' => 35,
            'Tier-1 Cities' => 40,
            'South India' => 45,
            'North India' => 50,
            'East India' => 55,
            'West India' => 50,
            'Remote Areas' => 75
        ];
        return $rates[$zoneName] ?? 40;
    }

    private function getFreeShippingThresholdForZone($zoneName)
    {
        $thresholds = [
            'Hyderabad Metro' => 500,
            'Hyderabad Outer' => 750,
            'Telangana State' => 1000,
            'Metro Cities' => 1200,
            'Tier-1 Cities' => 1500,
            'South India' => 1800,
            'North India' => 2000,
            'East India' => 2200,
            'West India' => 2000,
            'Remote Areas' => 3000
        ];
        return $thresholds[$zoneName] ?? 1500;
    }

    private function getEstimatedDaysForZone($zoneName)
    {
        $days = [
            'Hyderabad Metro' => 1,
            'Hyderabad Outer' => 2,
            'Telangana State' => 3,
            'Metro Cities' => 4,
            'Tier-1 Cities' => 5,
            'South India' => 6,
            'North India' => 7,
            'East India' => 8,
            'West India' => 7,
            'Remote Areas' => 10
        ];
        return $days[$zoneName] ?? 5;
    }
}
