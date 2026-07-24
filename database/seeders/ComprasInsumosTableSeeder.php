<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComprasInsumosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $compras = [
            ['insumo_id' => 1, 'cantidad' => 4000.00, 'costo_total' => 100.00],
            ['insumo_id' => 2, 'cantidad' => 1000.00, 'costo_total' => 45.00],
            ['insumo_id' => 3, 'cantidad' => 100.00, 'costo_total' => 125.00],
            ['insumo_id' => 4, 'cantidad' => 1000.00, 'costo_total' => 70.00],
            ['insumo_id' => 5, 'cantidad' => 1000.00, 'costo_total' => 35.00],
            ['insumo_id' => 6, 'cantidad' => 1000.00, 'costo_total' => 160.00],
            ['insumo_id' => 7, 'cantidad' => 1000.00, 'costo_total' => 170.00],
            ['insumo_id' => 8, 'cantidad' => 1000.00, 'costo_total' => 50.00],
            ['insumo_id' => 9, 'cantidad' => 1000.00, 'costo_total' => 21.00],
            ['insumo_id' => 10, 'cantidad' => 1000.00, 'costo_total' => 23.00],
            ['insumo_id' => 11, 'cantidad' => 1000.00, 'costo_total' => 175.00],
            ['insumo_id' => 12, 'cantidad' => 50.00, 'costo_total' => 120.00],
            ['insumo_id' => 13, 'cantidad' => 1000.00, 'costo_total' => 160.00],
            ['insumo_id' => 14, 'cantidad' => 20.00, 'costo_total' => 60.00],
            ['insumo_id' => 15, 'cantidad' => 3800.00, 'costo_total' => 175.00],
            ['insumo_id' => 16, 'cantidad' => 1000.00, 'costo_total' => 60.00],
            ['insumo_id' => 17, 'cantidad' => 1000.00, 'costo_total' => 150.00],
            ['insumo_id' => 18, 'cantidad' => 1000.00, 'costo_total' => 60.00],
            ['insumo_id' => 19, 'cantidad' => 100.00, 'costo_total' => 54.00],
            ['insumo_id' => 20, 'cantidad' => 100.00, 'costo_total' => 90.00],
            ['insumo_id' => 21, 'cantidad' => 750.00, 'costo_total' => 90.00],
            ['insumo_id' => 22, 'cantidad' => 3500.00, 'costo_total' => 55.00],
            ['insumo_id' => 23, 'cantidad' => 1000.00, 'costo_total' => 80.00],
            ['insumo_id' => 24, 'cantidad' => 1000.00, 'costo_total' => 20.00],
            ['insumo_id' => 25, 'cantidad' => 25.00, 'costo_total' => 11.00],
            ['insumo_id' => 26, 'cantidad' => 1000.00, 'costo_total' => 100.00],
            ['insumo_id' => 27, 'cantidad' => 1000.00, 'costo_total' => 14.00],
        ];

        foreach ($compras as $compra) {
            DB::table('compras_insumos')->insert([
                'insumo_id'    => $compra['insumo_id'],
                'cantidad'     => $compra['cantidad'],
                'costo_total'  => $compra['costo_total'],
                'fecha_compra' => now()->toDateString(),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
