<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Receta extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'recetas';

    protected $fillable = [
        'producto_id',
        'insumo_id',
        'cantidad',
        'cantidad_usada',
    ];

    public function insumo()
    {
        // Forzamos la relación belongsTo sobre el modelo de MongoDB
        return $this->belongsTo(Insumo::class, 'insumo_id', '_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', '_id');
    }
}