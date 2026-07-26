@extends('layouts.admin')

@section('title', 'Categorías')
@section('header', 'Gestión de Categorías')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm" style="border-radius: 12px; border: none;">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold text-dark">
                            <i class="fas fa-tags mr-2 text-primary"></i> Listado de Categorías
                        </h3>
                        <button class="btn btn-success shadow-sm" data-toggle="modal" data-target="#modal-nueva-categoria" style="border-radius: 8px;">
                            <i class="fas fa-plus-circle mr-1"></i> Nueva Categoría
                        </button>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4" style="width: 80px">ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th class="text-center" style="width: 160px">Total Productos</th>
                                    <th class="text-center" style="width: 150px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categorias as $cat)
                                <tr>
                                    <td class="px-4 align-middle"><b>#{{ $cat->id }}</b></td>
                                    <td class="align-middle text-dark font-weight-bold">{{ $cat->nombre }}</td>
                                    <td class="align-middle text-muted">{{ $cat->descripcion ?? 'Sin descripción' }}</td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-pill badge-primary px-3 py-2" style="font-size: 0.85rem;">
                                            <i class="fas fa-box-open mr-1"></i> {{ $cat->productos_count ?? $cat->productos?->count() ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <form action="{{ route('categorias.destroy', $cat->id) }}" method="POST" class="form-eliminar d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-confirm-delete" data-nombre="{{ $cat->nombre }}" title="Eliminar Categoría" style="border-radius: 5px;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3 text-light"></i><br>
                                        No hay categorías registradas actualmente.
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

<div class="modal fade" id="modal-nueva-categoria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow border-0" style="border-radius: 15px;">
            <div class="modal-header bg-success text-white" style="border-radius: 15px 15px 0 0;">
                <h5 class="modal-title font-weight-bold">Registrar Nueva Categoría</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('categorias.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Nombre</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej. Bebidas, Postres..." required style="border-radius: 8px;">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Descripción (Opcional)</label>
                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Breve detalle de la categoría..." style="border-radius: 8px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success px-4" style="border-radius: 8px;">Guardar Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Hecho!',
                text: '{{ session("success") }}',
                timer: 2000,
                showConfirmButton: false,
                timerProgressBar: true
            });
        @endif

        $('.btn-confirm-delete').on('click', function(e) {
            let nombre = $(this).data('nombre');
            let form = $(this).closest('form');
            
            Swal.fire({
                title: '¿Eliminar "' + nombre + '"?',
                text: "Esta acción borrará la categoría de forma permanente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush