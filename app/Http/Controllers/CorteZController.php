<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CorteZController extends Controller
{
    public function index()
    {
        $mesasAbiertas = DB::table('ventas')->where('estado', 'pendiente')->count();

        $resumen = DB::table('ventas')
            ->where('estado', 'pagado')
            ->whereNull('corte_id')
            ->select(
                DB::raw("SUM(CASE WHEN metodo_pago = 'Efectivo' THEN total ELSE 0 END) as efectivo"),
                DB::raw("SUM(CASE WHEN metodo_pago = 'Tarjeta' THEN total ELSE 0 END) as tarjeta"),
                DB::raw("SUM(CASE WHEN metodo_pago = 'Transferencia' THEN total ELSE 0 END) as transferencia"),
                DB::raw("SUM(total) as gran_total"),
                DB::raw("COUNT(id) as conteo")
            )
            ->first();

        if (!$resumen || !$resumen->conteo) {
            $resumen = (object)[
                'efectivo' => 0,
                'tarjeta' => 0,
                'transferencia' => 0,
                'gran_total' => 0,
                'conteo' => 0,
            ];
        }

        return view('admin.caja.corte_z', compact('resumen', 'mesasAbiertas'));
    }

    public function procesarCierre(Request $request)
    {
        $ventasParaCerrar = DB::table('ventas')
            ->where('estado', 'pagado')
            ->whereNull('corte_id')
            ->get();

        if ($ventasParaCerrar->isEmpty()) {
            return back()->with('error', 'No hay ventas pagadas para cerrar.');
        }

        try {
            DB::beginTransaction();

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

            $corteId = DB::table('cortes_z')->insertGetId([
                'fecha_cierre' => now(),
                'total_efectivo' => $resumen->efec ?? 0,
                'total_tarjeta' => $resumen->tarj ?? 0,
                'total_transferencia' => $resumen->trans ?? 0,
                'gran_total' => $resumen->total_dia ?? 0,
                'total_ventas_count' => $resumen->cant ?? 0,
                'user_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $actualizados = DB::table('ventas')
                ->where('estado', 'pagado')
                ->whereNull('corte_id')
                ->update(['corte_id' => $corteId]);

            DB::commit();

            return redirect()->route('preventa.index')->with('success', "¡Corte Z #{$corteId} realizado con éxito! Se procesaron {$actualizados} ventas.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error en el proceso: ' . $e->getMessage());
        }
    }
}
