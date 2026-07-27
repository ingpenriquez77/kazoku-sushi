@extends('layouts.admin')

@section('title', 'Cierre de Turno (Corte X)')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="m-0 text-dark">Cierre de Turno / Cajero (Corte X)</h3>
            <span class="badge badge-success px-3 py-2 font-weight-bold">TURNO ACTIVO</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">
        {{-- RESUMEN DE VENTAS DEL TURNO --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-calculator mr-2"></i> Resumen de Ventas del Turno</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-4">
                        <div class="col-md-4 mb-2">
                            <div class="p-3 bg-light rounded border">
                                <small class="text-muted d-block text-uppercase font-weight-bold">Fondo Inicial</small>
                                <span class="h4 font-weight-bold text-dark">${{ number_format($turnoActivo->monto_apertura, 2) }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="p-3 bg-light rounded border">
                                <small class="text-muted d-block text-uppercase font-weight-bold">Ventas Efectivo</small>
                                <span class="h4 font-weight-bold text-success">${{ number_format($totalEfectivo, 2) }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="p-3 bg-dark text-white rounded border">
                                <small class="text-white-50 d-block text-uppercase font-weight-bold">Efectivo Esperado en Caja</small>
                                <span class="h4 font-weight-bold text-warning">${{ number_format($efectivoEsperado, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row text-center border-top pt-3">
                        <div class="col-6 border-right">
                            <small class="text-muted d-block font-weight-bold">TARJETA</small>
                            <span class="h5 font-weight-bold text-primary">${{ number_format($totalTarjeta, 2) }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block font-weight-bold">TRANSFERENCIA</small>
                            <span class="h5 font-weight-bold text-info">${{ number_format($totalTransferencia, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FORMULARIO DE CUADRE DE CAJA --}}
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="m-0 font-weight-bold"><i class="fas fa-cash-register mr-2"></i> Realizar Arqueo y Cierre de Turno</h5>
                </div>
                <form action="{{ route('caja.cerrar') }}" method="POST" onsubmit="return confirm('¿Está seguro de cerrar su turno? Esta acción no se puede deshacer.')">
                    @csrf
                    <input type="hidden" name="turno_id" value="{{ $turnoActivo->id }}">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Efectivo Físico Contado en Caja ($) <span class="text-danger">*</span></label>
                            <input type="number" name="monto_efectivo_real" id="efectivo_real" step="0.01" min="0" class="form-control form-control-lg font-weight-bold text-center" placeholder="0.00" required>
                            <small class="text-muted">Cuente todo el dinero en efectivo que tiene en el cajón (incluyendo el fondo inicial).</small>
                        </div>

                        <div id="alerta_diferencia" class="alert py-3 text-center d-none">
                            <h5 class="mb-0 font-weight-bold" id="texto_diferencia"></h5>
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold">Observaciones / Notas del Cierre</label>
                            <textarea name="observaciones" class="form-control" rows="2" placeholder="Opcional: Explicación si hubo sobrante/faltante o incidencias..."></textarea>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <button type="submit" class="btn btn-danger btn-block btn-lg font-weight-bold shadow">
                            <i class="fas fa-lock mr-2"></i> EJECUTAR CIERRE DE TURNO
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- INFORMACIÓN LATERAL --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                <div class="card-header bg-secondary text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle mr-1"></i> Datos de la Sesión</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Cajero:</span>
                            <b>{{ $turnoActivo->cajero_nombre }}</b>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Apertura:</span>
                            <b>{{ \Carbon\Carbon::parse($turnoActivo->fecha_apertura)->format('d/m/Y H:i') }}</b>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Tickets Cobrados:</span>
                            <b class="text-success">{{ count($ventasTurno) }}</b>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Total Recaudado:</span>
                            <b class="text-primary">${{ number_format($totalVentas, 2) }}</b>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card bg-info text-white shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body">
                    <h5><i class="fas fa-lightbulb mr-2"></i> Recomendación de Arqueo</h5>
                    <p class="small mb-0">Resta el dinero base ($<b>{{ number_format($turnoActivo->monto_apertura, 2) }}</b>) al finalizar tu turno y entrega el remanente en bolsa de depósito.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        const esperado = {{ $efectivoEsperado }};

        $('#efectivo_real').on('input', function() {
            let real = parseFloat($(this).val()) || 0;
            let dif = real - esperado;
            let alerta = $('#alerta_diferencia');
            let txt = $('#texto_diferencia');

            alerta.removeClass('d-none alert-success alert-danger alert-warning');

            if (dif === 0) {
                alerta.addClass('alert-success');
                txt.text('¡Caja Cuadrada Perfectamente! ($0.00)');
            } else if (dif > 0) {
                alerta.addClass('alert-warning');
                txt.text('Sobrante en Caja: +$' + dif.toFixed(2));
            } else {
                alerta.addClass('alert-danger');
                txt.text('Faltante en Caja: -$' + Math.abs(dif).toFixed(2));
            }
        });
    });
</script>
@endpush