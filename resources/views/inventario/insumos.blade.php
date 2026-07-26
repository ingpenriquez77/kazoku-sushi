@extends('layouts.admin')

@section('title', 'Gestión de Insumos')
@section('content_header')
    <h1>Inventario de Materia Prima</h1>
@stop

@section('content')
<div class="row">
    {{-- BARRA SUPERIOR --}}
    <div class="col-md-12 mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <button class="btn btn-primary shadow-sm mb-2" data-toggle="modal" data-target="#modalInsumo">
                <i class="fas fa-plus"></i> Nuevo Insumo
            </button>

            <div class="input-group" style="width: 300px;">
                <input type="text" id="buscador-tabla" class="form-control" placeholder="Escribe para buscar..." autocomplete="off">
                <div class="input-group-append">
                    <span class="input-group-text bg-dark text-white border-dark">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card shadow border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>Insumo</th>
                                <th>U. Medida</th>
                                <th>Stock Actual</th>
                                <th>Stock Mín.</th>
                                <th>Costo Unit.</th>
                                <th>Inversión</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpo-tabla">
                            @forelse($insumos as $insumo)
                            @php
                                // Detecta automáticamente el nombre del campo guardado en MongoDB
                                $stockActual = $insumo->cantidad ?? $insumo->stock_actual ?? 0;
                                $costoUnitario = $insumo->precio_unitario ?? $insumo->precio_costo_unitario ?? 0;
                                $inversion = $stockActual * $costoUnitario;
                                $stockMinimo = $insumo->stock_minimo ?? 0;
                            @endphp
                            <tr class="fila-insumo">
                                <td class="align-middle font-weight-bold nombre-txt">{{ $insumo->nombre }}</td>
                                <td class="align-middle text-uppercase small">{{ $insumo->unidad_medida }}</td>
                                <td class="align-middle font-weight-bold">
                                    {{ number_format($stockActual, 2) }}
                                </td>
                                <td class="align-middle text-muted small">{{ number_format($stockMinimo, 2) }}</td>
                                <td class="align-middle">${{ number_format($costoUnitario, 4) }}</td>
                                <td class="align-middle text-success font-weight-bold">
                                    ${{ number_format($inversion, 2) }}
                                </td>
                                <td class="align-middle">
                                    @if($stockActual <= $stockMinimo)
                                        <span class="badge badge-danger px-2">REORDENAR</span>
                                    @else
                                        <span class="badge badge-success px-2">SUFICIENTE</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-success btn-add-stock" 
                                                data-id="{{ $insumo->id }}" 
                                                data-nombre="{{ $insumo->nombre }}"
                                                data-unidad="{{ $insumo->unidad_medida }}"
                                                data-toggle="modal" data-target="#modalAddStock"
                                                title="Aumentar Stock">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        
                                        <form action="{{ route('insumos.destroy', $insumo->id) }}" method="POST" onsubmit="return confirm('¿Eliminar insumo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger ml-1">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr id="fila-vacia">
                                <td colspan="8" class="text-center py-5">No hay insumos registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PAGINACIÓN --}}
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Mostrando registros del <b>{{ $insumos->firstItem() ?? 0 }}</b> al <b>{{ $insumos->lastItem() ?? 0 }}</b> 
                        de un total de <b>{{ $insumos->total() }}</b>
                    </div>
                    <div class="pagination-sm">
                        {{ $insumos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: NUEVO INSUMO --}}
<div class="modal fade" id="modalInsumo" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">Nuevo Insumo</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('insumos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Unidad de Medida</label>
                                <select name="unidad_medida" class="form-control">
                                    <option value="gr">Gramos (gr)</option>
                                    <option value="ml">Mililitros (ml)</option>
                                    <option value="unidad">Unidad (pza)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stock Mínimo</label>
                                <input type="number" name="stock_minimo" step="0.01" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cantidad Inicial</label>
                                <input type="number" name="cantidad" step="0.01" class="form-control" value="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Costo Unitario ($)</label>
                                <input type="number" name="precio_unitario" step="0.0001" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block">GUARDAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: AGREGAR STOCK --}}
<div class="modal fade" id="modalAddStock" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Entrada: <span id="nombre_insumo_modal"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('insumos.addStock') }}" method="POST">
                @csrf
                <input type="hidden" name="insumo_id" id="insumo_id_input">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Cantidad</label>
                            <input type="number" name="cantidad" id="input_cantidad" step="0.01" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Unidad</label>
                            <select name="unidad_ingreso" class="form-control" id="select_unidad_ingreso">
                                <option value="gr">Gramos</option>
                                <option value="kg">Kilos</option>
                                <option value="ml">Mililitros</option>
                                <option value="unidad">Unidad</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label>Costo Total de esta compra ($)</label>
                        <input type="number" name="costo_total" step="0.01" class="form-control" required>
                    </div>
                    <div id="aviso_conversion" class="alert alert-info d-none">
                        Equivale a: <b id="valor_convertido"></b>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">PROCESAR ENTRADA</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // 1. BUSCADOR INSTANTÁNEO
    $("#buscador-tabla").on("input", function() {
        let valor = $(this).val().toLowerCase().trim();
        $(".fila-insumo").each(function() {
            let nombreInsumo = $(this).find(".nombre-txt").text().toLowerCase();
            $(this).toggle(nombreInsumo.includes(valor));
        });
    });

    // 2. CONFIGURACIÓN MODAL STOCK
    $('.btn-add-stock').on('click', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        const unidadBase = $(this).data('unidad');
        
        $('#insumo_id_input').val(id);
        $('#nombre_insumo_modal').text(nombre);
        $('#aviso_conversion').addClass('d-none');

        $('#select_unidad_ingreso option').show();
        if (unidadBase === 'unidad') {
            $('#select_unidad_ingreso').val('unidad');
            $('#select_unidad_ingreso option[value!="unidad"]').hide();
        } else if (unidadBase === 'ml') {
            $('#select_unidad_ingreso').val('ml');
            $('#select_unidad_ingreso option[value!="ml"]').hide();
        } else {
            $('#select_unidad_ingreso option[value="unidad"], #select_unidad_ingreso option[value="ml"]').hide();
            $('#select_unidad_ingreso').val('kg');
        }
    });

    // 3. CÁLCULO VISUAL DE KG A GR
    $('#input_cantidad, #select_unidad_ingreso').on('input change', function() {
        let cant = $('#input_cantidad').val();
        let unid = $('#select_unidad_ingreso').val();
        
        if(cant > 0 && unid === 'kg') {
            $('#aviso_conversion').removeClass('d-none');
            $('#valor_convertido').text((cant * 1000) + ' gr');
        } else {
            $('#aviso_conversion').addClass('d-none');
        }
    });
});
</script>
@endpush