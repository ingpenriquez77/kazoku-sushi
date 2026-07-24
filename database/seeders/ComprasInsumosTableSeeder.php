<?php

namespace Database\Seeders;

use App\Models\CompraInsumo;
use App\Models\Insumo;
use Illuminate\Database\Seeder;

class ComprasInsumosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapeamos las compras vinculadas directamente al nombre del insumo
        $compras = [
            ['insumo_nombre' => 'Arroz Sushimix', 'cantidad' => 4000.00, 'costo_total' => 100.00],
            ['insumo_nombre' => 'Alga Nori', 'cantidad' => 1000.00, 'costo_total' => 45.00],
            ['insumo_nombre' => 'Queso Crema', 'cantidad' => 100.00, 'costo_total' => 125.00],
            ['insumo_nombre' => 'Aguacate', 'cantidad' => 1000.00, 'costo_total' => 70.00],
            ['insumo_nombre' => 'Pepino', 'cantidad' => 1000.00, 'costo_total' => 35.00],
            ['insumo_nombre' => 'Salmón Fresh', 'cantidad' => 1000.00, 'costo_total' => 160.00],
            ['insumo_nombre' => 'Atún Fresco', 'cantidad' => 1000.00, 'costo_total' => 170.00],
            ['insumo_nombre' => 'Surimi', 'cantidad' => 1000.00, 'costo_total' => 50.00],
            ['insumo_nombre' => 'Ajonjolí Blanco', 'cantidad' => 1000.00, 'costo_total' => 21.00],
            ['insumo_nombre' => 'Ajonjolí Negro', 'cantidad' => 1000.00, 'costo_total' => 23.00],
            ['insumo_nombre' => 'Camarón', 'cantidad' => 1000.00, 'costo_total' => 175.00],
            ['insumo_nombre' => 'Salsa de Soya', 'cantidad' => 50.00, 'costo_total' => 120.00],
            ['insumo_nombre' => 'Panko', 'cantidad' => 1000.00, 'costo_total' => 160.00],
            ['insumo_nombre' => 'Harina Tempura', 'cantidad' => 20.00, 'costo_total' => 60.00],
            ['insumo_nombre' => 'Salsa Eel / Anguila', 'cantidad' => 3800.00, 'costo_total' => 175.00],
            ['insumo_nombre' => 'Chipotle', 'cantidad' => 1000.00, 'costo_total' => 60.00],
            ['insumo_nombre' => 'Salsa Tampico', 'cantidad' => 1000.00, 'costo_total' => 150.00],
            ['insumo_nombre' => 'Chiles Serranos', 'cantidad' => 1000.00, 'costo_total' => 60.00],
            ['insumo_nombre' => 'Mayonesa', 'cantidad' => 100.00, 'costo_total' => 54.00],
            ['insumo_nombre' => 'Sriracha', 'cantidad' => 100.00, 'costo_total' => 90.00],
            ['insumo_nombre' => 'Zanahoria', 'cantidad' => 750.00, 'costo_total' => 90.00],
            ['insumo_nombre' => 'Calabaza', 'cantidad' => 3500.00, 'costo_total' => 55.00],
            ['insumo_nombre' => 'Pollo Empanizado', 'cantidad' => 1000.00, 'costo_total' => 80.00],
            ['insumo_nombre' => 'Carne de Res', 'cantidad' => 1000.00, 'costo_total' => 20.00],
            ['insumo_nombre' => 'Té Helado', 'cantidad' => 25.00, 'costo_total' => 11.00],
            ['insumo_nombre' => 'Refresco 600ml', 'cantidad' => 1000.00, 'costo_total' => 100.00],
            ['insumo_nombre' => 'Agua Ciel', 'cantidad' => 1000.00, 'costo_total' => 14.00],
        ];

        foreach ($compras as $compraData) {
            // Buscamos el insumo por su nombre único para obtener su ObjectId real
            $insumo = Insumo::where('nombre', $compraData['insumo_nombre'])->first();

            if ($insumo) {
                CompraInsumo::create([
                    'insumo_id'    => $insumo->id, // ObjectId dinámico de Mongo
                    'cantidad'     => $compraData['cantidad'],
                    'costo_total'  => $compraData['costo_total'],
                    'fecha_compra' => now()->toDateString(),
                ]);
            }
        }
    }
}
