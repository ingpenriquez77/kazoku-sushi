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

// ==============================================================================
// RUTA DE HEALTHCHECK (Pública)
// ==============================================================================
Route::get('/health', function () {
    try {
        $dbConnection = config('database.default');

        // Obtener versión y hora según el driver de base de datos
        if ($dbConnection === 'mysql') {
            $dbVersion = DB::selectOne("SELECT VERSION() as version")->version;
            $dbTime = DB::selectOne("SELECT NOW() as time")->time;
        } else {
            // En caso de usar MongoDB
            $dbVersion = 'MongoDB Atlas / Driver Native';
            $dbTime = now()->toDateTimeString();
        }

        $dbName = config("database.connections.{$dbConnection}.database");

        return response()->json([
            'status' => 'ok',
            'message' => 'API is running properly',
            'app_info' => [
                'name' => config('app.name'),
                'laravel_version' => app()->version(),
                'php_version' => PHP_VERSION,
                'server_time' => now()->toDateTimeString(),
                'timezone' => config('app.timezone'),
            ],
            'database' => [
                'connection' => $dbConnection,
                'name' => $dbName,
                'version' => $dbVersion,
                'time' => $dbTime,
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database connection failed: ' . $e->getMessage(),
            'app_info' => [
                'name' => config('app.name'),
                'laravel_version' => app()->version(),
                'php_version' => PHP_VERSION,
                'server_time' => now()->toDateTimeString(),
            ]
        ], 500);
    }
});

// ==============================================================================
// AUTENTICACIÓN
// ==============================================================================
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');

// Redirige a raíz '/' al cerrar sesión mediante este callback limpio
Route::post('/logout', function (Request $request) {
    app(LoginController::class)->logout(request());
    return redirect('/');
})->name('logout');


// ==============================================================================
// RUTAS PROTEGIDAS POR AUTENTICACIÓN
// ==============================================================================
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
