<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class DatosNegocio extends Model
{
    // Nombre de la colección en MongoDB
    protected $collection = 'datos_negocios';

    // Campos permitidos para asignación masiva (updateOrCreate)
    protected $fillable = [
        'nombre_comercial',
        'razon_social',
        'nit_rut',
        'telefono',
        'direccion',
        'moneda',
        'mensaje_ticket',
    ];
}
