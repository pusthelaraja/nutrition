<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingZone;
use App\Models\PincodeZone;
use App\Models\ShippingRate;
use App\Models\Warehouse;
use App\Models\WarehouseShippingRate;
use Illuminate\Support\Facades\DB;

class IndiaShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create shipping methods first
        $shippingMethods = [
            ['name' => 'Standard Delivery', 'description' => 'Regular delivery service'],
            ['name' => 'Express Delivery', 'description' => 'Fast delivery service'],
            ['name' => 'Same Day Delivery', 'description' => 'Same day delivery service'],
        ];

        foreach ($shippingMethods as $method) {
            DB::table('shipping_methods')->insertOrIgnore([
                'name' => $method['name'],
                'description' => $method['description'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $standardMethodId = DB::table('shipping_methods')->where('name', 'Standard Delivery')->first()->id;

        // Create main warehouse
        $mainWarehouse = Warehouse::create([
            'name' => 'Hyderabad Main Warehouse',
            'code' => 'HYD-MAIN',
            'address' => 'Plot No. 123, Industrial Area',
            'pincode' => '500032',
            'city' => 'Hyderabad',
            'state' => 'Telangana',
            'country' => 'India',
            'latitude' => 17.3850,
            'longitude' => 78.4867,
            'is_active' => true,
            'is_primary' => true
        ]);

        // Create shipping zones
        $zones = [
            [
                'name' => 'Hyderabad Metro',
                'description' => 'Hyderabad metropolitan area including core city zones',
                'sort_order' => 1,
                'pincodes' => [
                    ['pincode' => '500001', 'city' => 'Hyderabad', 'state' => 'Telangana'],
                    ['pincode' => '500002', 'city' => 'Hyderabad', 'state' => 'Telangana'],
                    ['pincode' => '500003', 'city' => 'Hyderabad', 'state' => 'Telangana'],
                    ['pincode' => '500004', 'city' => 'Hyderabad', 'state' => 'Telangana'],
                    ['pincode' => '500005', 'city' => 'Hyderabad', 'state' => 'Telangana'],
                    ['pincode' => '500006', 'city' => 'Hyderabad', 'state' => 'Telangana'],
                    ['pincode' => '500007', 'city' => 'Hyderabad', 'state' => 'Telangana'],
                    ['pincode' => '500008', 'city' => 'Hyderabad', 'state' => 'Telangana'],
                    ['pincode' => '500009', 'city' => 'Hyderabad', 'state' => 'Telangana'],
                    ['pincode' => '500010', 'city' => 'Hyderabad', 'state' => 'Telangana'],
                ]
            ],
            [
                'name' => 'Hyderabad Outer',
                'description' => 'Outer areas of Hyderabad including Secunderabad, Cyberabad',
                'sort_order' => 2,
                'pincodes' => [
                    ['pincode' => '500011', 'city' => 'Secunderabad', 'state' => 'Telangana'],
                    ['pincode' => '500012', 'city' => 'Secunderabad', 'state' => 'Telangana'],
                    ['pincode' => '500013', 'city' => 'Secunderabad', 'state' => 'Telangana'],
                    ['pincode' => '500014', 'city' => 'Secunderabad', 'state' => 'Telangana'],
                    ['pincode' => '500015', 'city' => 'Secunderabad', 'state' => 'Telangana'],
                    ['pincode' => '500016', 'city' => 'Cyberabad', 'state' => 'Telangana'],
                    ['pincode' => '500017', 'city' => 'Cyberabad', 'state' => 'Telangana'],
                    ['pincode' => '500018', 'city' => 'Cyberabad', 'state' => 'Telangana'],
                    ['pincode' => '500019', 'city' => 'Cyberabad', 'state' => 'Telangana'],
                    ['pincode' => '500020', 'city' => 'Cyberabad', 'state' => 'Telangana'],
                ]
            ],
            [
                'name' => 'Telangana State',
                'description' => 'Other cities in Telangana state',
                'sort_order' => 3,
                'pincodes' => [
                    ['pincode' => '500021', 'city' => 'Warangal', 'state' => 'Telangana'],
                    ['pincode' => '500022', 'city' => 'Nizamabad', 'state' => 'Telangana'],
                    ['pincode' => '500023', 'city' => 'Karimnagar', 'state' => 'Telangana'],
                    ['pincode' => '500024', 'city' => 'Khammam', 'state' => 'Telangana'],
                    ['pincode' => '500025', 'city' => 'Mahabubnagar', 'state' => 'Telangana'],
                ]
            ],
            [
                'name' => 'Metro Cities',
                'description' => 'Major metro cities across India',
                'sort_order' => 4,
                'pincodes' => [
                    ['pincode' => '110001', 'city' => 'New Delhi', 'state' => 'Delhi'],
                    ['pincode' => '110002', 'city' => 'New Delhi', 'state' => 'Delhi'],
                    ['pincode' => '400001', 'city' => 'Mumbai', 'state' => 'Maharashtra'],
                    ['pincode' => '400002', 'city' => 'Mumbai', 'state' => 'Maharashtra'],
                    ['pincode' => '560001', 'city' => 'Bangalore', 'state' => 'Karnataka'],
                    ['pincode' => '560002', 'city' => 'Bangalore', 'state' => 'Karnataka'],
                    ['pincode' => '600001', 'city' => 'Chennai', 'state' => 'Tamil Nadu'],
                    ['pincode' => '600002', 'city' => 'Chennai', 'state' => 'Tamil Nadu'],
                    ['pincode' => '700001', 'city' => 'Kolkata', 'state' => 'West Bengal'],
                    ['pincode' => '700002', 'city' => 'Kolkata', 'state' => 'West Bengal'],
                ]
            ],
            [
                'name' => 'Tier-1 Cities',
                'description' => 'Major tier-1 cities across India',
                'sort_order' => 5,
                'pincodes' => [
                    ['pincode' => '380001', 'city' => 'Ahmedabad', 'state' => 'Gujarat'],
                    ['pincode' => '302001', 'city' => 'Jaipur', 'state' => 'Rajasthan'],
                    ['pincode' => '226001', 'city' => 'Lucknow', 'state' => 'Uttar Pradesh'],
                    ['pincode' => '208001', 'city' => 'Kanpur', 'state' => 'Uttar Pradesh'],
                    ['pincode' => '440001', 'city' => 'Nagpur', 'state' => 'Maharashtra'],
                    ['pincode' => '452001', 'city' => 'Indore', 'state' => 'Madhya Pradesh'],
                    ['pincode' => '462001', 'city' => 'Bhopal', 'state' => 'Madhya Pradesh'],
                    ['pincode' => '160001', 'city' => 'Chandigarh', 'state' => 'Chandigarh'],
                ]
            ],
            [
                'name' => 'South India',
                'description' => 'South Indian states and cities',
                'sort_order' => 6,
                'pincodes' => [
                    ['pincode' => '641001', 'city' => 'Coimbatore', 'state' => 'Tamil Nadu'],
                    ['pincode' => '682001', 'city' => 'Kochi', 'state' => 'Kerala'],
                    ['pincode' => '530001', 'city' => 'Visakhapatnam', 'state' => 'Andhra Pradesh'],
                    ['pincode' => '520001', 'city' => 'Vijayawada', 'state' => 'Andhra Pradesh'],
                    ['pincode' => '570001', 'city' => 'Mysore', 'state' => 'Karnataka'],
                    ['pincode' => '575001', 'city' => 'Mangalore', 'state' => 'Karnataka'],
                ]
            ],
            [
                'name' => 'North India',
                'description' => 'North Indian states and cities',
                'sort_order' => 7,
                'pincodes' => [
                    ['pincode' => '141001', 'city' => 'Ludhiana', 'state' => 'Punjab'],
                    ['pincode' => '122001', 'city' => 'Gurgaon', 'state' => 'Haryana'],
                    ['pincode' => '248001', 'city' => 'Dehradun', 'state' => 'Uttarakhand'],
                    ['pincode' => '180001', 'city' => 'Jammu', 'state' => 'Jammu and Kashmir'],
                    ['pincode' => '190001', 'city' => 'Srinagar', 'state' => 'Jammu and Kashmir'],
                ]
            ],
            [
                'name' => 'East India',
                'description' => 'East Indian states and cities',
                'sort_order' => 8,
                'pincodes' => [
                    ['pincode' => '751001', 'city' => 'Bhubaneswar', 'state' => 'Odisha'],
                    ['pincode' => '834001', 'city' => 'Ranchi', 'state' => 'Jharkhand'],
                    ['pincode' => '800001', 'city' => 'Patna', 'state' => 'Bihar'],
                    ['pincode' => '781001', 'city' => 'Guwahati', 'state' => 'Assam'],
                    ['pincode' => '793001', 'city' => 'Shillong', 'state' => 'Meghalaya'],
                ]
            ],
            [
                'name' => 'West India',
                'description' => 'West Indian states and cities',
                'sort_order' => 9,
                'pincodes' => [
                    ['pincode' => '411001', 'city' => 'Pune', 'state' => 'Maharashtra'],
                    ['pincode' => '403001', 'city' => 'Panaji', 'state' => 'Goa'],
                    ['pincode' => '313001', 'city' => 'Udaipur', 'state' => 'Rajasthan'],
                    ['pincode' => '324001', 'city' => 'Kota', 'state' => 'Rajasthan'],
                    ['pincode' => '390001', 'city' => 'Vadodara', 'state' => 'Gujarat'],
                ]
            ],
            [
                'name' => 'Remote Areas',
                'description' => 'Remote and difficult to reach areas',
                'sort_order' => 10,
                'pincodes' => [
                    ['pincode' => '744101', 'city' => 'Port Blair', 'state' => 'Andaman and Nicobar Islands'],
                    ['pincode' => '194101', 'city' => 'Leh', 'state' => 'Ladakh'],
                    ['pincode' => '191101', 'city' => 'Kargil', 'state' => 'Ladakh'],
                    ['pincode' => '795001', 'city' => 'Imphal', 'state' => 'Manipur'],
                    ['pincode' => '797001', 'city' => 'Kohima', 'state' => 'Nagaland'],
                ]
            ]
        ];

        foreach ($zones as $zoneData) {
            $zone = ShippingZone::create([
                'name' => $zoneData['name'],
                'description' => $zoneData['description'],
                'is_active' => true,
                'sort_order' => $zoneData['sort_order']
            ]);

            // Add pincodes to zone
            foreach ($zoneData['pincodes'] as $pincodeData) {
                PincodeZone::create([
                    'shipping_zone_id' => $zone->id,
                    'pincode' => $pincodeData['pincode'],
                    'city' => $pincodeData['city'],
                    'state' => $pincodeData['state'],
                    'country' => 'India',
                    'is_active' => true
                ]);
            }

            // Create shipping rates for each zone
            $baseRate = $this->getBaseRateForZone($zoneData['name']);
            $ratePerKg = $this->getRatePerKgForZone($zoneData['name']);
            $freeShippingThreshold = $this->getFreeShippingThresholdForZone($zoneData['name']);
            $estimatedDays = $this->getEstimatedDaysForZone($zoneData['name']);

            ShippingRate::create([
                'shipping_zone_id' => $zone->id,
                'shipping_method_id' => $standardMethodId,
                'base_rate' => $baseRate,
                'rate_per_kg' => $ratePerKg,
                'free_shipping_threshold' => $freeShippingThreshold,
                'estimated_days' => $estimatedDays,
                'is_active' => true
            ]);

            // Create warehouse shipping rates
            WarehouseShippingRate::create([
                'warehouse_id' => $mainWarehouse->id,
                'shipping_zone_id' => $zone->id,
                'shipping_method_id' => $standardMethodId,
                'base_rate' => $baseRate,
                'rate_per_kg' => $ratePerKg,
                'free_shipping_threshold' => $freeShippingThreshold,
                'estimated_days' => $estimatedDays,
                'distance_km' => $this->getDistanceForZone($zoneData['name']),
                'is_active' => true
            ]);
        }

        $this->command->info('Successfully created India shipping zones with pincodes and rates!');
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
        return $rates[$zoneName] ?? 250;
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
        return $rates[$zoneName] ?? 60;
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
        return $thresholds[$zoneName] ?? 2500;
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
        return $days[$zoneName] ?? 12;
    }

    private function getDistanceForZone($zoneName)
    {
        $distances = [
            'Hyderabad Metro' => 10,
            'Hyderabad Outer' => 25,
            'Telangana State' => 100,
            'Metro Cities' => 500,
            'Tier-1 Cities' => 800,
            'South India' => 600,
            'North India' => 1200,
            'East India' => 1000,
            'West India' => 800,
            'Remote Areas' => 2000
        ];
        return $distances[$zoneName] ?? 1500;
    }
}
