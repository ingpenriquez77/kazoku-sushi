@extends('layouts.admin')

@section('title', 'Cierre de Caja (Corte Z)')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="m-0 text-dark">Cierre General de Caja (Corte Z)</h3>
            <span class="badge badge-dark px-3 py-2 font-weight-bold">CONSOLIDADOR DIARIO</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    {{-- ALERTA DE CAJEROS PENDIENTES --}}
    @if(!$puedoCerrarZ)
        <div class="alert alert-warning border-left shadow-sm mb-4" style="border-left-width: 5px !important;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x mr-3 text-warning"></i>
                <div>
                    <h5 class="font-weight-bold mb-1">Cierre Z Bloqueado</h5>
                    <p class="mb-0 small">Hay <b>{{ $turnosAbiertos->count() }} cajero(s)</b> con turnos activos. Todos los cajeros deben ejecutar su <b>Corte X</b> antes de poder consolidar el día.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        {{-- RESUMEN ACUMULADO DEL DÍA --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-cash-register mr-2"></i> Resumen General de Ventas (Tiempo Real)</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="p-3 bg-light rounded border">
                                <small class="text-muted d-block text-uppercase font-weight-bold">Efectivo</small>
                                <span class="h4 font-weight-bold text-success">${{ number_format($totalEfectivo, 2) }}</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded border">
                                <small class="text-muted d-block text-uppercase font-weight-bold">Tarjeta</small>
                                <span class="h4 font-weight-bold text-primary">${{ number_format($totalTarjeta, 2) }}</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded border">
                                <small class="text-muted d-block text-uppercase font-weight-bold">Transferencia</small>
                                <span class="h4 font-weight-bold text-info">${{ number_format($totalTransferencia, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-dark text-white p-3 rounded text-center mb-3">
                        <small class="text-white-50 text-uppercase font-weight-bold d-block">Total Recaudado Hoy</small>
                        <h2 class="font-weight-bold m-0 text-warning">${{ number_format($totalGeneral, 2) }}</h2>
                    </div>
                </div>
            </div>

            {{-- TABLA ESTADO DE CAJEROS DEL DÍA --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-users mr-1"></i> Estado de Turnos de Cajeros (Hoy)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light small text-uppercase">
                                <tr>
                                    <th>Cajero</th>
                                    <th>Apertura</th>
                                    <th>Fondo</th>
                                    <th>Diferencia</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($turnosDelDia as $t)
                                <tr>
                                    <td class="font-weight-bold align-middle">{{ $t->cajero_nombre }}</td>
                                    <td class="align-middle small">{{ \Carbon\Carbon::parse($t->fecha_apertura)->format('H:i A') }}</td>
                                    <td class="align-middle">${{ number_format($t->monto_apertura, 2) }}</td>
                                    <td class="align-middle">
                                        @if($t->estado == 'cerrada')
                                            <span class="font-weight-bold {{ $t->diferencia < 0 ? 'text-danger' : ($t->diferencia > 0 ? 'text-warning' : 'text-success') }}">
                                                ${{ number_format($t->diferencia, 2) }}
                                            </span>
                                        @else
                                            <span class="text-muted">En proceso...</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if($t->estado == 'abierta')
                                            <span class="badge badge-warning px-2">TURNO ACTIVO</span>
                                        @else
                                            <span class="badge badge-success px-2">CORTE X LISTO</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No hay turnos registrados el día de hoy.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- BOTÓN DE CIERRE DEFINITIVO Z --}}
            <form action="{{ route('caja.cerrar_z') }}" method="POST" onsubmit="return confirm('¿Confirmar el Cierre General Z del día?')">
                @csrf
                <button type="submit" class="btn btn-danger btn-block btn-lg font-weight-bold shadow py-3" {{ !$puedoCerrarZ ? 'disabled' : '' }}>
                    <i class="fas fa-lock mr-2"></i> EJECUTAR CORTE Z (CIERRE DIARIO DEFINITIVO)
                </button>
                @if(!$puedoCerrarZ)
                    <small class="text-danger d-block text-center mt-2 font-weight-bold">
                        * Debe esperar a que todos los cajeros realicen su Corte X para habilitar este botón.
                    </small>
                @endif
            </form>
        </div>

        {{-- PANEL INFORMATIVO --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 style="border-radius: 12px;">
                <div class="card-header bg-secondary text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle mr-1"></i> Información del Cierre</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2"><b>Fecha de Cierre:</b> {{ date('d/m/Y') }}</p>
                    <p class="small text-muted mb-2"><b>Tickets Registrados Hoy:</b> {{ count($ventasHoy) }}</p>
                    <hr>
                    <small class="text-muted">
                        <i class="fas fa-lightbulb text-warning mr-1"></i> El <b>Corte Z</b> resetea los indicadores del Dashboard diario y consolida la contabilidad final del negocio.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection