<?php

namespace Database\Seeders;

use App\Models\ProductAttribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Color',
                'description' => 'Product color',
                'type' => 'select',
                'is_required' => true,
                'is_global' => true,
                'is_filterable' => true,
                'is_visible_on_product_page' => true,
            ],
            [
                'name' => 'Size',
                'description' => 'Product size',
                'type' => 'select',
                'is_required' => true,
                'is_global' => true,
                'is_filterable' => true,
                'is_visible_on_product_page' => true,
            ],
            [
                'name' => 'Material',
                'description' => 'Main product material',
                'type' => 'select',
                'is_required' => false,
                'is_global' => true,
                'is_filterable' => true,
                'is_visible_on_product_page' => true,
            ],
            [
                'name' => 'Style',
                'description' => 'Product style',
                'type' => 'select',
                'is_required' => false,
                'is_global' => true,
                'is_filterable' => true,
                'is_visible_on_product_page' => true,
            ],
            [
                'name' => 'Capacity',
                'description' => 'Storage capacity (for electronic devices)',
                'type' => 'select',
                'is_required' => false,
                'is_global' => false,
                'is_filterable' => true,
                'is_visible_on_product_page' => true,
            ],
        ];
        
        foreach ($attributes as $index => $attributeData) {
            $attributeData['slug'] = Str::slug($attributeData['name']);
            $attributeData['position'] = $index + 1;
            ProductAttribute::create($attributeData);
        }
    }
}