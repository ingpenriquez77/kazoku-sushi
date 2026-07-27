<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kazoku Sushi | @yield('title')</title>

    <link rel="icon" href="{{ asset('kazoku.ico') }}" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    {{-- SELECT2 para buscadores en recetas --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">

    <style>
        body { font-family: 'Nunito', sans-serif; }

        /* --- AJUSTE DE LOGO --- */
        .brand-link {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 0.8rem 0.5rem !important;
        }

        .brand-image-custom {
            width: 38px !important;
            height: 38px !important;
            object-fit: cover;
            margin-left: 0.5rem;
            margin-right: 0.5rem;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .sidebar-collapse .brand-text { display: none !important; }
        .sidebar-collapse .brand-link {
            justify-content: center;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1) !important;
            border: none;
            margin-bottom: 1.5rem;
        }
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #e3e6f0;
            padding: 1.25rem;
        }
        .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 0.5rem 1rem;
            transition: all 0.3s;
        }
        .main-sidebar { background-color: #1e293b !important; }
        .brand-link { border-bottom: 1px solid #334155 !important; }
        .nav-link.active {
            background-color: #3b82f6 !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            border-radius: 8px;
        }
        .badge {
            padding: 0.5em 0.8em;
            border-radius: 6px;
            font-weight: 600;
        }

        /* Estilo para que Select2 combine con AdminLTE */
        .select2-container--bootstrap4 .select2-selection { border-radius: 8px !important; }
    </style>

    @livewireStyles
    @stack('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light sticky-top">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item d-none d-sm-inline-block">
                <span class="nav-link text-muted">
                    <i class="far fa-calendar-alt mr-1"></i> {{ date('d/m/Y') }}
                </span>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('dashboard.index') }}" class="brand-link">
            <img src="{{ asset('img/kazoku.png') }}" 
                 alt="Logo" 
                 class="brand-image-custom img-circle elevation-3">
            <span class="brand-text font-weight-extrabold tracking-wider text-white">
                Kazoku <span class="text-primary">Sushi</span>
            </span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
                <div class="image">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3b82f6&color=fff&bold=true" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block font-weight-bold text-white">{{ auth()->user()->name }}</a>
                    <small class="text-success"><i class="fas fa-circle text-xs mr-1"></i> {{ auth()->user()->role }}</small>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('dashboard.index') }}" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header text-muted small">MÓDULOS</li>
                    
                    <li class="nav-item">
                      <a href="{{ route('usuarios.index') }}" class="nav-link {{ request()->is('usuarios*') ? 'active' : '' }}">
                          <i class="nav-icon fas fa-users-cog"></i>
                          <p>Empleados</p>
                      </a>
                    </li>

                    <li class="nav-item">
                      <a href="{{ route('categorias.index') }}" class="nav-link {{ request()->is('categorias*') ? 'active' : '' }}">
                          <i class="nav-icon fas fa-tags"></i>
                          <p>Categorías</p>
                      </a>
                    </li>

                    <li class="nav-item">
                      <a href="{{ route('productos.index') }}" class="nav-link {{ request()->is('productos*') || request()->is('recetas*') ? 'active' : '' }}">
                          <i class="nav-icon fas fa-box-open"></i>
                          <p>Productos</p>
                      </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('insumos.index') }}" class="nav-link {{ request()->is('inventario/insumos*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>Inventario de Insumos</p>
                        </a>
                    </li>
                
                    <li class="nav-item">
                        <a href="{{ route('preventa.index') }}" class="nav-link {{ request()->is('preventa*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>Comandas (Mesas)</p>
                        </a>
                    </li>

                    <li class="nav-header text-muted small">SISTEMA Y CAJA</li>

                    {{-- BOTONES DE CAJA --}}
                    <li class="nav-item">
                        <a href="{{ route('caja.corte_x') }}" class="nav-link {{ request()->is('caja/corte-x*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-clock text-info"></i>
                            <p>Corte Turno (X)</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('caja.corte_z') }}" class="nav-link {{ request()->is('caja/corte-z*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cash-register text-warning"></i>
                            <p>Corte Diario (Z)</p>
                        </a>
                    </li>

                    {{-- MÓDULO DE REPORTES (DESPLEGABLE TREEVIEW) --}}
                    <li class="nav-item {{ request()->is('reportes*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('reportes*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt text-teal"></i>
                            <p>
                                Reportes
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ Route::has('reportes.ventas') ? route('reportes.ventas') : '#' }}" class="nav-link {{ request()->is('reportes/ventas*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon text-success"></i>
                                    <p>Ventas e Ingresos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ Route::has('reportes.inventario') ? route('reportes.inventario') : '#' }}" class="nav-link {{ request()->is('reportes/inventario*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon text-info"></i>
                                    <p>Consumo de Insumos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ Route::has('reportes.cortes') ? route('reportes.cortes') : '#' }}" class="nav-link {{ request()->is('reportes/cortes*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon text-warning"></i>
                                    <p>Historial Cortes X/Z</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- BOTÓN: DATOS FISCALES --}}
                    <li class="nav-item">
                        <a href="{{ route('datos_negocio.index') }}" class="nav-link {{ request()->is('configuracion/fiscal*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice-dollar text-primary"></i>
                            <p>Datos Fiscales</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="nav-icon fas fa-power-off text-danger"></i>
                            <p class="text">Cerrar Sesión</p>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper bg-light">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="font-weight-bold text-dark">@yield('header')</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer text-center text-sm border-0 bg-light">
        <div class="float-right d-none d-sm-block text-muted">
            <b>v1.0</b>
        </div>
        <span class="text-muted">Kazoku Sushi &copy; 2026. Hecho con <i class="fas fa-heart text-danger"></i></span>
    </footer>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    });
</script>

@livewireScripts
@stack('js')

</body>
</html>