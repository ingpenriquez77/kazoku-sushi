<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\CorteZ;
use Illuminate\Http\Request;

class CorteZController extends Controller
{
    public function index()
    {
        // 1. Verificamos si hay ventas pendientes
        $mesasAbiertas = Venta::where('estado', 'pendiente')->count();

        // 2. Traemos las ventas pagadas que no se han asignado a un Corte Z
        $ventasSinCorte = Venta::where('estado', 'pagado')
            ->whereNull('corte_id')
            ->get();

        // 3. Calculamos los totales usando colecciones de Laravel
        $resumen = (object)[
            'efectivo'      => $ventasSinCorte->where('metodo_pago', 'Efectivo')->sum('total'),
            'tarjeta'       => $ventasSinCorte->where('metodo_pago', 'Tarjeta')->sum('total'),
            'transferencia' => $ventasSinCorte->where('metodo_pago', 'Transferencia')->sum('total'),
            'gran_total'    => $ventasSinCorte->sum('total'),
            'conteo'        => $ventasSinCorte->count(),
        ];

        return view('admin.caja.corte_z', compact('resumen', 'mesasAbiertas'));
    }

    public function procesarCierre(Request $request)
    {
        // 1. Consulta de ventas pendientes de corte
        $ventasSinCorte = Venta::where('estado', 'pagado')
            ->whereNull('corte_id')
            ->get();

        if ($ventasSinCorte->isEmpty()) {
            return back()->with('error', 'No hay ventas pagadas para cerrar.');
        }

        try {
            // 2. Totales calculados con colecciones de Laravel
            $efectivo      = $ventasSinCorte->where('metodo_pago', 'Efectivo')->sum('total');
            $tarjeta       = $ventasSinCorte->where('metodo_pago', 'Tarjeta')->sum('total');
            $transferencia = $ventasSinCorte->where('metodo_pago', 'Transferencia')->sum('total');
            $granTotal     = $ventasSinCorte->sum('total');
            $conteo        = $ventasSinCorte->count();

            // 3. Creación del documento Corte Z en Mongo Atlas
            $corte = CorteZ::create([
                'fecha_cierre'       => now(),
                'total_efectivo'     => $efectivo,
                'total_tarjeta'      => $tarjeta,
                'total_transferencia' => $transferencia,
                'gran_total'         => $granTotal,
                'total_ventas_count' => $conteo,
                'user_id'            => auth()->id(),
            ]);

            // 4. Asignar el ID del nuevo Corte Z a las ventas correspondientes
            $ids = $ventasSinCorte->pluck('_id');
            Venta::whereIn('_id', $ids)->update(['corte_id' => $corte->_id]);

            return redirect()->route('preventa.index')
                ->with('success', "¡Corte Z realizado con éxito! Se procesaron {$conteo} ventas.");

        } catch (\Exception $e) {
            return back()->with('error', 'Error en el proceso: ' . $e->getMessage());
        }
    }
}
