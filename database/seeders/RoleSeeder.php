<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'description' => 'Administrador del sistema'],
            ['name' => 'sales', 'description' => 'Ventas - Toma pedidos de clientes'],
            ['name' => 'purchasing', 'description' => 'Compras - Gestiona compra de materiales'],
            ['name' => 'warehouse', 'description' => 'Almacén - Prepara pedidos y gestiona inventario'],
            ['name' => 'route', 'description' => 'Ruta - Distribuye pedidos a clientes'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}