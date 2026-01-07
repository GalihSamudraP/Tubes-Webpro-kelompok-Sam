<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Espresso',
                'description' => 'Strong and bold coffee shot.',
                'price' => 3.50,
                'category' => 'kopi',
                'image' => 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            ],
            [
                'name' => 'Cappuccino',
                'description' => 'Espresso with steamed milk and foam.',
                'price' => 4.50,
                'category' => 'kopi',
                'image' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            ],
            [
                'name' => 'Latte',
                'description' => 'Smooth espresso with steamed milk.',
                'price' => 4.00,
                'category' => 'kopi',
                'image' => 'https://images.unsplash.com/photo-1561882468-411333a92def?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            ],
            [
                'name' => 'Mocha',
                'description' => 'Espresso with chocolate and steamed milk.',
                'price' => 5.00,
                'category' => 'kopi',
                'image' => 'https://images.unsplash.com/photo-1596078061324-9b1686734fb4?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            ],
            [
                'name' => 'Croissant',
                'description' => 'Buttery and flaky pastry.',
                'price' => 3.00,
                'category' => 'makanan',
                'image' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            ],
            [
                'name' => 'Cheesecake',
                'description' => 'Creamy slice of cheesecake.',
                'price' => 4.50,
                'category' => 'makanan',
                'image' => 'https://images.unsplash.com/photo-1508737804141-4c3b688e2546?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
