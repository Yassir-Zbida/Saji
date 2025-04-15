<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        
        // Products for Smartphones category
        $smartphonesCategory = Category::where('name', 'Smartphones')->first();
        if ($smartphonesCategory) {
            $smartphones = [
                [
                    'name' => 'Smartphone XS Pro',
                    'description' => 'The latest smartphone with OLED display and 48MP camera',
                    'price' => 899.99,
                    'sale_price' => 849.99,
                    'quantity' => 50,
                    'is_active' => true,
                    'is_featured' => true,
                ],
                [
                    'name' => 'Smartphone Basic',
                    'description' => 'An affordable smartphone with all essential features',
                    'price' => 299.99,
                    'sale_price' => null,
                    'quantity' => 100,
                    'is_active' => true,
                    'is_featured' => false,
                ],
                [
                    'name' => 'Smartphone Ultra',
                    'description' => 'The most powerful smartphone with octa-core processor',
                    'price' => 1299.99,
                    'sale_price' => 1199.99,
                    'quantity' => 25,
                    'is_active' => true,
                    'is_featured' => true,
                ],
            ];
            
            foreach ($smartphones as $index => $productData) {
                $this->createProduct($productData, $smartphonesCategory->id, $index);
            }
        }
        
        // Products for Computers category
        $computersCategory = Category::where('name', 'Computers')->first();
        if ($computersCategory) {
            $computers = [
                [
                    'name' => 'Pro Laptop',
                    'description' => 'Powerful laptop for professionals',
                    'price' => 1499.99,
                    'sale_price' => 1399.99,
                    'quantity' => 30,
                    'is_active' => true,
                    'is_featured' => true,
                ],
                [
                    'name' => 'Gaming Desktop PC',
                    'description' => 'Desktop PC for gamers with high-end graphics card',
                    'price' => 1999.99,
                    'sale_price' => null,
                    'quantity' => 15,
                    'is_active' => true,
                    'is_featured' => true,
                ],
                [
                    'name' => 'Student Laptop',
                    'description' => 'Lightweight and affordable laptop for students',
                    'price' => 699.99,
                    'sale_price' => 649.99,
                    'quantity' => 50,
                    'is_active' => true,
                    'is_featured' => false,
                ],
            ];
            
            foreach ($computers as $index => $productData) {
                $this->createProduct($productData, $computersCategory->id, $index + 10);
            }
        }
        
        // Products for Men's Clothing category
        $mensCategory = Category::where('name', 'Men')->first();
        if ($mensCategory) {
            $mensClothing = [
                [
                    'name' => 'Classic T-shirt',
                    'description' => 'High-quality cotton t-shirt',
                    'price' => 29.99,
                    'sale_price' => 24.99,
                    'quantity' => 100,
                    'is_active' => true,
                    'is_featured' => false,
                ],
                [
                    'name' => 'Slim Jeans',
                    'description' => 'Comfortable slim jeans for men',
                    'price' => 59.99,
                    'sale_price' => null,
                    'quantity' => 75,
                    'is_active' => true,
                    'is_featured' => true,
                ],
                [
                    'name' => 'Leather Jacket',
                    'description' => 'Genuine leather jacket for men',
                    'price' => 199.99,
                    'sale_price' => 179.99,
                    'quantity' => 25,
                    'is_active' => true,
                    'is_featured' => true,
                ],
            ];
            
            foreach ($mensClothing as $index => $productData) {
                $this->createProduct($productData, $mensCategory->id, $index + 20);
            }
        }
        
        // Products for Women's Clothing category
        $womensCategory = Category::where('name', 'Women')->first();
        if ($womensCategory) {
            $womensClothing = [
                [
                    'name' => 'Summer Dress',
                    'description' => 'Light and elegant summer dress',
                    'price' => 49.99,
                    'sale_price' => 39.99,
                    'quantity' => 60,
                    'is_active' => true,
                    'is_featured' => true,
                ],
                [
                    'name' => 'Silk Blouse',
                    'description' => 'High-quality silk blouse',
                    'price' => 89.99,
                    'sale_price' => null,
                    'quantity' => 40,
                    'is_active' => true,
                    'is_featured' => false,
                ],
                [
                    'name' => 'Skinny Jeans',
                    'description' => 'Comfortable skinny jeans for women',
                    'price' => 59.99,
                    'sale_price' => 49.99,
                    'quantity' => 80,
                    'is_active' => true,
                    'is_featured' => true,
                ],
            ];
            
            foreach ($womensClothing as $index => $productData) {
                $this->createProduct($productData, $womensCategory->id, $index + 30);
            }
        }
        
        // Products for other categories
        // Generate 30 random products distributed across remaining categories
        foreach ($categories as $category) {
            if (!in_array($category->name, ['Smartphones', 'Computers', 'Men', 'Women'])) {
                for ($i = 0; $i < 3; $i++) {
                    $price = rand(1999, 99999) / 100;
                    $salePrice = rand(0, 1) ? $price * 0.9 : null;
                    
                    $productData = [
                        'name' => $category->name . ' Product ' . ($i + 1),
                        'description' => 'Description for ' . $category->name . ' Product ' . ($i + 1),
                        'price' => $price,
                        'sale_price' => $salePrice,
                        'quantity' => rand(10, 100),
                        'is_active' => true,
                        'is_featured' => rand(0, 1) ? true : false,
                    ];
                    
                    $this->createProduct($productData, $category->id, $i + 40 + ($category->id * 3));
                }
            }
        }
    }
    
    /**
     * Create a product with the provided data
     */
    private function createProduct($productData, $categoryId, $index)
    {
        $productData['slug'] = Str::slug($productData['name']);
        $productData['category_id'] = $categoryId;
        $productData['sku'] = 'SKU-' . str_pad($index, 6, '0', STR_PAD_LEFT);
        $productData['image'] = 'placeholder.jpg';
        
        Product::create($productData);
    }
}