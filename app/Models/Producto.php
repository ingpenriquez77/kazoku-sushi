<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    // Al usar SQL Directo, no necesitamos definir $fillable o $guarded
    protected $table = 'productos';
}