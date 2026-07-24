<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorteZ extends Model
{
    use HasFactory;

    protected $table = 'corte_z';

    protected $fillable = [
        'user_id',
        'fecha',
        'total_ventas',
        'total_efectivo',
        'total_tarjeta',
        'observaciones',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
