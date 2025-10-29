<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get categories
        $powderCategory = Category::firstOrCreate(
            ['slug' => 'nutritional-powders'],
            [
                'name' => 'Nutritional Powders',
                'description' => 'Healthy nutritional powder products',
                'is_active' => true
            ]
        );

        $ladduCategory = Category::firstOrCreate(
            ['slug' => 'laddu-sweets'],
            [
                'name' => 'Laddu & Sweets',
                'description' => 'Traditional Indian sweets and laddus',
                'is_active' => true
            ]
        );

        $personalCareCategory = Category::firstOrCreate(
            ['slug' => 'personal-care'],
            [
                'name' => 'Personal Care',
                'description' => 'Herbal and organic personal care products',
                'is_active' => true
            ]
        );

        // Create products
        $products = [
            ['name' => 'Nutrimix powder', 'sku' => 'NUT001', 'price' => 299, 'stock' => 50, 'category' => $powderCategory],
            ['name' => 'Biotin Laddu', 'sku' => 'BIO001', 'price' => 199, 'stock' => 30, 'category' => $ladduCategory],
            ['name' => 'Dry Fruit laddu', 'sku' => 'DRY001', 'price' => 249, 'stock' => 25, 'category' => $ladduCategory],
            ['name' => 'Nuvvula laddu', 'sku' => 'NUV001', 'price' => 179, 'stock' => 40, 'category' => $ladduCategory],
            ['name' => 'Raggi Laddu', 'sku' => 'RAG001', 'price' => 159, 'stock' => 35, 'category' => $ladduCategory],
            ['name' => 'Herbal Nourish Shampoo', 'sku' => 'SHM001', 'price' => 199, 'stock' => 20, 'category' => $personalCareCategory],
            ['name' => 'Herbal Bath Powder', 'sku' => 'BTH001', 'price' => 149, 'stock' => 30, 'category' => $personalCareCategory],
            ['name' => 'Organic Loofa', 'sku' => 'LOO001', 'price' => 99, 'stock' => 15, 'category' => $personalCareCategory]
        ];

        foreach($products as $productData) {
            Product::firstOrCreate(
                ['sku' => $productData['sku']],
                [
                    'name' => $productData['name'],
                    'slug' => strtolower(str_replace(' ', '-', $productData['name'])),
                    'description' => 'High quality ' . $productData['name'] . ' for health and wellness',
                    'price' => $productData['price'],
                    'stock_quantity' => $productData['stock'],
                    'manage_stock' => true,
                    'is_active' => true,
                    'category_id' => $productData['category']->id
                ]
            );
        }

        $this->command->info('Successfully created ' . count($products) . ' products and 3 categories');
    }
}
