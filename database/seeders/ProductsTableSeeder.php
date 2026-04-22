<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
            [
                'code' => 'PROD-001',
                'name' => 'Laptop HP ProBook',
                'description' => 'Laptop de 14 pulgadas, 8GB RAM, 256GB SSD',
                'stock' => 15,
                'price' => 8500.00,
                'category' => 'Electrónica',
                'unit' => 'pieza',
                'min_stock' => 5,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'PROD-002',
                'name' => 'Mouse Inalámbrico Logitech',
                'description' => 'Mouse óptico inalámbrico, 2.4GHz',
                'stock' => 50,
                'price' => 350.00,
                'category' => 'Electrónica',
                'unit' => 'pieza',
                'min_stock' => 10,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'PROD-003',
                'name' => 'Teclado Mecánico RGB',
                'description' => 'Teclado mecánico con luces RGB',
                'stock' => 8,
                'price' => 1200.00,
                'category' => 'Electrónica',
                'unit' => 'pieza',
                'min_stock' => 3,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}