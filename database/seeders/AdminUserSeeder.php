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
            ['username' => 'admin'], // Busca por este campo para no duplicar en Mongo
            [
                'name'              => 'Administrador',
                'email'             => 'admin@kazoku.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('010704'),
                'role'              => 'Administrador',
                'avatar'            => 'img/kazoku.png', // Añadido para mantener consistencia con el UserController
            ]
        );
    }
}
