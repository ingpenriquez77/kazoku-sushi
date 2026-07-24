<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Categoria extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'categorias';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
