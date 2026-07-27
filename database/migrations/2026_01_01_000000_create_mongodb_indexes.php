<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mongodb';

    public function up(): void
    {
        // 1. Usuarios
        Schema::connection('mongodb')->table('users', function (Blueprint $collection) {
            $collection->unique('username');
            $collection->unique('email');
        });

        // 2. Categorías
        Schema::connection('mongodb')->table('categorias', function (Blueprint $collection) {
            $collection->unique('nombre');
        });

        // 3. Productos
        Schema::connection('mongodb')->table('productos', function (Blueprint $collection) {
            $collection->index('categoria_id');
            $collection->index('nombre');
        });

        // 4. Ventas
        Schema::connection('mongodb')->table('ventas', function (Blueprint $collection) {
            $collection->index('estado');
            $collection->index('corte_id');
            $collection->index('user_id');
            $collection->index('created_at');
            $collection->index('metodo_pago');
        });

        // 5. Detalle de Ventas
        Schema::connection('mongodb')->table('detalle_ventas', function (Blueprint $collection) {
            $collection->index('venta_id');
            $collection->index('producto_id');
            $collection->index('estado_item');
        });

        // 6. Recetas
        Schema::connection('mongodb')->table('recetas', function (Blueprint $collection) {
            $collection->index('producto_id');
            $collection->index('insumo_id');
        });

        // 7. Caja Turnos (Corte X / Cajeros)
        Schema::connection('mongodb')->table('caja_turnos', function (Blueprint $collection) {
            $collection->index('user_id');
            $collection->index('estado');
            $collection->index('fecha_apertura');
            $collection->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::connection('mongodb')->table('users', function (Blueprint $collection) {
            $collection->dropIndex(['username', 'email']);
        });

        Schema::connection('mongodb')->table('ventas', function (Blueprint $collection) {
            $collection->dropIndex(['estado', 'corte_id', 'user_id', 'created_at', 'metodo_pago']);
        });

        Schema::connection('mongodb')->table('detalle_ventas', function (Blueprint $collection) {
            $collection->dropIndex(['venta_id', 'producto_id', 'estado_item']);
        });

        Schema::connection('mongodb')->table('caja_turnos', function (Blueprint $collection) {
            $collection->dropIndex(['user_id', 'estado', 'fecha_apertura', 'created_at']);
        });
    }
};