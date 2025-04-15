<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Main categories
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic products and gadgets',
                'children' => [
                    [
                        'name' => 'Smartphones',
                        'description' => 'Smartphones and accessories',
                    ],
                    [
                        'name' => 'Computers',
                        'description' => 'Laptops and desktop computers',
                    ],
                    [
                        'name' => 'Accessories',
                        'description' => 'Electronic accessories',
                    ],
                ],
            ],
            [
                'name' => 'Clothing',
                'description' => 'Clothing for men, women, and children',
                'children' => [
                    [
                        'name' => 'Men',
                        'description' => 'Men\'s clothing',
                    ],
                    [
                        'name' => 'Women',
                        'description' => 'Women\'s clothing',
                    ],
                    [
                        'name' => 'Children',
                        'description' => 'Children\'s clothing',
                    ],
                ],
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Items for home and garden',
                'children' => [
                    [
                        'name' => 'Furniture',
                        'description' => 'Home furniture',
                    ],
                    [
                        'name' => 'Decoration',
                        'description' => 'Decorative items',
                    ],
                    [
                        'name' => 'Garden',
                        'description' => 'Gardening tools and accessories',
                    ],
                ],
            ],
            [
                'name' => 'Books & Media',
                'description' => 'Books, movies, and music',
                'children' => [
                    [
                        'name' => 'Books',
                        'description' => 'Print and digital books',
                    ],
                    [
                        'name' => 'Movies',
                        'description' => 'Movies and TV series',
                    ],
                    [
                        'name' => 'Music',
                        'description' => 'Albums and singles',
                    ],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);
            
            $categoryData['slug'] = Str::slug($categoryData['name']);
            $category = Category::create($categoryData);
            
            foreach ($children as $childData) {
                $childData['slug'] = Str::slug($childData['name']);
                $childData['parent_id'] = $category->id;
                Category::create($childData);
            }
        }
    }
}