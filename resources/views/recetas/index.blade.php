@extends('layouts.admin')

@section('title', 'Receta de ' . $producto->nombre)
@section('header', 'Configuración de Receta')

@section('content')
<div class="container-fluid">
    {{-- BOTÓN VOLVER Y RESUMEN FINANCIERO --}}
    <div class="row mb-3">
        <div class="col-12 mb-2">
            <a href="{{ route('productos.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Volver a Productos
            </a>
        </div>

        @php
            $costoTotal = 0;
            foreach($receta as $item) {
                $costoTotal += ($item->cantidad_usada * $item->precio_insumo);
            }
            $utilidad = $producto->precio - $costoTotal;
            $margen = $producto->precio > 0 ? ($utilidad / $producto->precio) * 100 : 0;
            
            // Colores basados en rentabilidad de restaurante
            $colorMargen = 'text-danger';
            if($margen >= 65) $colorMargen = 'text-success';
            elseif($margen >= 40) $colorMargen = 'text-warning';
        @endphp

        <div class="col-md-5">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 15px;">
                <div class="card-body">
                    <h5 class="text-muted small font-weight-bold text-uppercase">Producto seleccionado</h5>
                    <h3 class="font-weight-bold mb-1">{{ $producto->nombre }}</h3>
                    <p class="text-muted small mb-3">{{ $producto->descripcion }}</p>
                    <div class="d-flex align-items-center">
                        <span class="h4 mb-0 font-weight-bold text-primary">Precio Venta: ${{ number_format($producto->precio, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm border-0 h-100 bg-dark text-white" style="border-radius: 15px;">
                <div class="card-body">
                    <h5 class="text-white-50 small font-weight-bold text-uppercase">Análisis de Costos (Basado en Insumos)</h5>
                    <div class="row text-center mt-3">
                        <div class="col-4 border-right border-secondary">
                            <small class="d-block text-white-50">Costo Plato</small>
                            <span class="h4 font-weight-bold text-danger">${{ number_format($costoTotal, 2) }}</span>
                        </div>
                        <div class="col-4 border-right border-secondary">
                            <small class="d-block text-white-50">Ganancia Bruta</small>
                            <span class="h4 font-weight-bold text-success">${{ number_format($utilidad, 2) }}</span>
                        </div>
                        <div class="col-4">
                            <small class="d-block text-white-50">Margen de Utilidad</small>
                            <span class="h4 font-weight-bold {{ $colorMargen }}">{{ number_format($margen, 1) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- PANEL DE AGREGAR INGREDIENTE --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-plus-circle text-success mr-1"></i> Agregar a la Receta</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('recetas.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                        
                        <div class="form-group">
                            <label class="small font-weight-bold">Seleccionar Insumo</label>
                            <select name="insumo_id" class="form-control select2" required>
                                <option value="">Buscar ingrediente...</option>
                                @foreach($insumos as $insumo)
                                    <option value="{{ $insumo->id }}">
                                        {{ $insumo->nombre }} (Costo: ${{ number_format($insumo->precio_costo_unitario, 2) }} / {{ $insumo->unidad_medida }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold">Cantidad Utilizada</label>
                            <div class="input-group">
                                <input type="number" name="cantidad_usada" step="0.0001" class="form-control" placeholder="0.000" required>
                                <div class="input-group-append">
                                    <span class="input-group-text small">Cant.</span>
                                </div>
                            </div>
                            <small class="text-muted">Use decimales (Ej: 0.100 para 100gr o 100ml).</small>
                        </div>

                        <button type="submit" class="btn btn-success btn-block shadow-sm font-weight-bold mt-3">
                            <i class="fas fa-save mr-1"></i> Guardar Ingrediente
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- TABLA DETALLE DE RECETA --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-utensils text-primary mr-1"></i> Ingredientes de este plato</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="px-4">Insumo</th>
                                    <th>Cant. Usada</th>
                                    <th>Costo Unit.</th>
                                    <th>Subtotal</th>
                                    <th class="text-right px-4">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($receta as $item)
                                <tr>
                                    <td class="px-4 align-middle font-weight-bold">{{ $item->insumo_nombre }}</td>
                                    <td class="align-middle text-primary font-weight-bold">
                                        {{ number_format($item->cantidad_usada, 3) }} 
                                        <small class="text-muted font-weight-normal">{{ $item->unidad_medida }}</small>
                                    </td>
                                    <td class="align-middle text-muted small">${{ number_format($item->precio_insumo, 4) }}</td>
                                    <td class="align-middle font-weight-bold text-danger">
                                        ${{ number_format($item->cantidad_usada * $item->precio_insumo, 2) }}
                                    </td>
                                    <td class="text-right px-4 align-middle">
                                        <form action="{{ route('recetas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este insumo de la receta?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-info-circle mb-2 d-block"></i>
                                        Aún no has agregado ingredientes a este producto.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection