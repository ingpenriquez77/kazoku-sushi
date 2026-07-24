<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'unidad_medida',
        'costo_unitario',
        'stock',
    ];

    public function recetas()
    {
        return $this->hasMany(Receta::class);
    }
}
