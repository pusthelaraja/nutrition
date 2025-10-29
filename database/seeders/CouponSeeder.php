<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount',
                'description' => '10% off for new customers',
                'type' => 'percentage',
                'value' => 10,
                'minimum_amount' => 500,
                'maximum_discount' => 200,
                'usage_limit' => 100,
                'usage_limit_per_user' => 1,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
                'stackable' => false
            ],
            [
                'code' => 'SAVE50',
                'name' => 'Save ₹50',
                'description' => '₹50 off on orders above ₹1000',
                'type' => 'fixed_amount',
                'value' => 50,
                'minimum_amount' => 1000,
                'usage_limit' => 50,
                'usage_limit_per_user' => 2,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(1),
                'is_active' => true,
                'stackable' => false
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Free Shipping',
                'description' => 'Free shipping on all orders',
                'type' => 'free_shipping',
                'value' => 100,
                'minimum_amount' => 0,
                'usage_limit' => 200,
                'usage_limit_per_user' => 3,
                'starts_at' => now(),
                'expires_at' => now()->addWeeks(2),
                'is_active' => true,
                'stackable' => true
            ],
            [
                'code' => 'SUMMER20',
                'name' => 'Summer Sale',
                'description' => '20% off on summer products',
                'type' => 'percentage',
                'value' => 20,
                'minimum_amount' => 300,
                'maximum_discount' => 500,
                'usage_limit' => 75,
                'usage_limit_per_user' => 1,
                'starts_at' => now()->addDays(7),
                'expires_at' => now()->addMonths(2),
                'is_active' => true,
                'stackable' => false
            ],
            [
                'code' => 'VIP15',
                'name' => 'VIP Customer Discount',
                'description' => '15% off for VIP customers',
                'type' => 'percentage',
                'value' => 15,
                'minimum_amount' => 200,
                'usage_limit' => 25,
                'usage_limit_per_user' => 5,
                'starts_at' => now(),
                'expires_at' => now()->addYear(),
                'is_active' => true,
                'stackable' => false
            ]
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }

        $this->command->info('Successfully created ' . count($coupons) . ' sample coupons!');
    }
}
