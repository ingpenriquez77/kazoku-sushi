@extends('layouts.admin')

@section('title', 'Datos del Negocio')
@section('header', 'Configuración Fiscal y de Ticket')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-dark">
                <h3 class="card-title"><i class="fas fa-store mr-2"></i> Información General del Establecimiento</h3>
            </div>
            <form action="{{ route('datos_negocio.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close text-white">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Nombre Comercial <span class="text-danger">*</span></label>
                            <input type="text" name="nombre_comercial" class="form-control" value="{{ $datos->nombre_comercial ?? '' }}" required placeholder="Ej: Kazoku Sushi">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Moneda / Símbolo</label>
                            <input type="text" name="moneda" class="form-control" value="{{ $datos->moneda ?? '$' }}" required maxlength="5">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Razón Social</label>
                            <input type="text" name="razon_social" class="form-control" value="{{ $datos->razon_social ?? '' }}" placeholder="Nombre legal del negocio">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>NIT / RUT / RFC</label>
                            <input type="text" name="nit_rut" class="form-control" value="{{ $datos->nit_rut ?? '' }}" placeholder="Identificación tributaria">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Teléfono de Contacto</label>
                            <input type="text" name="telefono" class="form-control" value="{{ $datos->telefono ?? '' }}" placeholder="Ej: +56 9 1234 5678">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Dirección Física</label>
                            <input type="text" name="direccion" class="form-control" value="{{ $datos->direccion ?? '' }}" placeholder="Calle, Número, Ciudad">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Mensaje al pie del Ticket</label>
                        <textarea name="mensaje_ticket" class="form-control" rows="3" placeholder="Mensaje de despedida o información legal adicional...">{{ $datos->mensaje_ticket ?? '' }}</textarea>
                        <small class="text-muted text-italic">Este mensaje aparecerá al final de cada ticket impreso.</small>
                    </div>
                </div>
                <div class="card-footer text-right bg-light">
                    <button type="submit" class="btn btn-primary px-5 shadow-sm">
                        <i class="fas fa-save mr-2"></i> Guardar Configuración
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection