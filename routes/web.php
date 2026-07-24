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
use App\Http\Controllers\CorteZController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Health Check / Endpoint de Diagnóstico
|--------------------------------------------------------------------------
*/
Route::get('/health', function () {
    try {
        // En MongoDB realizamos un ping al comando nativo para validar conexión
        DB::connection('mongodb')->getPdo()
            ? null
            : DB::connection('mongodb')->command(['ping' => 1]);

        $dbStatus = 'connected';
    } catch (\Exception $e) {
        $dbStatus = 'disconnected: ' . $e->getMessage();
    }

    return response()->json([
        'status'      => 'ok',
        'timestamp'   => now()->toIso8601String(),
        'database'    => [
            'status' => $dbStatus,
            'name'   => config('database.connections.mongodb.database'),
        ]
    ], $dbStatus === 'connected' ? 200 : 500);
});

/*
|--------------------------------------------------------------------------
| Rutas Autenticación
|--------------------------------------------------------------------------
*/
// Esta es la ruta que Laravel busca por defecto cuando la sesión expira
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// Asignamos el nombre 'login.store' para procesar el formulario
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

    // --- Rutas para PreVenta ---
    Route::prefix('preventa')->group(function () {
        Route::get('/', [PreVentaController::class, 'index'])->name('preventa.index');
        Route::get('/insumos/{id}', [PreVentaController::class, 'getInsumos']);
        Route::post('/abrir', [PreVentaController::class, 'store'])->name('preventa.store');
        Route::post('/agregar', [PreVentaController::class, 'agregarProducto'])->name('preventa.agregar');
        Route::delete('/{id}', [PreVentaController::class, 'destroy'])->name('preventa.destroy');
        Route::post('/finalizar', [PreVentaController::class, 'finalizarCobro'])->name('preventa.finalizar');
    });

    // --- Rutas para Caja ---
    Route::prefix('caja')->group(function () {
        Route::get('/corte-z', [CorteZController::class, 'index'])->name('corte.index');
        Route::post('/corte-z', [CorteZController::class, 'procesarCierre'])->name('corte.procesar');
    });
});
