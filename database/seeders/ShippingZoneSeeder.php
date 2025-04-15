<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create shipping zones
        $zones = [
            [
                'name' => 'United States',
                'description' => 'Shipping within the United States',
                'position' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Europe',
                'description' => 'Shipping to European countries',
                'position' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'International',
                'description' => 'International shipping',
                'position' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('shipping_zones')->insert($zones);
        
        // Add regions for each zone
        $regions = [
            // United States
            [
                'shipping_zone_id' => 1,
                'region_type' => 'country',
                'region_code' => 'US',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Europe
            [
                'shipping_zone_id' => 2,
                'region_type' => 'country',
                'region_code' => 'DE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_zone_id' => 2,
                'region_type' => 'country',
                'region_code' => 'FR',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_zone_id' => 2,
                'region_type' => 'country',
                'region_code' => 'IT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_zone_id' => 2,
                'region_type' => 'country',
                'region_code' => 'ES',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_zone_id' => 2,
                'region_type' => 'country',
                'region_code' => 'UK',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_zone_id' => 2,
                'region_type' => 'country',
                'region_code' => 'NL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_zone_id' => 2,
                'region_type' => 'country',
                'region_code' => 'BE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // International (all other countries)
            [
                'shipping_zone_id' => 3,
                'region_type' => 'continent',
                'region_code' => 'NA', // North America
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_zone_id' => 3,
                'region_type' => 'continent',
                'region_code' => 'SA', // South America
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_zone_id' => 3,
                'region_type' => 'continent',
                'region_code' => 'AS', // Asia
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_zone_id' => 3,
                'region_type' => 'continent',
                'region_code' => 'AF', // Africa
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'shipping_zone_id' => 3,
                'region_type' => 'continent',
                'region_code' => 'OC', // Oceania
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('shipping_zone_regions')->insert($regions);
    }
}