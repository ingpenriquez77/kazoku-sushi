<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class CajaTurno extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'caja_turnos';

    protected $fillable = [
        'user_id',
        'cajero_nombre',
        'monto_apertura',      // Dinero base en caja
        'monto_efectivo_ventas',
        'monto_tarjeta_ventas',
        'monto_transferencia_ventas',
        'monto_total_esperado',// (apertura + ventas efectivo)
        'monto_efectivo_real', // Lo que el cajero contó
        'diferencia',          // Sobrante (+) o Faltante (-)
        'fecha_apertura',
        'fecha_cierre',
        'estado',              // 'abierta' o 'cerrada'
        'observaciones'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}