<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Illuminate\Support\Carbon;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        Order::create([
            'invoice_number' => 'INV-001',
            'customer_name' => 'Cliente Ejemplo 1',
            'customer_number' => 'CLI-001',
            'fiscal_data' => 'RFC: XXXX000000',
            'order_date' => Carbon::now(),
            'delivery_address' => 'Calle Principal 123',
            'notes' => 'Entregar antes de las 5pm',
            'status' => Order::STATUS_ORDERED,
            'created_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Order::create([
            'invoice_number' => 'INV-002',
            'customer_name' => 'Cliente Ejemplo 2',
            'customer_number' => 'CLI-002',
            'fiscal_data' => 'RFC: XXXX111111',
            'order_date' => Carbon::now(),
            'delivery_address' => 'Avenida Central 456',
            'notes' => 'Llamar antes de entregar',
            'status' => Order::STATUS_IN_PROCESS,
            'created_by' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Order::create([
            'invoice_number' => 'INV-003',
            'customer_name' => 'Cliente Ejemplo 3',
            'customer_number' => 'CLI-003',
            'fiscal_data' => 'RFC: XXXX222222',
            'order_date' => Carbon::now()->subDay(),
            'delivery_address' => 'Plaza Principal 789',
            'notes' => 'Entregar en recepción',
            'status' => Order::STATUS_IN_ROUTE,
            'created_by' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Order::create([
            'invoice_number' => 'INV-004',
            'customer_name' => 'Cliente Ejemplo 4',
            'customer_number' => 'CLI-004',
            'fiscal_data' => 'RFC: XXXX333333',
            'order_date' => Carbon::now()->subDays(2),
            'delivery_address' => 'Calle Secundaria 321',
            'notes' => 'Entregado exitosamente',
            'status' => Order::STATUS_DELIVERED,
            'created_by' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}