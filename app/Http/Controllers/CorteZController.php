<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CorteZController extends Controller
{
    public function index()
    {
        // 1. Verificamos si hay mesas pendientes
        $mesasAbiertas = DB::table('ventas')->where('estado', 'pendiente')->count();

        // 2. Calculamos los totales usando comillas simples para los valores de texto
        $resumen = DB::table('ventas')
            ->where('estado', 'pagado') // Laravel se encarga de las comillas aquí
            ->whereNull('corte_id')
            ->select(
                // Nota el uso de '' (comillas simples) dentro del raw para los strings
                DB::raw("SUM(CASE WHEN metodo_pago = 'Efectivo' THEN total ELSE 0 END) as efectivo"),
                DB::raw("SUM(CASE WHEN metodo_pago = 'Tarjeta' THEN total ELSE 0 END) as tarjeta"),
                DB::raw("SUM(CASE WHEN metodo_pago = 'Transferencia' THEN total ELSE 0 END) as transferencia"),
                DB::raw("SUM(total) as gran_total"),
                DB::raw("COUNT(id) as conteo")
            )
            ->first();

        // Si no hay ventas, el query devuelve null en los campos de SUM, 
        // aseguramos que al menos sean 0 para evitar errores en la vista
        if (!$resumen->conteo) {
            $resumen->efectivo = 0;
            $resumen->tarjeta = 0;
            $resumen->transferencia = 0;
            $resumen->gran_total = 0;
            $resumen->conteo = 0;
        }

        return view('admin.caja.corte_z', compact('resumen', 'mesasAbiertas'));
    }

    public function procesarCierre(Request $request)
{
    // 1. Verificación manual rápida
    $ventasParaCerrar = DB::table('ventas')
        ->where('estado', 'pagado')
        ->whereNull('corte_id')
        ->get();

    if ($ventasParaCerrar->isEmpty()) {
        return back()->with('error', 'No hay ventas pagadas para cerrar.');
    }

    try {
        DB::beginTransaction();

        // 2. Cálculo de totales (Corregido para Postgres)
        $resumen = DB::table('ventas')
            ->where('estado', 'pagado')
            ->whereNull('corte_id')
            ->select(
                DB::raw("SUM(CASE WHEN metodo_pago = 'Efectivo' THEN total ELSE 0 END) as efec"),
                DB::raw("SUM(CASE WHEN metodo_pago = 'Tarjeta' THEN total ELSE 0 END) as tarj"),
                DB::raw("SUM(CASE WHEN metodo_pago = 'Transferencia' THEN total ELSE 0 END) as trans"),
                DB::raw("SUM(total) as total_dia"),
                DB::raw("COUNT(id) as cant")
            )->first();

        // 3. Inserción en cortes_z (Asegúrate que los nombres de columnas coincidan con tu migración)
        // He usado los nombres que pusiste en el controlador anterior
        $corteId = DB::table('cortes_z')->insertGetId([
            'fecha_cierre' => now(),
            'total_efectivo' => $resumen->efec ?? 0,
            'total_tarjeta' => $resumen->tarj ?? 0,
            'total_transferencia' => $resumen->trans ?? 0,
            'gran_total' => $resumen->total_dia ?? 0,
            'total_ventas_count' => $resumen->cant,
            'user_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 4. Actualización de las ventas (Ponerles el ID del corte)
        $actualizados = DB::table('ventas')
            ->where('estado', 'pagado')
            ->whereNull('corte_id')
            ->update(['corte_id' => $corteId]);

        // 5. Opcional: Liberar mesas si tienes esa tabla
        // DB::table('mesas')->update(['estado' => 'Libre']);

        DB::commit();

        return redirect()->route('preventa.index')->with('success', "¡Corte Z #{$corteId} realizado con éxito! Se procesaron {$actualizados} ventas.");

    } catch (\Exception $e) {
        DB::rollBack();
        
        // ESTO ES CLAVE: Si falla, nos dirá el error real de PostgreSQL
        return back()->with('error', 'Error en el proceso: ' . $e->getMessage());
    }
}
}