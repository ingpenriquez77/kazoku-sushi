<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Entradas', 'descripcion' => 'Platillos de entrada e entremeses'],
            ['nombre' => 'Naturales', 'descripcion' => 'Sushis y rollos naturales'],
            ['nombre' => 'Empanizados', 'descripcion' => 'Rollos empanizados y fritos'],
            ['nombre' => 'Especialidades', 'descripcion' => 'Especialidades de la casa'],
            ['nombre' => 'Arroces', 'descripcion' => 'Gohan y platillos a base de arroz'],
            ['nombre' => 'Promociones', 'descripcion' => 'Promociones especiales'],
            ['nombre' => 'Charolas', 'descripcion' => 'Charolas de sushi para compartir'],
            ['nombre' => 'Bebidas', 'descripcion' => 'Bebidas y refrescos'],
            ['nombre' => 'Extras', 'descripcion' => 'Ingredientes y aderezos extra'],
        ];

        foreach ($categorias as $cat) {
            Categoria::updateOrCreate(
                ['nombre' => $cat['nombre']], // Busca por nombre único
                [
                    'descripcion' => $cat['descripcion'],
                ]
            );
        }
    }
}
