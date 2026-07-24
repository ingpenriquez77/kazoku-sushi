<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'avatar',
        'permissions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array', // Converte el JSON automáticamente a Array PHP
        ];
    }

    /**
     * Helper para verificar permisos de acceso
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->role === 'Administrador') {
            return true;
        }

        $perms = $this->permissions ?? [];
        return in_array($permission, $perms);
    }

    public function cortes()
    {
        return $this->hasMany(CorteZ::class);
    }

    public function preVentas()
    {
        return $this->hasMany(PreVenta::class);
    }
}
