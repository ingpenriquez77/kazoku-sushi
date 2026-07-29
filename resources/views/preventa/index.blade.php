@extends('layouts.admin')

@section('title', 'Comandas')

@section('content_header')
    <h1>Gestión de Comandas - Kazoku Sushi</h1>
@stop

@push('css')
<style>
    .select2-container { width: 100% !important; z-index: 9999 !important; }
    .card-mesa {
        position: relative; border-radius: 12px; border-left: 5px solid #ffc107 !important;
        transition: transform 0.2s; background-color: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.2);
    }
    .card-mesa:hover { transform: translateY(-5px); }
    .btn-cancelar-comanda {
        position: absolute; top: -10px; right: -10px; background: #dc3545; color: white;
        border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center;
        justify-content: center; font-size: 14px; border: 2px solid white; cursor: pointer; z-index: 10;
    }
    .lista-comanda-previa {
        font-size: 0.85rem; max-height: 200px; overflow-y: auto;
        background: #f4f6f9; border: 1px solid #dee2e6; border-radius: 8px; padding: 10px;
    }
    .item-comanda { border-bottom: 1px dashed #dee2e6; padding: 5px 0; position: relative; }
    .item-comentario { font-size: 0.75rem; color: #6c757d; font-style: italic; display: block; line-height: 1.2; margin-top: 2px; }
    .item-precio-unit { font-size: 0.75rem; font-weight: bold; color: #28a745; }
    
    .btn-quitar-item {
        color: #dc3545; cursor: pointer; padding: 2px 5px; border-radius: 4px;
        transition: background 0.2s;
    }
    .btn-quitar-item:hover { background: #ffe6e6; }

    .ticket-visual {
        background: #fff; border: 1px solid #ddd; padding: 15px;
        font-family: 'Courier New', Courier, monospace; color: #000;
    }
    .total-display { font-size: 2.5rem; font-weight: 800; color: #28a745; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="m-0 text-dark">Comandas Activas</h3>
            <button class="btn btn-success elevation-2 font-weight-bold" data-toggle="modal" data-target="#modalNuevaMesa">
                <i class="fas fa-plus-circle mr-1"></i> ABRIR COMANDA
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">
        @forelse($ventas_pendientes as $venta)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="card card-mesa h-100 shadow-sm">
                    <button class="btn-cancelar-comanda" onclick="confirmarCancelarComanda('{{ $venta->id }}', '{{ $venta->mesa }}')" title="Anular Comanda Completa">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="card-body p-3 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="font-weight-bold m-0 text-dark">{{ $venta->mesa }}</h5>
                            <small class="text-muted font-weight-bold text-uppercase">
                                <i class="fas fa-user-tag mr-1"></i> {{ $venta->mesero }}
                            </small>
                        </div>
                        <h4 class="text-success font-weight-bold mb-3">${{ number_format($venta->total, 2) }}</h4>

                        <div class="lista-comanda-previa mb-3 flex-grow-1">
                            @php $tieneDetalle = false; @endphp
                            @foreach($detalles_totales as $det)
                                @if($det->venta_id == $venta->id)
                                    @php $tieneDetalle = true; @endphp
                                    <div class="item-comanda"
                                         data-nombre="{{ $det->nombre }}"
                                         data-cantidad="{{ $det->cantidad }}"
                                         data-precio="{{ $det->precio }}"
                                         data-comentario="{{ $det->comentario }}">

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>
                                                <i class="fas fa-minus-circle btn-quitar-item mr-1" onclick="confirmarCancelarDetalle('{{ $det->id }}', '{{ $det->nombre }}')" title="Cancelar este producto"></i>
                                                <span class="font-weight-bold text-primary">{{ $det->cantidad }}x</span> {{ $det->nombre }}
                                            </span>
                                            <span class="item-precio-unit">${{ number_format($det->precio * $det->cantidad, 2) }}</span>
                                        </div>
                                        @if($det->comentario)
                                            <span class="item-comentario pl-3"><i class="fas fa-sticky-note mr-1"></i> {{ $det->comentario }}</span>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                            @if(!$tieneDetalle) <div class="text-muted text-center small py-2">Mesa vacía</div> @endif
                        </div>

                        <div class="row no-gutters">
                            <div class="col-6 pr-1">
                                <button type="button" class="btn btn-primary btn-block btn-sm font-weight-bold btn-agregar"
                                        data-id="{{ $venta->id }}" data-mesa="{{ $venta->mesa }}">
                                    <i class="fas fa-utensils mr-1"></i> COMANDAR
                                </button>
                            </div>
                            <div class="col-6 pl-1">
                                <button type="button" class="btn btn-success btn-block btn-sm font-weight-bold"
                                        onclick="abrirVistaTicket('{{ $venta->id }}', '{{ $venta->mesa }}', {{ $venta->total }})">
                                    <i class="fas fa-cash-register mr-1"></i> PAGAR
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">No hay cuentas abiertas.</div>
        @endforelse
    </div>
</div>

{{-- MODAL NUEVA MESA --}}
<div class="modal fade" id="modalNuevaMesa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('preventa.store') }}" method="POST">
                @csrf
                <div class="modal-body pt-4 text-center">
                    <label class="font-weight-bold">NOMBRE DE MESA</label>
                    <input type="text" name="mesa" class="form-control form-control-lg text-uppercase text-center" required placeholder="EJ: MESA 5">
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-success btn-block font-weight-bold">ABRIR CUENTA</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL COMANDAR --}}
<div class="modal fade" id="modalAgregarProducto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title font-weight-bold" id="labelMesa">Comandar</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body bg-light">
                <div class="row">
                    <div class="col-md-7 border-right">
                        <div class="row mb-3">
                            <div class="col-8">
                                <label class="small font-weight-bold">PRODUCTO</label>
                                <select id="select-producto" class="form-control select2">
                                    <option value=""></option>
                                    @foreach($productos as $p)
                                        <option value="{{ $p->id }}" data-nombre="{{ $p->nombre }}" data-precio="{{ $p->precio }}">
                                            {{ $p->nombre }} - ${{ number_format($p->precio, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="small font-weight-bold">CANT.</label>
                                <input type="number" id="input-cantidad" value="1" min="1" class="form-control text-center font-weight-bold">
                            </div>
                        </div>
                        <div id="contenedor-ingredientes" class="card mb-3 shadow-sm" style="display:none;">
                            <div class="card-body py-2" id="lista-checkbox-ingredientes"></div>
                        </div>
                        <div id="seccion-detalles-producto" style="display: none;">
                            <label class="small font-weight-bold">NOTAS / OBSERVACIONES</label>
                            <textarea id="input-comentario" class="form-control mb-3" rows="2" placeholder="Ej: Sin cebolla..."></textarea>
                            <button type="button" id="btn-confirmar-item" class="btn btn-info btn-block text-white font-weight-bold">AÑADIR A LA ORDEN</button>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="ticket-visual bg-white shadow-sm" style="min-height: 250px;">
                            <div class="text-center border-bottom mb-2 pb-2">
                                <h6 class="font-weight-bold mb-0">ORDEN ACTUAL</h6>
                                <small id="ticket-mesa-nombre">---</small>
                            </div>
                            <div id="ticket-items"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <form id="form-comanda" action="{{ route('preventa.agregar') }}" method="POST" class="w-100">
                    @csrf
                    <input type="hidden" name="venta_id" id="modal_venta_id">
                    <div id="inputs-ocultos"></div>
                    <button type="button" id="btn-enviar-cocina" class="btn btn-success btn-lg btn-block font-weight-bold disabled" disabled>ENVIAR A COCINA</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TICKET (VISTA PREVIA) --}}
<div class="modal fade" id="modalTicket" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-4 bg-light">
                <div id="ticket-print-area" class="ticket-visual shadow-sm">
                    <div class="text-center mb-2">
                        <img src="{{ asset('img/kazoku.png') }}" alt="Logo" class="img-fluid mb-2" style="max-height: 70px; filter: grayscale(100%);">
                        <h5 class="font-weight-bold mb-0" id="tk-nombre-negocio">KAZOKU SUSHI</h5>
                        <div id="tk-razon-social" class="small"></div>
                        <div id="tk-nit" class="small"></div>
                        <div id="tk-direccion" class="small"></div>
                        <div id="tk-telefono" class="small mb-2"></div>
                    </div>
                    <div class="border-top border-bottom py-1 text-left mb-2" style="font-size: 11px">
                        <b>MESA:</b> <span id="tk-mesa"></span><br>
                        <b>FECHA:</b> {{ date('d/m/Y H:i') }}
                    </div>
                    <div id="tk-items-lista" class="text-left mb-3" style="font-size: 12px"></div>
                    <div class="border-top pt-2 text-right">
                        <h5 class="font-weight-bold">TOTAL: <span id="tk-total"></span></h5>
                    </div>
                    <div class="text-center mt-3 small italic" id="tk-mensaje" style="font-size: 10px"></div>
                </div>
            </div>
            <div class="modal-footer flex-column border-0">
                <button type="button" class="btn btn-success btn-block font-weight-bold py-2 shadow" id="btn-ir-a-cobro">
                    PROCEDER AL COBRO <i class="fas fa-arrow-right ml-1"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary btn-block btn-sm" data-dismiss="modal">CANCELAR</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL COBRO --}}
<div class="modal fade" id="modalCobro" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold">Finalizar Venta</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('preventa.finalizar') }}" method="POST">
                @csrf
                <input type="hidden" name="venta_id" id="cobro_venta_id">
                <input type="hidden" name="total_pagar" id="cobro_total_input">
                <div class="modal-body text-center">
                    <h6 class="text-muted">TOTAL A PAGAR</h6>
                    <div class="total-display mb-4">$<span id="cobro_total_display">0.00</span></div>
                    
                    <div class="form-group text-left">
                        <label>Método de Pago</label>
                        <select name="metodo_pago" id="metodo_pago" class="form-control form-control-lg">
                            <option value="Efectivo">Efectivo</option>
                            <option value="Transferencia">Transferencia</option>
                            <option value="Tarjeta">Tarjeta</option>
                        </select>
                    </div>

                    <div class="form-group text-left" id="div_referencia" style="display: none;">
                        <label id="lbl_referencia">Código de Autorización / Folio</label>
                        <input type="text" name="referencia_pago" id="referencia_pago" class="form-control form-control-lg" placeholder="Ej: 849204">
                    </div>

                    <div class="form-group text-left" id="div_pago_con">
                        <label>Efectivo Recibido</label>
                        <input type="number" name="pago_con" id="pago_con" class="form-control form-control-lg" step="0.01" required>
                    </div>

                    <div class="alert alert-warning py-3" id="div_cambio" style="display:none;">
                        <h4 class="mb-0 font-weight-bold">Cambio: $<span id="cambio_display">0.00</span></h4>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-success btn-block btn-lg font-weight-bold shadow">CONFIRMAR PAGO</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="form-cancelar-comanda" method="POST" style="display:none;">@csrf @method('DELETE')</form>
<form id="form-cancelar-detalle" method="POST" style="display:none;">@csrf @method('DELETE')</form>
@endsection

@push('js')
<!-- SweetAlert2 Plugin -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let productosTemporal = [];
    let datosMesaActual = {};

    const cajaAbiertaStatus = @json($cajaAbierta ?? false);

    $(document).ready(function() {
        $('#modalNuevaMesa form').on('submit', function(e) {
            if (!cajaAbiertaStatus) {
                e.preventDefault();
                $('#modalNuevaMesa').modal('hide');

                Swal.fire({
                    icon: 'warning',
                    title: '¡Caja Cerrada!',
                    text: 'Debes realizar la apertura de turno en Caja antes de poder abrir comandas o mesas.',
                    confirmButtonText: '<i class="fas fa-cash-register mr-1"></i> IR A ABRIR CAJA',
                    confirmButtonColor: '#28a745',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('caja.corte_x') }}";
                    }
                });
            }
        });

        @if(session('caja_cerrada'))
            Swal.fire({
                icon: 'warning',
                title: 'Aviso de Caja',
                text: 'No hay ninguna caja abierta en este momento.',
                confirmButtonText: '<i class="fas fa-cash-register mr-1"></i> IR A ABRIR CAJA',
                confirmButtonColor: '#28a745',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('caja.corte_x') }}";
                }
            });
        @endif

        $('.select2').select2({ placeholder: "Buscar producto...", theme: 'bootstrap4' });

        $(document).on('click', '.btn-agregar', function() {
            let id = $(this).data('id');
            let mesa = $(this).data('mesa');

            $('#modal_venta_id').val(id);
            $('#labelMesa').text("Comandar: " + mesa);
            $('#ticket-mesa-nombre').text(mesa);

            productosTemporal = [];
            actualizarTicket();
            $('#select-producto').val(null).trigger('change');
            $('#contenedor-ingredientes').hide();
            $('#seccion-detalles-producto').hide();

            $('#modalAgregarProducto').modal('show');
        });

        $('#select-producto').on('change', function() {
            let pid = $(this).val();
            if(pid) {
                $('#contenedor-ingredientes').show();
                $.get('/preventa/insumos/' + pid, function(data) {
                    let html = '';
                    if(data.insumos && data.insumos.length > 0) {
                        html += '<p class="badge badge-secondary w-100">Ingredientes</p>';
                        data.insumos.forEach(i => html += `<div class="small d-inline-block mr-3"><input type="checkbox" class="check-ingrediente" value="${i.nombre}" checked> ${i.nombre}</div>`);
                    }
                    if(data.extras && data.extras.length > 0) {
                        html += '<p class="badge badge-success w-100 mt-2">Extras</p>';
                        data.extras.forEach(e => html += `<div class="small d-inline-block mr-3"><input type="checkbox" class="check-extra" value="${e.nombre}" data-precio="${e.precio}"> +${e.nombre} ($${e.precio})</div>`);
                    }
                    $('#lista-checkbox-ingredientes').html(html || '<div class="text-muted small">Sin adicionales.</div>');
                    $('#seccion-detalles-producto').show();
                }).fail(function() {
                    $('#lista-checkbox-ingredientes').html('<div class="text-muted small">Sin adicionales.</div>');
                    $('#seccion-detalles-producto').show();
                });
            } else {
                $('#contenedor-ingredientes').hide();
                $('#seccion-detalles-producto').hide();
            }
        });

        $('#btn-confirmar-item').on('click', function() {
            const select = $('#select-producto');
            const dataP = select.find('option:selected').data();
            if(!dataP || !dataP.nombre) return;

            let precioU = parseFloat(dataP.precio);
            let nota = [];
            $('.check-ingrediente:not(:checked)').each(function() { nota.push("SIN " + $(this).val()); });
            $('.check-extra:checked').each(function() {
                nota.push("CON " + $(this).val());
                precioU += parseFloat($(this).data('precio'));
            });
            if($('#input-comentario').val().trim()) nota.push($('#input-comentario').val().trim());

            productosTemporal.push({
                id: select.val(),
                nombre: dataP.nombre,
                cantidad: $('#input-cantidad').val(),
                comentario: nota.join(' | '),
                precio: precioU
            });

            actualizarTicket();

            select.val(null).trigger('change');
            $('#input-comentario').val('');
            $('#input-cantidad').val(1);
            $('#contenedor-ingredientes').hide();
            $('#seccion-detalles-producto').hide();
        });

        $('#btn-enviar-cocina').on('click', function() {
            let html = '';
            productosTemporal.forEach(i => {
                html += `<input type="hidden" name="productos[]" value="${i.id}">
                         <input type="hidden" name="cantidades[]" value="${i.cantidad}">
                         <input type="hidden" name="comentarios[]" value="${i.comentario}">
                         <input type="hidden" name="precios[]" value="${i.precio}">`;
            });
            $('#inputs-ocultos').html(html);
            $('#form-comanda').submit();
        });

        $('#btn-ir-a-cobro').on('click', function() {
            $('#modalTicket').modal('hide');
            $('#cobro_venta_id').val(datosMesaActual.id);
            $('#cobro_total_input').val(datosMesaActual.total);
            $('#cobro_total_display').text(parseFloat(datosMesaActual.total).toLocaleString('es-MX', {minimumFractionDigits: 2}));
            $('#pago_con').val(datosMesaActual.total);
            $('#div_cambio').hide();
            $('#metodo_pago').val('Efectivo').trigger('change');
            setTimeout(() => { $('#modalCobro').modal('show'); }, 400);
        });

        $('#pago_con').on('input', function() {
            let total = parseFloat($('#cobro_total_input').val());
            let pago = parseFloat($(this).val()) || 0;
            let cambio = pago - total;
            if(cambio >= 0) {
                $('#cambio_display').text(cambio.toLocaleString('es-MX', {minimumFractionDigits: 2}));
                $('#div_cambio').fadeIn();
            } else { $('#div_cambio').hide(); }
        });

        $('#metodo_pago').on('change', function() {
            let metodo = $(this).val();

            if (metodo === 'Tarjeta') {
                $('#pago_con').val($('#cobro_total_input').val()).attr('readonly', true);
                $('#div_cambio').hide();
                $('#div_referencia').fadeIn();
                $('#lbl_referencia').text('Código de Autorización / N° Voucher');
                $('#referencia_pago').attr('placeholder', 'Ej: 938201');
            } else if (metodo === 'Transferencia') {
                $('#pago_con').val($('#cobro_total_input').val()).attr('readonly', true);
                $('#div_cambio').hide();
                $('#div_referencia').fadeIn();
                $('#lbl_referencia').text('Folio / Clave de Rastreo (SPEI)');
                $('#referencia_pago').attr('placeholder', 'Ej: SPEI-884920');
            } else {
                $('#pago_con').attr('readonly', false);
                $('#div_referencia').fadeOut();
                $('#referencia_pago').val('');
            }
        });
    });

    function actualizarTicket() {
        let cont = $('#ticket-items'); cont.empty();
        if(productosTemporal.length > 0) {
            productosTemporal.forEach(i => {
                cont.append(`
                    <div class="border-bottom py-2">
                        <div class="d-flex justify-content-between">
                            <b>${i.cantidad}x ${i.nombre}</b>
                            <span class="text-success">$${(i.precio * i.cantidad).toFixed(2)}</span>
                        </div>
                        <small class="text-muted d-block">${i.comentario}</small>
                    </div>`);
            });
            $('#btn-enviar-cocina').prop('disabled', false).removeClass('disabled');
        } else {
            cont.html('<div class="text-center text-muted mt-5 py-5">Orden vacía</div>');
            $('#btn-enviar-cocina').prop('disabled', true).addClass('disabled');
        }
    }

    function abrirVistaTicket(id, mesa, total) {
        datosMesaActual = { id, mesa, total };

        $.get('/configuracion/fiscal-api', function(negocio) {
            if(negocio) {
                $('#tk-nombre-negocio').text(negocio.nombre_comercial || 'KAZOKU SUSHI');
                $('#tk-razon-social').text(negocio.razon_social || '');
                $('#tk-nit').text(negocio.nit_rut ? 'RFC: ' + negocio.nit_rut : '');
                $('#tk-direccion').text(negocio.direccion || '');
                $('#tk-telefono').text(negocio.telefono ? 'Tel: ' + negocio.telefono : '');
                $('#tk-mensaje').text(negocio.mensaje_ticket || '¡GRACIAS POR SU VISITA!');
            }
        });

        $('#tk-mesa').text(mesa);
        $('#tk-total').text('$' + parseFloat(total).toLocaleString('es-MX', {minimumFractionDigits: 2}));

        let itemsHtml = '';
        $(`.btn-agregar[data-id="${id}"]`).closest('.card-mesa').find('.item-comanda').each(function() {
            let nom = $(this).data('nombre');
            let cant = $(this).data('cantidad');
            let prec = parseFloat($(this).data('precio')) * cant;
            let com = $(this).data('comentario');
            itemsHtml += `<div class="mb-1 border-bottom pb-1">
                <div class="d-flex justify-content-between"><span><b>${cant}</b> ${nom}</span><span>$${prec.toFixed(2)}</span></div>
                ${com ? `<div style="font-size: 10px; color: #555;"><i>${com}</i></div>` : ''}
            </div>`;
        });

        $('#tk-items-lista').html(itemsHtml || '<div class="text-center small py-2">Sin productos</div>');
        $('#modalTicket').modal('show');
    }

    function confirmarCancelarComanda(id, mesa) {
        Swal.fire({
            title: `¿Anular la ${mesa}?`,
            text: "Esta acción cancelará la comanda completa.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, anular comanda',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                let f = document.getElementById('form-cancelar-comanda');
                f.action = '/preventa/' + id;
                f.submit();
            }
        });
    }

    // NUEVA FUNCIÓN: Cancelar un solo ítem de la comanda
    function confirmarCancelarDetalle(detalleId, productoNombre) {
        Swal.fire({
            title: `¿Quitar "${productoNombre}"?`,
            text: "El producto se eliminará de la comanda y el total se actualizará.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, quitar producto',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                let f = document.getElementById('form-cancelar-detalle');
                f.action = '/preventa/detalle/' + detalleId;
                f.submit();
            }
        });
    }
</script>
@endpush