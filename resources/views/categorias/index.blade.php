@extends('layouts.admin')

@section('title', 'Categorías')
@section('header', 'Gestión de Categorías')

@push('css')
<style>
    .card-categoria {
        border-radius: 12px;
        border: 1px solid #e9ecef;
        transition: all 0.2s ease-in-out;
    }
    .card-categoria:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
    }
    .producto-chip {
        background-color: #f8f9fa;
        border: 1px solid #e3e6f0;
        border-left: 4px solid #17a2b8;
        border-radius: 10px;
        padding: 10px 14px;
        height: 100%;
        transition: transform 0.15s ease;
    }
    .producto-chip:hover {
        transform: translateY(-2px);
        background-color: #ffffff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .btn-toggle-accordion {
        cursor: pointer;
        text-decoration: none !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-tags mr-2 text-primary"></i> Categorías y Menú
            </h3>
            <button class="btn btn-success shadow-sm font-weight-bold" data-toggle="modal" data-target="#modal-nueva-categoria" style="border-radius: 8px;">
                <i class="fas fa-plus-circle mr-1"></i> NUEVA CATEGORÍA
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="accordion" id="accordionCategorias">
        @forelse($categorias as $index => $cat)
            <div class="card card-categoria shadow-sm mb-3">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center flex-grow-1 btn-toggle-accordion" data-toggle="collapse" data-target="#collapse-{{ $cat->id }}">
                        <i class="fas fa-folder text-warning fa-lg mr-3"></i>
                        <div>
                            <h5 class="mb-0 font-weight-bold text-dark">{{ $cat->nombre }}</h5>
                            <small class="text-muted">{{ $cat->descripcion ?? 'Sin descripción' }}</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <span class="badge badge-pill badge-primary px-3 py-2 mr-3" style="font-size: 0.85rem;">
                            <i class="fas fa-hamburger mr-1"></i> {{ $cat->productos->count() }} Productos
                        </span>

                        <form action="{{ route('categorias.destroy', $cat->id) }}" method="POST" class="d-inline mr-2">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-outline-danger btn-sm btn-confirm-delete" data-nombre="{{ $cat->nombre }}" title="Eliminar Categoría" style="border-radius: 6px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>

                        <button class="btn btn-sm btn-light text-muted border-0" data-toggle="collapse" data-target="#collapse-{{ $cat->id }}">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </div>

                {{-- Se despliega el primer elemento por defecto ($index === 0) --}}
                <div id="collapse-{{ $cat->id }}" class="collapse {{ $index === 0 ? 'show' : '' }}" data-parent="#accordionCategorias">
                    <div class="card-body bg-light pt-3 pb-4 border-top">
                        @if($cat->productos->count() > 0)
                            <div class="row">
                                @foreach($cat->productos as $prod)
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <div class="producto-chip d-flex flex-column justify-content-between">
                                            <div>
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <span class="font-weight-bold text-uppercase text-dark" style="font-size: 0.95rem;">
                                                        {{ $prod->nombre }}
                                                    </span>
                                                    <span class="badge badge-success px-2 py-1" style="font-size: 0.85rem;">
                                                        ${{ number_format($prod->precio, 2) }}
                                                    </span>
                                                </div>
                                                <p class="text-muted small mb-0 font-italic">
                                                    {{ $prod->descripcion ?? 'Sin ingredientes ni descripción registrada.' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3 text-muted small">
                                <i class="fas fa-info-circle mr-1"></i> No hay productos asignados a esta categoría todavía.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card card-categoria shadow-sm p-5 text-center text-muted">
                <i class="fas fa-folder-open fa-3x mb-3 text-light"></i>
                <h5>No hay categorías registradas.</h5>
            </div>
        @endforelse
    </div>
</div>

{{-- MODAL NUEVA CATEGORÍA --}}
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
            e.stopPropagation(); // Evita que se colapse/despliegue el acordeón al hacer clic en eliminar
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