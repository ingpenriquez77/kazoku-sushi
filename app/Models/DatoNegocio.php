<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class DatoNegocio extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'datos_negocio';

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
