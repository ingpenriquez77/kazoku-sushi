<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InsumosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insumos = [
            ['nombre' => 'Aceite', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.03],
            ['nombre' => 'Aguacate', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.05],
            ['nombre' => 'Alga', 'unidad_medida' => 'unidad', 'stock_actual' => 0.00, 'stock_minimo' => 30.00, 'precio_costo_unitario' => 1.25],
            ['nombre' => 'Anguila', 'unidad_medida' => 'ml', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.07],
            ['nombre' => 'Arroz', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.035],
            ['nombre' => 'Camaron', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.16],
            ['nombre' => 'Carne de Res', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.1700],
            ['nombre' => 'Cebollin', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.05],
            ['nombre' => 'Cebollita Asada', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.021],
            ['nombre' => 'Chile Caribe', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.023],
            ['nombre' => 'Chipotle', 'unidad_medida' => 'ml', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.0152173913],
            ['nombre' => 'Contenedor', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 25.00, 'precio_costo_unitario' => 2.40],
            ['nombre' => 'Gratinado', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.16],
            ['nombre' => 'Hoja de Arroz', 'unidad_medida' => 'unidad', 'stock_actual' => 0.00, 'stock_minimo' => 5.00, 'precio_costo_unitario' => 3.00],
            ['nombre' => 'Mayonesa', 'unidad_medida' => 'ml', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.04605263158],
            ['nombre' => 'Panco', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.06],
            ['nombre' => 'Philadelphia', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.15],
            ['nombre' => 'Pollo', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 1500.00, 'precio_costo_unitario' => 0.06],
            ['nombre' => 'Recipiente Aderezos', 'unidad_medida' => 'unidad', 'stock_actual' => 0.00, 'stock_minimo' => 30.00, 'precio_costo_unitario' => 0.54],
            ['nombre' => 'Recipiente Soya', 'unidad_medida' => 'unidad', 'stock_actual' => 0.00, 'stock_minimo' => 30.00, 'precio_costo_unitario' => 0.90],
            ['nombre' => 'Siracha', 'unidad_medida' => 'ml', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.12],
            ['nombre' => 'Soya', 'unidad_medida' => 'ml', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.01571428571],
            ['nombre' => 'Surimi', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.08],
            ['nombre' => 'Tampico', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 10.00, 'precio_costo_unitario' => 6.00],
            ['nombre' => 'Tenedor', 'unidad_medida' => 'unidad', 'stock_actual' => 0.00, 'stock_minimo' => 10.00, 'precio_costo_unitario' => 0.22],
            ['nombre' => 'Tocino', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.10],
            ['nombre' => 'Zanahoria', 'unidad_medida' => 'gr', 'stock_actual' => 0.00, 'stock_minimo' => 500.00, 'precio_costo_unitario' => 0.014],
        ];

        foreach ($insumos as $insumo) {
            DB::table('insumos')->updateOrInsert(
                ['nombre' => $insumo['nombre']],
                array_merge($insumo, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
