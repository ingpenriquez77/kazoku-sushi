<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allPermissions = [
            'dashboard', 'configuracion', 'usuarios', 'categorias',
            'productos', 'inventario', 'recetas', 'preventa', 'caja'
        ];
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrador General',
                'email' => 'admin@kazoku.com',
                'password' => Hash::make('010704'),
                'role' => 'Administrador',
                'permissions' => $allPermissions,
            ]
        );
    }
}
