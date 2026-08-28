<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder{

    public function run():void
    {
        Product::insert([
            ['code' => 'BJ001', 'name' => 'Cardigan biru', 'category' => 'Baju', 'unit' => 'pcs', 'price' => 56000, 'cost' => 35000, 'stock' => 50, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'JKT01', 'name' => 'Jaket Kulit', 'category' => 'Baju', 'unit' => 'pcs', 'price' => 150000, 'cost' => 90000, 'stock' => 50, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'BJ002', 'name' => 'Blouse coklat', 'category' => 'Baju', 'unit' => 'pcs', 'price' => 65000, 'cost' => 40000, 'stock' => 50, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'BJ003', 'name' => 'Kaos', 'category' => 'Baju', 'unit' => 'pcs', 'price' => 45000, 'cost' => 35000, 'stock' => 50, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'JKT02', 'name' => 'Jaket Hangat', 'category' => 'Baju', 'unit' => 'pcs', 'price' => 130000, 'cost' => 90000, 'stock' => 50, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'BJ004', 'name' => 'Blouse Polkadot', 'category' => 'Baju', 'unit' => 'pcs', 'price' => 75000, 'cost' => 40000, 'stock' => 50, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
