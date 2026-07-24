@extends('layouts.admin')

@section('title', 'Corte de Caja Z')

@section('header', 'Cierre de Caja (Corte Z)')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- Resumen de Ventas --}}
        <div class="col-md-8">
            <div class="card card-outline card-danger shadow">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold text-danger">
                        <i class="fas fa-cash-register mr-2"></i> Resumen de Ventas del Turno
                    </h3>
                </div>
                <div class="card-body">
                    {{-- Alerta de Mesas Abiertas --}}
                    @if($mesasAbiertas > 0)
                        <div class="alert alert-warning border-0 shadow-sm">
                            <h5><i class="icon fas fa-exclamation-triangle"></i> ¡Atención!</h5>
                            Hay <strong>{{ $mesasAbiertas }}</strong> comanda(s) aún abiertas. Debes cerrarlas o cancelarlas antes de realizar el Corte Z.
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="info-box shadow-sm border">
                                <span class="info-box-icon bg-success"><i class="fas fa-money-bill-wave"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted">Efectivo</span>
                                    <span class="info-box-number text-success font-weight-bold">
                                        ${{ number_format($resumen->efectivo, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="info-box shadow-sm border">
                                <span class="info-box-icon bg-info"><i class="fas fa-credit-card"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted">Tarjeta</span>
                                    <span class="info-box-number text-info font-weight-bold">
                                        ${{ number_format($resumen->tarjeta, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="info-box shadow-sm border">
                                <span class="info-box-icon bg-primary"><i class="fas fa-university"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted">Transferencia</span>
                                    <span class="info-box-number text-primary font-weight-bold">
                                        ${{ number_format($resumen->transferencia, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-hover border">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3">Concepto</th>
                                    <th class="text-right py-3">Monto Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="align-middle">
                                        <i class="fas fa-receipt mr-2 text-muted"></i> 
                                        Ventas Realizadas ({{ $resumen->conteo }} tickets)
                                    </td>
                                    <td class="text-right align-middle">
                                        <h4 class="mb-0 font-weight-bold">${{ number_format($resumen->gran_total, 2) }}</h4>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white py-4">
                    {{-- El action debe coincidir con el nombre de tu ruta en web.php --}}
                    <form action="{{ route('corte.procesar') }}" method="POST" onsubmit="return confirm('¿Confirmas el cierre de caja? Esto liberará todas las mesas y guardará el reporte diario.')">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-xl btn-block py-3 font-weight-bold shadow-sm">
                            <i class="fas fa-lock mr-2"></i> EJECUTAR CORTE Z (CIERRE DIARIO)
                        </button>
                    </form>
                    <p class="text-center text-muted small mt-3">
                        <i class="fas fa-info-circle mr-1"></i> 
                        Esta acción es irreversible y reseteará los totales del Dashboard.
                    </p>
                </div>
            </div>
        </div>

        {{-- Panel Lateral Informativo --}}
        <div class="col-md-4">
            <div class="card card-dark shadow">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i> Información del Cierre</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item d-flex justify-content-between">
                            <b>Fecha de Cierre</b> <span>{{ date('d/m/Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <b>Hora actual</b> <span>{{ date('H:i A') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between border-bottom-0">
                            <b>Cajero responsable</b> <span class="badge badge-info">{{ Auth::user()->name }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="alert alert-info shadow-sm">
                <h5><i class="icon fas fa-lightbulb"></i> Tip de Caja</h5>
                Asegúrate de contar físicamente el efectivo antes de realizar el corte para evitar descuadres en el reporte final.
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .info-box-number { font-size: 1.6rem !important; }
    .btn-xl { font-size: 1.25rem; border-radius: 12px; }
    .card { border-radius: 15px; }
    .info-box { border-radius: 10px; }
</style>
@endpush