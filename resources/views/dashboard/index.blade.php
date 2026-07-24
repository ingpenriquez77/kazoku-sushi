@extends('layouts.admin')

@section('title', 'Dashboard')

@push('css')
<style>
    /* Ajustes específicos para emular el estilo exacto de AdminLTE 3 v3 */
    .info-box {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: 0.25rem;
        background-color: #fff;
        display: flex;
        margin-bottom: 1rem;
        min-height: 80px;
        padding: .5rem;
        position: relative;
        width: 100%;
    }
    .products-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .products-list > .item {
        border-radius: 0.25rem;
        background-color: #fff;
        padding: 10px 0;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .products-list > .item:last-of-type {
        border-bottom: 0;
    }
    .product-description {
        color: #6c757d;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">VENTAS DEL MES</span>
                    <span class="info-box-number">
                        ${{ number_format($stats['ventas_mes'], 2) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-utensils"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">PEDIDOS PENDIENTES</span>
                    <span class="info-box-number">{{ $stats['nuevos_pedidos'] }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-box"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">PRODUCTOS</span>
                    <span class="info-box-number">{{ $stats['productos_total'] }}</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">MESAS ACTIVAS</span>
                    <span class="info-box-number">{{ $stats['mesas_activas'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-info shadow">
                <div class="card-header border-transparent">
                    <h3 class="card-title font-weight-bold">Últimos Pedidos Realizados</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0 table-hover table-valign-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Mesa</th>
                                    <th>Mesero</th>
                                    <th>Estado</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimos_pedidos as $pedido)
                                <tr>
                                    <td><span class="text-primary font-weight-bold">{{ $pedido->codigo_pedidido }}</span></td>
                                    <td>{{ $pedido->mesa }}</td>
                                    <td>{{ $pedido->mesero ?? 'N/A' }}</td>
                                    <td>
                                        @if($pedido->estado == 'Pagado')
                                            <span class="badge badge-success px-2 py-1">Pagado</span>
                                        @elseif($pedido->estado == 'pendiente')
                                            <span class="badge badge-warning px-2 py-1">En Curso</span>
                                        @else
                                            <span class="badge badge-secondary px-2 py-1">{{ $pedido->estado }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="font-weight-bold text-dark">${{ number_format($pedido->total, 2) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No se encontraron pedidos recientes.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header border-transparent">
                    <h3 class="card-title font-weight-bold text-uppercase" style="font-size: 0.9rem;">Los más vendidos</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card px-3">
                        @forelse($top_productos as $tp)
                        <li class="item">
                            <div class="product-info ml-0">
                                <a href="javascript:void(0)" class="product-title text-dark font-weight-bold">
                                    {{ $tp->nombre }}
                                    <span class="badge badge-success float-right">${{ number_format($tp->precio, 2) }}</span>
                                </a>
                                <span class="product-description">
                                    Total vendido: {{ $tp->total_vendido }} unidades
                                </span>
                            </div>
                        </li>
                        @empty
                        <li class="item text-center py-3 text-muted">No hay ventas registradas.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <small class="text-muted text-uppercase font-weight-bold">Análisis del mes en curso</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection