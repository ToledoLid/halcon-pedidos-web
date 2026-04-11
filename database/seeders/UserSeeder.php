<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@halcon.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'admin')->first()->id,
            'is_active' => true,
        ]);

        // Usuario Ventas
        User::create([
            'name' => 'Juan Pérez',
            'email' => 'ventas@halcon.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'sales')->first()->id,
            'is_active' => true,
        ]);

        // Usuario Almacén
        User::create([
            'name' => 'Carlos López',
            'email' => 'almacen@halcon.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'warehouse')->first()->id,
            'is_active' => true,
        ]);

        // Usuario Ruta
        User::create([
            'name' => 'Roberto Martínez',
            'email' => 'ruta@halcon.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'route')->first()->id,
            'is_active' => true,
        ]);

        // Usuario Compras
        User::create([
            'name' => 'María García',
            'email' => 'compras@halcon.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'purchasing')->first()->id,
            'is_active' => true,
        ]);
    }
}