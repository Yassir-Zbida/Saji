<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create tags
        $tags = [
            [
                'name' => 'New',
                'description' => 'Recently added products',
                'color' => '#4CAF50', // Green
                'type' => 'product',
            ],
            [
                'name' => 'Popular',
                'description' => 'Best-selling products',
                'color' => '#2196F3', // Blue
                'type' => 'product',
            ],
            [
                'name' => 'Sale',
                'description' => 'Products on sale',
                'color' => '#F44336', // Red
                'type' => 'product',
            ],
            [
                'name' => 'Trending',
                'description' => 'Currently trending products',
                'color' => '#9C27B0', // Purple
                'type' => 'product',
            ],
            [
                'name' => 'Limited Edition',
                'description' => 'Limited edition products',
                'color' => '#FF9800', // Orange
                'type' => 'product',
            ],
            [
                'name' => 'Eco-friendly',
                'description' => 'Environmentally friendly products',
                'color' => '#8BC34A', // Light green
                'type' => 'product',
            ],
        ];
        
        foreach ($tags as $tagData) {
            $tagData['slug'] = Str::slug($tagData['name']);
            Tag::create($tagData);
        }
        
        // Assign tags to products
        $products = Product::all();
        $tagIds = Tag::pluck('id')->toArray();
        
        foreach ($products as $product) {
            // Assign 1 to 3 random tags to each product
            $randomTagCount = rand(1, 3);
            $randomTagIds = array_rand(array_flip($tagIds), $randomTagCount);
            
            if (!is_array($randomTagIds)) {
                $randomTagIds = [$randomTagIds];
            }
            
            $product->tags()->attach($randomTagIds);
            
            // Automatically assign the "Sale" tag to products on sale
            if ($product->sale_price !== null) {
                $saleTag = Tag::where('name', 'Sale')->first();
                if ($saleTag && !in_array($saleTag->id, $randomTagIds)) {
                    $product->tags()->attach($saleTag->id);
                }
            }
        }
    }
}