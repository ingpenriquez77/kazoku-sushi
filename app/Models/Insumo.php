<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Insumo extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'insumos';

    protected $fillable = [
        'nombre',
        'cantidad',
        'unidad_medida',
        'precio_unitario',
        'stock_minimo',
    ];
}
