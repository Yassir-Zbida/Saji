<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
                'type' => 'percentage',
                'value' => 10.00,
                'minimum_spend' => 50.00,
                'maximum_discount' => 100.00,
                'usage_limit' => 1000,
                'individual_use' => true,
                'exclude_sale_items' => true,
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
                'is_active' => true,
                'description' => 'Get 10% off your first order',
            ],
            [
                'code' => 'SUMMER2023',
                'type' => 'percentage',
                'value' => 15.00,
                'minimum_spend' => 100.00,
                'maximum_discount' => 200.00,
                'usage_limit' => 500,
                'individual_use' => false,
                'exclude_sale_items' => false,
                'start_date' => now(),
                'end_date' => now()->addMonths(2),
                'is_active' => true,
                'description' => 'Summer promotion: 15% off sitewide',
            ],
            [
                'code' => 'FREESHIP',
                'type' => 'free_shipping',
                'value' => 0.00,
                'minimum_spend' => 75.00,
                'maximum_discount' => null,
                'usage_limit' => null,
                'individual_use' => false,
                'exclude_sale_items' => false,
                'start_date' => now(),
                'end_date' => null,
                'is_active' => true,
                'description' => 'Free shipping on orders over $75',
            ],
            [
                'code' => 'FIXED20',
                'type' => 'fixed_amount',
                'value' => 20.00,
                'minimum_spend' => 100.00,
                'maximum_discount' => null,
                'usage_limit' => 200,
                'individual_use' => true,
                'exclude_sale_items' => true,
                'start_date' => now(),
                'end_date' => now()->addMonths(1),
                'is_active' => true,
                'description' => '$20 off your order',
            ],
            [
                'code' => 'VIP25',
                'type' => 'percentage',
                'value' => 25.00,
                'minimum_spend' => 200.00,
                'maximum_discount' => 500.00,
                'usage_limit' => 100,
                'individual_use' => true,
                'exclude_sale_items' => false,
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'is_active' => true,
                'description' => 'VIP offer: 25% off orders over $200',
            ],
        ];
        
        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }
    }
}