<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            // Methods for United States
            [
                'shipping_zone_id' => 1,
                'name' => 'Standard Shipping',
                'method_type' => 'flat_rate',
                'cost' => 5.99,
                'minimum_order_amount' => null,
                'maximum_order_amount' => null,
                'is_taxable' => true,
                'position' => 1,
                'is_active' => true,
                'settings' => json_encode([
                    'estimated_delivery_time' => '2-3 business days',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_zone_id' => 1,
                'name' => 'Express Shipping',
                'method_type' => 'flat_rate',
                'cost' => 12.99,
                'minimum_order_amount' => null,
                'maximum_order_amount' => null,
                'is_taxable' => true,
                'position' => 2,
                'is_active' => true,
                'settings' => json_encode([
                    'estimated_delivery_time' => '1 business day',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_zone_id' => 1,
                'name' => 'Free Shipping',
                'method_type' => 'free_shipping',
                'cost' => 0.00,
                'minimum_order_amount' => 75.00,
                'maximum_order_amount' => null,
                'is_taxable' => false,
                'position' => 3,
                'is_active' => true,
                'settings' => json_encode([
                    'estimated_delivery_time' => '3-5 business days',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Methods for Europe
            [
                'shipping_zone_id' => 2,
                'name' => 'Standard Europe Shipping',
                'method_type' => 'flat_rate',
                'cost' => 12.99,
                'minimum_order_amount' => null,
                'maximum_order_amount' => null,
                'is_taxable' => true,
                'position' => 1,
                'is_active' => true,
                'settings' => json_encode([
                    'estimated_delivery_time' => '3-5 business days',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_zone_id' => 2,
                'name' => 'Express Europe Shipping',
                'method_type' => 'flat_rate',
                'cost' => 24.99,
                'minimum_order_amount' => null,
                'maximum_order_amount' => null,
                'is_taxable' => true,
                'position' => 2,
                'is_active' => true,
                'settings' => json_encode([
                    'estimated_delivery_time' => '1-2 business days',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_zone_id' => 2,
                'name' => 'Free Europe Shipping',
                'method_type' => 'free_shipping',
                'cost' => 0.00,
                'minimum_order_amount' => 150.00,
                'maximum_order_amount' => null,
                'is_taxable' => false,
                'position' => 3,
                'is_active' => true,
                'settings' => json_encode([
                    'estimated_delivery_time' => '5-7 business days',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Methods for International
            [
                'shipping_zone_id' => 3,
                'name' => 'Standard International Shipping',
                'method_type' => 'flat_rate',
                'cost' => 29.99,
                'minimum_order_amount' => null,
                'maximum_order_amount' => null,
                'is_taxable' => true,
                'position' => 1,
                'is_active' => true,
                'settings' => json_encode([
                    'estimated_delivery_time' => '7-14 business days',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_zone_id' => 3,
                'name' => 'Express International Shipping',
                'method_type' => 'flat_rate',
                'cost' => 49.99,
                'minimum_order_amount' => null,
                'maximum_order_amount' => null,
                'is_taxable' => true,
                'position' => 2,
                'is_active' => true,
                'settings' => json_encode([
                    'estimated_delivery_time' => '3-5 business days',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_zone_id' => 3,
                'name' => 'Free International Shipping',
                'method_type' => 'free_shipping',
                'cost' => 0.00,
                'minimum_order_amount' => 300.00,
                'maximum_order_amount' => null,
                'is_taxable' => false,
                'position' => 3,
                'is_active' => true,
                'settings' => json_encode([
                    'estimated_delivery_time' => '10-15 business days',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('shipping_methods')->insert($methods);
        
        // Create some shipping classes
        $classes = [
            [
                'name' => 'Standard',
                'slug' => 'standard',
                'description' => 'Products of standard size and weight',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Heavy',
                'slug' => 'heavy',
                'description' => 'Heavy products requiring special handling',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fragile',
                'slug' => 'fragile',
                'description' => 'Fragile products requiring special packaging',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Oversized',
                'slug' => 'oversized',
                'description' => 'Large products requiring special delivery',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('shipping_classes')->insert($classes);
        
        // Add specific rates for shipping classes
        $classRates = [
            // United States - Standard Shipping
            [
                'shipping_method_id' => 1,
                'shipping_class_id' => 2, // Heavy
                'cost' => 10.99,
                'cost_type' => 'fixed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_method_id' => 1,
                'shipping_class_id' => 3, // Fragile
                'cost' => 8.99,
                'cost_type' => 'fixed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_method_id' => 1,
                'shipping_class_id' => 4, // Oversized
                'cost' => 15.99,
                'cost_type' => 'fixed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // United States - Express Shipping
            [
                'shipping_method_id' => 2,
                'shipping_class_id' => 2, // Heavy
                'cost' => 19.99,
                'cost_type' => 'fixed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_method_id' => 2,
                'shipping_class_id' => 3, // Fragile
                'cost' => 16.99,
                'cost_type' => 'fixed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_method_id' => 2,
                'shipping_class_id' => 4, // Oversized
                'cost' => 24.99,
                'cost_type' => 'fixed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('shipping_class_rates')->insert($classRates);
    }
}