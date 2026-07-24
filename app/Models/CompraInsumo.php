<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CompraInsumo extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'compras_insumos';

    protected $fillable = [
        'insumo_id',
        'cantidad',
        'costo_total',
        'fecha_compra',
    ];
}
