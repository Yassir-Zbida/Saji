<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ProductAttributeSeeder::class,
            ProductVariationSeeder::class,
            ShippingMethodSeeder::class,
            ShippingZoneSeeder::class,
            OrderSeeder::class, // Added OrderSeeder to database seeder
        ]);
    }
}