<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder{

    public function run():void
    {
        Product::insert([
            ['code' => 'BJ001', 'name' => 'Cardigan biru', 'category' => 'Baju', 'unit' => 'pcs', 'price' => 56000, 'cost' => 30000, 'stock' => 50, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'JKT02', 'name' => 'Jaket Kulit', 'category' => 'Baju', 'unit' => 'pcs', 'price' => 150000, 'cost' => 20000, 'stock' => 50, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'BJ002', 'name' => 'Blouse coklat', 'category' => 'Baju', 'unit' => 'pcs', 'price' => 25000, 'cost' => 20000, 'stock' => 50, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
