<?php

namespace Database\Seeders;

use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Values for Color attribute
        $colorAttribute = ProductAttribute::where('name', 'Color')->first();
        if ($colorAttribute) {
            $colors = [
                ['value' => 'Red', 'color_code' => '#FF0000'],
                ['value' => 'Blue', 'color_code' => '#0000FF'],
                ['value' => 'Green', 'color_code' => '#00FF00'],
                ['value' => 'Black', 'color_code' => '#000000'],
                ['value' => 'White', 'color_code' => '#FFFFFF'],
                ['value' => 'Gray', 'color_code' => '#808080'],
                ['value' => 'Yellow', 'color_code' => '#FFFF00'],
                ['value' => 'Orange', 'color_code' => '#FFA500'],
                ['value' => 'Pink', 'color_code' => '#FFC0CB'],
                ['value' => 'Purple', 'color_code' => '#800080'],
            ];
            
            foreach ($colors as $index => $colorData) {
                AttributeValue::create([
                    'attribute_id' => $colorAttribute->id,
                    'value' => $colorData['value'],
                    'slug' => Str::slug($colorData['value']),
                    'color_code' => $colorData['color_code'],
                    'position' => $index + 1,
                ]);
            }
        }
        
        // Values for Size attribute
        $sizeAttribute = ProductAttribute::where('name', 'Size')->first();
        if ($sizeAttribute) {
            $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '36', '38', '40', '42', '44', '46'];
            
            foreach ($sizes as $index => $size) {
                AttributeValue::create([
                    'attribute_id' => $sizeAttribute->id,
                    'value' => $size,
                    'slug' => Str::slug($size),
                    'position' => $index + 1,
                ]);
            }
        }
        
        // Values for Material attribute
        $materialAttribute = ProductAttribute::where('name', 'Material')->first();
        if ($materialAttribute) {
            $materials = ['Cotton', 'Polyester', 'Wool', 'Silk', 'Leather', 'Denim', 'Linen', 'Velvet', 'Nylon', 'Cashmere'];
            
            foreach ($materials as $index => $material) {
                AttributeValue::create([
                    'attribute_id' => $materialAttribute->id,
                    'value' => $material,
                    'slug' => Str::slug($material),
                    'position' => $index + 1,
                ]);
            }
        }
        
        // Values for Style attribute
        $styleAttribute = ProductAttribute::where('name', 'Style')->first();
        if ($styleAttribute) {
            $styles = ['Casual', 'Formal', 'Sport', 'Vintage', 'Bohemian', 'Classic', 'Modern', 'Elegant', 'Minimalist', 'Streetwear'];
            
            foreach ($styles as $index => $style) {
                AttributeValue::create([
                    'attribute_id' => $styleAttribute->id,
                    'value' => $style,
                    'slug' => Str::slug($style),
                    'position' => $index + 1,
                ]);
            }
        }
        
        // Values for Capacity attribute
        $capacityAttribute = ProductAttribute::where('name', 'Capacity')->first();
        if ($capacityAttribute) {
            $capacities = ['32 GB', '64 GB', '128 GB', '256 GB', '512 GB', '1 TB', '2 TB'];
            
            foreach ($capacities as $index => $capacity) {
                AttributeValue::create([
                    'attribute_id' => $capacityAttribute->id,
                    'value' => $capacity,
                    'slug' => Str::slug($capacity),
                    'position' => $index + 1,
                ]);
            }
        }
    }
}