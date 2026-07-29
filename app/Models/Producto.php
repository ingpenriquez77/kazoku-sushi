<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Producto extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'categoria_id',
        'imagen',
        'disponible',
        'stock_actual',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', '_id');
    }
}