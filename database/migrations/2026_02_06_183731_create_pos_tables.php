<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datos_negocio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_comercial', 150);
            $table->string('razon_social', 200)->nullable();
            $table->string('nit_rut', 50)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('moneda', 5)->default('$');
            $table->text('mensaje_ticket')->nullable(); 
            $table->timestamps();
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->string('descripcion', 100);
            $table->timestamps();
        });

        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->onDelete('set null');
            $table->string('nombre', 50);
            $table->string('descripcion', 250);
            $table->decimal('precio', 12, 2);
            $table->timestamps();
        });

        // TABLA CORTES_Z (Se crea antes que ventas para poder referenciarla)
        Schema::create('cortes_z', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fecha_cierre');
            $table->decimal('total_efectivo', 12, 2);
            $table->decimal('total_tarjeta', 12, 2);
            $table->decimal('total_transferencia', 12, 2);
            $table->decimal('gran_total', 12, 2);
            $table->integer('total_ventas_count'); 
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_pedidido', 20); 
            $table->string('mesa', 50)->nullable(); 
            $table->enum('estado', ['pendiente', 'pagado', 'cancelado'])->default('pendiente');
            $table->decimal('total', 12, 2)->default(0);
            $table->string('metodo_pago', 50)->nullable(); 
            $table->decimal('monto_pagado', 12, 2)->default(0); 
            $table->decimal('cambio', 12, 2)->default(0);
            $table->foreignId('user_id')->constrained('users'); 
            
            $table->foreignId('corte_id')->nullable()->constrained('cortes_z')->onDelete('set null');
            
            $table->timestamps();
        });

        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos');
            $table->string('codigo_pedidido', 20);
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 12, 2); 
            $table->decimal('subtotal', 12, 2);
            $table->text('comentario')->nullable(); 
            $table->string('estado_item', 50)->default('pedidido'); 
            $table->timestamps();
        });

        Schema::create('insumos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('unidad_medida', 20);
            $table->decimal('stock_actual', 12, 2)->default(0);
            $table->decimal('stock_minimo', 12, 2)->default(0);
            $table->decimal('precio_costo_unitario', 12, 4)->default(0); 
            $table->timestamps();
        });

        Schema::create('recetas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('insumo_id')->constrained('insumos')->onDelete('cascade');
            $table->decimal('cantidad_usada', 12, 2); 
            $table->timestamps();
        });

        Schema::create('compras_insumos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insumo_id')->constrained('insumos')->onDelete('cascade');
            $table->decimal('cantidad', 12, 2);
            $table->decimal('costo_total', 12, 2);
            $table->date('fecha_compra'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('cortes_z');
        Schema::dropIfExists('compras_insumos');
        Schema::dropIfExists('recetas');
        Schema::dropIfExists('detalle_ventas');
        Schema::dropIfExists('ventas');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('insumos');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('datos_negocio');
        Schema::enableForeignKeyConstraints();
    }
};