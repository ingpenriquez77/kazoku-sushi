<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CorteZ extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'cortes_z';

    protected $fillable = [
        'fecha_cierre',
        'total_efectivo',
        'total_tarjeta',
        'total_transferencia',
        'gran_total',
        'total_ventas_count',
        'user_id',
    ];
}
