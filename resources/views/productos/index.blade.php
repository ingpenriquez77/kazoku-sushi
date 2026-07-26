@extends('layouts.admin')

@section('title', 'Gestión de Productos')

@section('content_header')
    <h1>Catálogo de Productos Finales</h1>
@stop

@section('content')
<div class="row">
    {{-- BARRA SUPERIOR: BOTÓN Y BUSCADOR --}}
    <div class="col-md-12 mb-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <button class="btn btn-primary shadow-sm mb-2" data-toggle="modal" data-target="#modalProducto">
                <i class="fas fa-plus"></i> Nuevo Producto
            </button>

            <div class="input-group" style="width: 300px;">
                <input type="text" id="buscador-productos" class="form-control" placeholder="Escribe para buscar..." autocomplete="off">
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
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold text-uppercase text-muted">
                    <i class="fas fa-hamburger mr-2"></i> Gestión de Productos
                </h3>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>Nombre del Producto</th>
                                <th>Categoría</th>
                                <th>Descripción</th>
                                <th>Precio de Venta</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-productos-body">
                            @forelse($productos as $producto)
                            <tr class="fila-producto">
                                <td class="align-middle font-weight-bold text-uppercase nombre-prod">
                                    {{ $producto->nombre }}
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-info">
                                        {{-- Protección con operador null-safe ?-> --}}
                                        {{ $producto->categoria?->nombre ?? 'Sin Categoría' }}
                                    </span>
                                </td>
                                <td class="align-middle text-muted small">
                                    {{ $producto->descripcion ?? 'Sin descripción' }}
                                </td>
                                <td class="align-middle text-success font-weight-bold">
                                    ${{ number_format($producto->precio, 2) }}
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group shadow-sm">
                                        {{-- BOTÓN RECETA --}}
                                        <a href="{{ route('recetas.index', $producto->id) }}" class="btn btn-sm btn-warning" title="Gestionar Receta">
                                            <i class="fas fa-utensils"></i>
                                        </a>

                                        {{-- BOTÓN EDITAR --}}
                                        <button class="btn btn-sm btn-info btn-edit"
                                                data-id="{{ $producto->id }}"
                                                data-nombre="{{ $producto->nombre }}"
                                                data-categoria_id="{{ $producto->categoria_id ?? '' }}"
                                                data-descripcion="{{ $producto->descripcion }}"
                                                data-precio="{{ $producto->precio }}"
                                                data-toggle="modal" data-target="#modalEditProducto"
                                                title="Editar Producto">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- BOTÓN ELIMINAR --}}
                                        <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este producto?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger ml-1" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-info-circle mr-1"></i> No hay productos registrados.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Mostrando registros del <b>{{ $productos->firstItem() ?? 0 }}</b> al <b>{{ $productos->lastItem() ?? 0 }}</b>
                        de un total de <b>{{ $productos->total() }}</b>
                    </div>
                    <div class="pagination-sm">
                        {{ $productos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: NUEVO PRODUCTO --}}
<div class="modal fade" id="modalProducto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">Nuevo Producto</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('productos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Categoría</label>
                        <select name="categoria_id" class="form-control" required>
                            <option value="" disabled selected>-- Seleccione una categoría --</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nombre del Producto</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Chile Relleno" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="2" placeholder="Breve descripción..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Precio de Venta ($)</label>
                        <input type="number" name="precio" step="0.01" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block font-weight-bold">GUARDAR PRODUCTO</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: EDITAR PRODUCTO --}}
<div class="modal fade" id="modalEditProducto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title font-weight-bold">Editar Producto</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditProducto" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Categoría</label>
                        <select name="categoria_id" id="edit_categoria_id" class="form-control" required>
                            <option value="" disabled>-- Seleccione una categoría --</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nombre del Producto</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Precio de Venta ($)</label>
                        <input type="number" name="precio" id="edit_precio" step="0.01" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-block font-weight-bold text-white">ACTUALIZAR CAMBIOS</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // 1. Buscador instantáneo
    $("#buscador-productos").on("input", function() {
        let valor = $(this).val().toLowerCase().trim();
        $(".fila-producto").each(function() {
            let nombre = $(this).find(".nombre-prod").text().toLowerCase();
            $(this).toggle(nombre.includes(valor));
        });
    });

    // 2. Llenar modal de edición dinámicamente con categoría
    $('.btn-edit').on('click', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        const categoria_id = $(this).data('categoria_id');
        const descripcion = $(this).data('descripcion');
        const precio = $(this).data('precio');

        $('#formEditProducto').attr('action', '/productos/' + id);
        $('#edit_nombre').val(nombre);
        $('#edit_categoria_id').val(categoria_id);
        $('#edit_descripcion').val(descripcion);
        $('#edit_precio').val(precio);
    });
});
</script>
@endpush