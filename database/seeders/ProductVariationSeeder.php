<?php

namespace Database\Seeders;

use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductVariation;
use Illuminate\Database\Seeder;

class ProductVariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get attributes and their values
        $colorAttribute = ProductAttribute::where('name', 'Color')->first();
        $sizeAttribute = ProductAttribute::where('name', 'Size')->first();
        $capacityAttribute = ProductAttribute::where('name', 'Capacity')->first();
        
        $colorValues = $colorAttribute ? AttributeValue::where('attribute_id', $colorAttribute->id)->get() : collect([]);
        $sizeValues = $sizeAttribute ? AttributeValue::where('attribute_id', $sizeAttribute->id)->get() : collect([]);
        $capacityValues = $capacityAttribute ? AttributeValue::where('attribute_id', $capacityAttribute->id)->get() : collect([]);
        
        // Create variations for clothing products
        $clothingCategories = ['Men', 'Women', 'Children'];
        $clothingProducts = Product::whereHas('category', function ($query) use ($clothingCategories) {
            $query->whereIn('name', $clothingCategories);
        })->get();
        
        foreach ($clothingProducts as $product) {
            // For clothing, use color and size attributes
            if ($colorValues->count() > 0 && $sizeValues->count() > 0) {
                // Select 3 random colors
                $selectedColors = $colorValues->random(min(3, $colorValues->count()));
                
                // Select 4 random sizes
                $selectedSizes = $sizeValues->random(min(4, $sizeValues->count()));
                
                $variationCount = 0;
                
                foreach ($selectedColors as $color) {
                    foreach ($selectedSizes as $size) {
                        $variationCount++;
                        $sku = $product->sku . '-' . $variationCount;
                        
                        // Calculate a slightly different price for each variation
                        $priceAdjustment = rand(-10, 10) / 100; // -10% to +10%
                        $price = $product->price * (1 + $priceAdjustment);
                        $salePrice = $product->sale_price ? $product->sale_price * (1 + $priceAdjustment) : null;
                        
                        // Create the variation
                        ProductVariation::create([
                            'product_id' => $product->id,
                            'sku' => $sku,
                            'price' => round($price, 2),
                            'sale_price' => $salePrice ? round($salePrice, 2) : null,
                            'quantity' => rand(5, 30),
                            'is_active' => true,
                            'attribute_values' => json_encode([
                                $colorAttribute->id => $color->id,
                                $sizeAttribute->id => $size->id,
                            ]),
                        ]);
                    }
                }
            }
        }
        
        // Create variations for electronic products
        $electronicCategories = ['Smartphones', 'Computers', 'Accessories'];
        $electronicProducts = Product::whereHas('category', function ($query) use ($electronicCategories) {
            $query->whereIn('name', $electronicCategories);
        })->get();
        
        foreach ($electronicProducts as $product) {
            // For smartphones, use color and capacity attributes
            if ($product->category->name === 'Smartphones' && $colorValues->count() > 0 && $capacityValues->count() > 0) {
                // Select 3 random colors
                $selectedColors = $colorValues->random(min(3, $colorValues->count()));
                
                // Select 3 random capacities
                $selectedCapacities = $capacityValues->random(min(3, $capacityValues->count()));
                
                $variationCount = 0;
                
                foreach ($selectedColors as $color) {
                    foreach ($selectedCapacities as $capacity) {
                        $variationCount++;
                        $sku = $product->sku . '-' . $variationCount;
                        
                        // Increase price based on capacity
                        $capacityIndex = array_search($capacity->id, $capacityValues->pluck('id')->toArray());
                        $priceMultiplier = 1 + ($capacityIndex * 0.2); // +20% per capacity level
                        
                        $price = $product->price * $priceMultiplier;
                        $salePrice = $product->sale_price ? $product->sale_price * $priceMultiplier : null;
                        
                        // Create the variation
                        ProductVariation::create([
                            'product_id' => $product->id,
                            'sku' => $sku,
                            'price' => round($price, 2),
                            'sale_price' => $salePrice ? round($salePrice, 2) : null,
                            'quantity' => rand(5, 30),
                            'is_active' => true,
                            'attribute_values' => json_encode([
                                $colorAttribute->id => $color->id,
                                $capacityAttribute->id => $capacity->id,
                            ]),
                        ]);
                    }
                }
            }
            
            // For computers, use only capacity attribute
            elseif ($product->category->name === 'Computers' && $capacityValues->count() > 0) {
                // Select 4 random capacities
                $selectedCapacities = $capacityValues->random(min(4, $capacityValues->count()));
                
                $variationCount = 0;
                
                foreach ($selectedCapacities as $capacity) {
                    $variationCount++;
                    $sku = $product->sku . '-' . $variationCount;
                    
                    // Increase price based on capacity
                    $capacityIndex = array_search($capacity->id, $capacityValues->pluck('id')->toArray());
                    $priceMultiplier = 1 + ($capacityIndex * 0.15); // +15% per capacity level
                    
                    $price = $product->price * $priceMultiplier;
                    $salePrice = $product->sale_price ? $product->sale_price * $priceMultiplier : null;
                    
                    // Create the variation
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'sku' => $sku,
                        'price' => round($price, 2),
                        'sale_price' => $salePrice ? round($salePrice, 2) : null,
                        'quantity' => rand(5, 30),
                        'is_active' => true,
                        'attribute_values' => json_encode([
                            $capacityAttribute->id => $capacity->id,
                        ]),
                    ]);
                }
            }
        }
    }
}