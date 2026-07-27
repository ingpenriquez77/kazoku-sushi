<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DatosNegocioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\PreVentaController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/health', function () {
    try {
        $db = DB::connection('mongodb')->getMongoDB();
        $buildInfo = $db->command(['buildInfo' => 1])->toArray()[0];
        $serverStatus = $db->command(['serverStatus' => 1])->toArray()[0];

        // Convertimos el tiempo UTC de Mongo al tiempo local
        $mongoTime = isset($serverStatus['localTime'])
            ? \Carbon\Carbon::parse($serverStatus['localTime']->toDateTime())->timezone(config('app.timezone'))->format('Y-m-d H:i:s T')
            : now()->format('Y-m-d H:i:s T');

        return response()->json([
            'servidor' => [
                'nombre_aplicacion' => config('app.name'),
                'version_php'        => PHP_VERSION,
                'version_laravel'    => app()->version(),
                'hora_servidor'      => now()->format('Y-m-d H:i:s T'),
            ],
            'base_de_datos' => [
                'motor'         => 'MongoDB',
                'nombre_bd'     => DB::connection('mongodb')->getDatabaseName(),
                'version_bd'    => $buildInfo['version'] ?? 'Desconocida',
                'hora_fecha_bd' => $mongoTime,
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => 'ERROR',
            'mensaje' => 'Error al conectar con la base de datos',
            'error'   => $e->getMessage(),
        ], 500);
    }
})->name('health.check');

/*
|--------------------------------------------------------------------------
| Rutas Autenticación
|--------------------------------------------------------------------------
*/
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Requieren autenticación)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // --- Rutas para Configuración ---
    Route::prefix('configuracion')->group(function () {
        Route::get('/fiscal', [DatosNegocioController::class, 'index'])->name('datos_negocio.index');
        Route::post('/fiscal', [DatosNegocioController::class, 'store'])->name('datos_negocio.store');
        Route::get('/fiscal-api', [DatosNegocioController::class, 'getFiscalApi']);
    });

    // --- Rutas para Usuarios ---
    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('usuarios.index');
        Route::post('/', [UserController::class, 'store'])->name('usuarios.store');
        Route::put('/{id}', [UserController::class, 'update'])->name('usuarios.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');
        Route::put('/{id}/password', [UserController::class, 'updatePassword'])->name('usuarios.password.update');
    });

    // --- Rutas para Categorías ---
    Route::prefix('categorias')->group(function () {
        Route::get('/', [CategoriaController::class, 'index'])->name('categorias.index');
        Route::post('/store', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::delete('/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
    });

    // --- Rutas para Productos ---
    Route::prefix('productos')->group(function () {
        Route::get('/', [ProductoController::class, 'index'])->name('productos.index');
        Route::post('/store', [ProductoController::class, 'store'])->name('productos.store');
        Route::put('/{id}', [ProductoController::class, 'update'])->name('productos.update');
        Route::delete('/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');
        Route::get('/{id}/insumos', [ProductoController::class, 'getInsumos']);
    });

    // --- Rutas para Inventario ---
    Route::prefix('inventario')->group(function () {
        Route::get('/insumos', [InsumoController::class, 'index'])->name('insumos.index');
        Route::post('/insumos', [InsumoController::class, 'store'])->name('insumos.store');
        Route::post('/insumos/add-stock', [InsumoController::class, 'addStock'])->name('insumos.addStock');
        Route::delete('/insumos/{id}', [InsumoController::class, 'destroy'])->name('insumos.destroy');
    });

    // --- Rutas para Recetas ---
    Route::prefix('recetas')->group(function () {
        Route::get('/{producto_id}', [RecetaController::class, 'index'])->name('recetas.index');
        Route::post('/store', [RecetaController::class, 'store'])->name('recetas.store');
        Route::delete('/{id}', [RecetaController::class, 'destroy'])->name('recetas.destroy');
    });

    // --- Rutas para PreVenta (Comandas) ---
    Route::prefix('preventa')->group(function () {
        Route::get('/', [PreVentaController::class, 'index'])->name('preventa.index');
        Route::get('/insumos/{id}', [PreVentaController::class, 'getInsumos']);
        Route::post('/abrir', [PreVentaController::class, 'store'])->name('preventa.store');
        Route::post('/agregar', [PreVentaController::class, 'agregarProducto'])->name('preventa.agregar');
        Route::delete('/{id}', [PreVentaController::class, 'destroy'])->name('preventa.destroy');
        Route::post('/finalizar', [PreVentaController::class, 'finalizarCobro'])->name('preventa.finalizar');
    });

    // --- Rutas Unificadas para Gestión de Caja (Apertura, Corte X y Corte Z) ---
    Route::prefix('caja')->group(function () {
        Route::get('/corte-x', [CajaController::class, 'corteX'])->name('caja.corte_x');
        Route::get('/corte-z', [CajaController::class, 'corteZ'])->name('caja.corte_z');
        Route::post('/abrir', [CajaController::class, 'abrirTurno'])->name('caja.abrir');
        Route::post('/cerrar-turno', [CajaController::class, 'cerrarTurno'])->name('caja.cerrar');
        Route::post('/cerrar-z', [CajaController::class, 'cerrarDiaZ'])->name('caja.cerrar_z');
    });

});