<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Venta extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'ventas';

    protected $fillable = [
        'user_id',
        'codigo_pedidido', 
        'mesa',            
        'cliente',
        'total',
        'estado',          
        'metodo_pago',     
        'referencia_pago', 
        'monto_recibido', 
        'monto_pagado',  
        'cambio',         
        'notas',          
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}