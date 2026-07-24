<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            DatosNegocioSeeder::class,
            CategoriasTableSeeder::class,
            ProductosTableSeeder::class,
            InsumosTableSeeder::class,
            ComprasInsumosTableSeeder::class,
        ]);
    }
}
