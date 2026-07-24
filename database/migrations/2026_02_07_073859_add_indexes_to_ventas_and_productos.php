<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToVentasAndProductos extends Migration
{
    
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->index('estado', 'idx_venta_estado');
        });

        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->index('estado_item', 'idx_detalle_ventas_estado');
        });
    }

    
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropIndex('idx_venta_estado');
        });

        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->dropIndex('idx_detalle_ventas_estado');
        });
    }
}