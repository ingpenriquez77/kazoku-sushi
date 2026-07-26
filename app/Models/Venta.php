<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Venta extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'ventas';

    protected $fillable = [
        'user_id',
        'cliente',
        'total',
        'estado',         // ej: 'completada', 'cancelada'
        'metodo_pago',    // ej: 'efectivo', 'tarjeta', 'transferencia'
        'referencia_pago',// N° de Voucher (tarjeta) o N° de Folio/Rastreo (transferencia)
        'monto_recibido', // Util si pagan en efectivo y necesitas calcular el cambio
        'cambio',         // Cambio devuelto
        'notas',          // Notas adicionales opcionales
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