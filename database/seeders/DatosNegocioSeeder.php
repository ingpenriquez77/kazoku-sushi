<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatosNegocioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('datos_negocio')->updateOrInsert(
            ['id' => 1],
            [
                'nombre_comercial' => 'Kazoku Sushi',
                'razon_social'     => 'Ruben Enriquez Alvarez',
                'nit_rut'          => 'EIAR970612SG9',
                'telefono'         => '6674640266',
                'direccion'        => 'Lic. Jose Vasconcelos 3062',
                'moneda'           => '$',
                'mensaje_ticket'   => '¡¡¡ GRACIAS POR SU COMPRA !!!',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]
        );
    }
}
