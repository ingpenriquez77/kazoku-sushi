@extends('layouts.admin')

@section('title', 'Apertura de Caja')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 style="border-radius: 15px;">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="m-0 font-weight-bold"><i class="fas fa-lock-open mr-2"></i> Apertura de Turno de Caja</h4>
                </div>
                <form action="{{ route('caja.abrir') }}" method="POST">
                    @csrf
                    <div class="card-body p-4">
                        <div class="alert alert-info">
                            <i class="fas fa-user mr-1"></i> Cajero Responsable: <b>{{ $userNombre }}</b><br>
                            <small>Ingrese el saldo inicial en efectivo asignado para dar cambio.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Fondo Inicial de Caja ($)</label>
                            <input type="number" name="monto_apertura" step="0.01" min="0" class="form-control form-control-lg text-center font-weight-bold" value="0.00" required autofocus>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-center py-3">
                        <button type="submit" class="btn btn-success btn-lg btn-block font-weight-bold shadow">
                            <i class="fas fa-check-circle mr-1"></i> ABRIR CAJA
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection