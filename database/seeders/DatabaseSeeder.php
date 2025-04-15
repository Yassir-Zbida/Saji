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
            ProductAttributeSeeder::class,
            AttributeValueSeeder::class,
            ProductSeeder::class,
            TagSeeder::class,
            ProductVariationSeeder::class,
            CouponSeeder::class,
            SettingSeeder::class,
            ShippingZoneSeeder::class,
            ShippingMethodSeeder::class,
        ]);
    }
}