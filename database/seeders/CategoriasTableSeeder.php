<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['id' => 1, 'nombre' => 'Entradas', 'descripcion' => 'Platillos de entrada e entremeses'],
            ['id' => 2, 'nombre' => 'Naturales', 'descripcion' => 'Sushis y rollos naturales'],
            ['id' => 3, 'nombre' => 'Empanizados', 'descripcion' => 'Rollos empanizados y fritos'],
            ['id' => 4, 'nombre' => 'Especialidades', 'descripcion' => 'Especialidades de la casa'],
            ['id' => 5, 'nombre' => 'Arroces', 'descripcion' => 'Gohan y platillos a base de arroz'],
            ['id' => 6, 'nombre' => 'Promociones', 'descripcion' => 'Promociones especiales'],
            ['id' => 7, 'nombre' => 'Charolas', 'descripcion' => 'Charolas de sushi para compartir'],
            ['id' => 8, 'nombre' => 'Bebidas', 'descripcion' => 'Bebidas y refrescos'],
            ['id' => 9, 'nombre' => 'Extras', 'descripcion' => 'Ingredientes y aderezos extra'],
        ];

        foreach ($categorias as $cat) {
            DB::table('categorias')->updateOrInsert(
                ['id' => $cat['id']],
                [
                    'nombre'      => $cat['nombre'],
                    'descripcion' => $cat['descripcion'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
        }
    }
}
