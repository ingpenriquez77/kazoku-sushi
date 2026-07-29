<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Insumo;
use App\Models\Receta;

class RecetaSeeder extends Seeder
{
    public function run(): void
    {
        $recetasData = [
            // --- ENTRADAS ---
            'Chile Relleno' => [
                'Chile Caribe' => 45,
                'Carne de Res' => 35,
                'Camaron' => 40,
                'Pollo' => 40,
                'Tocino' => 25,
            ],
            'Goyo Roll' => [
                'Hoja de Arroz' => 1,
                'Surimi' => 50,
                'Carne de Res' => 35,
                'Camaron' => 40,
                'Pollo' => 40,
            ],
            'Brocheta' => [
                'Arroz' => 210,
                'Philadelphia' => 20,
                'Aguacate' => 15,
                'Anguila' => 15,
                'Carne de Res' => 35,
                'Pollo' => 40,
                'Panco' => 10,
                'Aceite' => 10,
            ],

            // --- ARROCES ---
            'Gohan Especial' => [
                'Arroz' => 210,
                'Philadelphia' => 20,
                'Pollo' => 40,
                'Cebollin' => 20,
                'Anguila' => 15,
            ],
            'Gohan Mixto' => [
                'Arroz' => 210,
                'Philadelphia' => 20,
                'Pollo' => 40,
                'Carne de Res' => 35,
                'Camaron' => 40,
                'Aguacate' => 15,
                'Anguila' => 15,
            ],

            // --- EMPANIZADOS ---
            'Mar y Tierra' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Carne de Res' => 35,
                'Camaron' => 40,
                'Panco' => 10,
                'Aceite' => 10,
            ],
            'Cielo, Mar y Tierra' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Carne de Res' => 35,
                'Camaron' => 40,
                'Pollo' => 40,
                'Panco' => 10,
                'Aceite' => 10,
            ],
            'Cordón Blue' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Aceite' => 10,
                'Panco' => 10,
                'Pollo' => 40,
                'Tocino' => 25,
                'Gratinado' => 28,
            ],
            '3 Quesos' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Aceite' => 10,
                'Panco' => 10,
                'Carne de Res' => 35,
                'Camaron' => 40,
                'Gratinado' => 28,
            ],
            'Guamuchilito' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Aceite' => 10,
                'Panco' => 10,
                'Surimi' => 50,
                'Camaron' => 40,
            ],
            'Camaron Blue' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Aceite' => 10,
                'Panco' => 10,
                'Camaron' => 40,
                'Tocino' => 25,
                'Gratinado' => 28,
            ],
            'Caribeño' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Aceite' => 10,
                'Panco' => 10,
                'Camaron' => 40,
                'Tocino' => 25,
                'Chile Caribe' => 45,
                'Gratinado' => 28,
            ],
            'El de papa' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Aceite' => 10,
                'Panco' => 10,
                'Camaron' => 40,
                'Carne de Res' => 35,
                'Tocino' => 25,
                'Anguila' => 15,
            ],
            'Ranchero' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Aceite' => 10,
                'Panco' => 10,
                'Carne de Res' => 35,
                'Pollo' => 40,
                'Gratinado' => 28,
                'Tocino' => 25,
            ],
            'Bomba' => [
                'Arroz' => 210,
                'Carne de Res' => 35,
                'Camaron' => 40,
                'Pollo' => 40,
                'Tocino' => 25,
                'Aceite' => 10,
                'Panco' => 10,
            ],
            'Bomba Especial' => [
                'Arroz' => 210,
                'Carne de Res' => 35,
                'Camaron' => 40,
                'Pollo' => 40,
                'Tocino' => 25,
                'Aceite' => 10,
                'Panco' => 10,
                'Gratinado' => 28,
            ],
            'Vane Roll' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Aceite' => 10,
                'Panco' => 10,
                'Carne de Res' => 35,
                'Pollo' => 40,
                'Gratinado' => 28,
                'Tocino' => 25,
            ],
            '31 Roll' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Aceite' => 10,
                'Panco' => 10,
                'Carne de Res' => 35,
                'Camaron' => 40,
                'Tocino' => 25,
                'Gratinado' => 28,
                'Pollo' => 40,
            ],

            // --- ESPECIALES ---
            'El Ahogado' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Aceite' => 10,
                'Panco' => 10,
                'Pollo' => 40,
                'Surimi' => 50,
                'Gratinado' => 28,
                'Tocino' => 25,
            ],
            '3 Carnes' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Aceite' => 10,
                'Panco' => 10,
                'Carne de Res' => 35,
                'Pollo' => 40,
                'Tocino' => 25,
                'Gratinado' => 28,
                'Anguila' => 15,
                'Siracha' => 15,
            ],
            'RockRoll' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Aceite' => 10,
                'Panco' => 10,
                'Carne de Res' => 35,
                'Camaron' => 40,
                'Gratinado' => 28,
                'Tocino' => 25,
            ],
            'Bombastic' => [
                'Arroz' => 210,
                'Tocino' => 25,
                'Camaron' => 40,
                'Carne de Res' => 35,
                'Pollo' => 40,
                'Panco' => 10,
                'Aceite' => 10,
                'Gratinado' => 28,
                'Anguila' => 15,
            ],
            'Kroll' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Aceite' => 10,
                'Panco' => 10,
                'Pollo' => 40,
                'Camaron' => 40,
                'Gratinado' => 28,
                'Tocino' => 25,
                'Anguila' => 15,
            ],

            // --- NATURALES ---
            'Avocado' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Panco' => 10,
                'Camaron' => 40,
                'Anguila' => 15,
            ],
            'Camaroncito' => [
                'Arroz' => 210,
                'Alga' => 1,
                'Aguacate' => 15,
                'Philadelphia' => 20,
                'Panco' => 10,
                'Camaron' => 40,
                'Gratinado' => 28,
                'Anguila' => 15,
            ],
        ];

        foreach ($recetasData as $nombreProducto => $ingredientes) {
            // Buscar producto por coincidencia exacta o parecida
            $producto = Producto::where('nombre', 'LIKE', trim($nombreProducto))->first();

            if (!$producto) {
                continue;
            }

            foreach ($ingredientes as $nombreInsumo => $cantidad) {
                $insumo = Insumo::where('nombre', 'LIKE', trim($nombreInsumo))->first();

                if ($insumo) {
                    Receta::updateOrCreate(
                        [
                            'producto_id' => (string) $producto->id,
                            'insumo_id'   => (string) $insumo->id,
                        ],
                        [
                            'cantidad_usada' => (float) $cantidad,
                        ]
                    );
                }
            }
        }
    }
}