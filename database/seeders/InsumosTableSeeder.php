<?php

namespace Database\Seeders;

use App\Models\Insumo;
use Illuminate\Database\Seeder;

class InsumosTableSeeder extends Seeder // <-- Asegúrate de que diga InsumosTableSeeder y NO InsumoSeeder
{
    public function run(): void
    {
        $insumos = [
            // --- PROTEÍNAS ---
            ['nombre' => 'Carne de Res', 'cantidad' => 1500.00, 'unidad_medida' => 'gr', 'stock_minimo' => 500.00, 'precio_unitario' => 0.1700],
            ['nombre' => 'Camaron', 'cantidad' => 1500.00, 'unidad_medida' => 'gr', 'stock_minimo' => 500.00, 'precio_unitario' => 0.1600],
            ['nombre' => 'Pollo', 'cantidad' => 2000.00, 'unidad_medida' => 'gr', 'stock_minimo' => 500.00, 'precio_unitario' => 0.0600],
            ['nombre' => 'Tocino', 'cantidad' => 1000.00, 'unidad_medida' => 'gr', 'stock_minimo' => 300.00, 'precio_unitario' => 0.1000],
            ['nombre' => 'Surimi', 'cantidad' => 1000.00, 'unidad_medida' => 'gr', 'stock_minimo' => 300.00, 'precio_unitario' => 0.0800],

            // --- LÁCTEOS Y CREMAS ---
            ['nombre' => 'Philadelphia', 'cantidad' => 1500.00, 'unidad_medida' => 'gr', 'stock_minimo' => 400.00, 'precio_unitario' => 0.1500],
            ['nombre' => 'Gratinado', 'cantidad' => 1000.00, 'unidad_medida' => 'gr', 'stock_minimo' => 300.00, 'precio_unitario' => 0.1600],
            ['nombre' => 'Mayonesa', 'cantidad' => 1000.00, 'unidad_medida' => 'ml', 'stock_minimo' => 300.00, 'precio_unitario' => 0.0460],

            // --- BASES Y EMPANIZADO ---
            ['nombre' => 'Arroz', 'cantidad' => 3000.00, 'unidad_medida' => 'gr', 'stock_minimo' => 1000.00, 'precio_unitario' => 0.0350],
            ['nombre' => 'Alga', 'cantidad' => 50.00, 'unidad_medida' => 'unidad', 'stock_minimo' => 15.00, 'precio_unitario' => 1.2500],
            ['nombre' => 'Panco', 'cantidad' => 1000.00, 'unidad_medida' => 'gr', 'stock_minimo' => 300.00, 'precio_unitario' => 0.0600],
            ['nombre' => 'Aceite', 'cantidad' => 2000.00, 'unidad_medida' => 'gr', 'stock_minimo' => 500.00, 'precio_unitario' => 0.0300],
            ['nombre' => 'Hoja de Arroz', 'cantidad' => 20.00, 'unidad_medida' => 'unidad', 'stock_minimo' => 5.00, 'precio_unitario' => 3.0000],

            // --- VERDURAS Y SALSAS ---
            ['nombre' => 'Aguacate', 'cantidad' => 1000.00, 'unidad_medida' => 'gr', 'stock_minimo' => 300.00, 'precio_unitario' => 0.0500],
            ['nombre' => 'Chile Caribe', 'cantidad' => 500.00, 'unidad_medida' => 'gr', 'stock_minimo' => 150.00, 'precio_unitario' => 0.0230],
            ['nombre' => 'Cebollin', 'cantidad' => 300.00, 'unidad_medida' => 'gr', 'stock_minimo' => 100.00, 'precio_unitario' => 0.0500],
            ['nombre' => 'Soya', 'cantidad' => 1500.00, 'unidad_medida' => 'ml', 'stock_minimo' => 500.00, 'precio_unitario' => 0.0157],
            ['nombre' => 'Siracha', 'cantidad' => 500.00, 'unidad_medida' => 'ml', 'stock_minimo' => 150.00, 'precio_unitario' => 0.1200],
            ['nombre' => 'Anguila', 'cantidad' => 500.00, 'unidad_medida' => 'ml', 'stock_minimo' => 150.00, 'precio_unitario' => 0.0700],
            ['nombre' => 'Tampico', 'cantidad' => 500.00, 'unidad_medida' => 'gr', 'stock_minimo' => 100.00, 'precio_unitario' => 6.0000],

            // --- EMPAQUES Y DESECHABLES ---
            ['nombre' => 'Contenedor', 'cantidad' => 50.00, 'unidad_medida' => 'unidad', 'stock_minimo' => 15.00, 'precio_unitario' => 2.4000],
            ['nombre' => 'Recipiente Aderezos', 'cantidad' => 50.00, 'unidad_medida' => 'unidad', 'stock_minimo' => 15.00, 'precio_unitario' => 0.5400],
            ['nombre' => 'Recipiente Soya', 'cantidad' => 50.00, 'unidad_medida' => 'unidad', 'stock_minimo' => 15.00, 'precio_unitario' => 0.9000],
            ['nombre' => 'Tenedor', 'cantidad' => 50.00, 'unidad_medida' => 'unidad', 'stock_minimo' => 15.00, 'precio_unitario' => 0.2200],
        ];

        foreach ($insumos as $insumo) {
            Insumo::updateOrCreate(
                ['nombre' => $insumo['nombre']],
                [
                    'cantidad'        => $insumo['cantidad'],
                    'unidad_medida'   => $insumo['unidad_medida'],
                    'precio_unitario' => $insumo['precio_unitario'],
                    'stock_minimo'    => $insumo['stock_minimo'],
                ]
            );
        }
    }
}