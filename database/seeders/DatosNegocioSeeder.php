<?php

namespace Database\Seeders;

use App\Models\DatosNegocio;
use Illuminate\Database\Seeder;

class DatosNegocioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DatosNegocio::updateOrCreate(
            ['nombre_comercial' => 'Kazoku Sushi'], // Criterio de búsqueda para evitar duplicados en Mongo
            [
                'razon_social'   => 'Ruben Enriquez Alvarez',
                'nit_rut'        => 'EIAR970612SG9',
                'telefono'       => '6674640266',
                'direccion'      => 'Lic. Jose Vasconcelos 3062',
                'moneda'         => '$',
                'mensaje_ticket' => '¡¡¡ GRACIAS POR SU COMPRA !!!',
            ]
        );
    }
}
