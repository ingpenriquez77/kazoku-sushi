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
        User::updateOrCreate(
            ['username' => 'admin'], // Busca por este campo para no duplicar si se ejecuta varias veces
            [
                'name'              => 'Administrador',
                'email'             => 'admin@kazoku.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('010704'), // Cambia la contraseña por la que gustes
                'role'              => 'Administrador',
            ]
        );
    }
}
