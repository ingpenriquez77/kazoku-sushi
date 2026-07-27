<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\CajaTurno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CajaController extends Controller
{
    /**
     * Vista de Corte X (Turno del Cajero)
     */
    public function corteX()
    {
        $userId = Auth::id();
        $userNombre = Auth::user()->name;

        // Buscar si el cajero actual tiene un turno abierto
        $turnoActivo = CajaTurno::where('user_id', $userId)
            ->where('estado', 'abierta')
            ->first();

        if (!$turnoActivo) {
            return view('caja.apertura', compact('userNombre'));
        }

        // Ventas del cajero en tiempo real durante su turno actual
        $ventasTurno = Venta::where('user_id', $userId)
            ->where('estado', 'pagado')
            ->where('created_at', '>=', $turnoActivo->created_at)
            ->get();

        $totalEfectivo = $ventasTurno->where('metodo_pago', 'Efectivo')->sum('total');
        $totalTarjeta = $ventasTurno->where('metodo_pago', 'Tarjeta')->sum('total');
        $totalTransferencia = $ventasTurno->where('metodo_pago', 'Transferencia')->sum('total');
        $totalVentas = $ventasTurno->sum('total');

        $efectivoEsperado = $turnoActivo->monto_apertura + $totalEfectivo;

        return view('caja.corte_x', compact(
            'turnoActivo',
            'totalEfectivo',
            'totalTarjeta',
            'totalTransferencia',
            'totalVentas',
            'efectivoEsperado',
            'ventasTurno'
        ));
    }

    /**
     * Vista de Corte Z (Administración / Cierre Global del Día)
     */
    public function corteZ()
    {
        $inicioHoy = Carbon::today();

        // 1. Obtener todos los turnos del día
        $turnosDelDia = CajaTurno::where('created_at', '>=', $inicioHoy)->get();

        // 2. Verificar si existen turnos que aún estén 'abiertos'
        $turnosAbiertos = $turnosDelDia->where('estado', 'abierta');
        $puedoCerrarZ = ($turnosAbiertos->count() === 0); // Solo se activa si es 0

        // 3. Totales acumulados de todos los turnos/ventas pagadas hoy
        $ventasHoy = Venta::where('estado', 'pagado')
            ->where('created_at', '>=', $inicioHoy)
            ->get();

        $totalEfectivo = $ventasHoy->where('metodo_pago', 'Efectivo')->sum('total');
        $totalTarjeta = $ventasHoy->where('metodo_pago', 'Tarjeta')->sum('total');
        $totalTransferencia = $ventasHoy->where('metodo_pago', 'Transferencia')->sum('total');
        $totalGeneral = $ventasHoy->sum('total');

        return view('caja.corte_z', compact(
            'turnosDelDia',
            'turnosAbiertos',
            'puedoCerrarZ',
            'totalEfectivo',
            'totalTarjeta',
            'totalTransferencia',
            'totalGeneral',
            'ventasHoy'
        ));
    }

    public function abrirTurno(Request $request)
    {
        $request->validate(['monto_apertura' => 'required|numeric|min:0']);

        CajaTurno::create([
            'user_id' => Auth::id(),
            'cajero_nombre' => Auth::user()->name,
            'monto_apertura' => floatval($request->monto_apertura),
            'fecha_apertura' => Carbon::now(),
            'estado' => 'abierta'
        ]);

        return redirect()->route('caja.corte_x')->with('success', 'Turno de caja iniciado.');
    }

    public function cerrarTurno(Request $request)
    {
        $request->validate([
            'turno_id' => 'required',
            'monto_efectivo_real' => 'required|numeric|min:0'
        ]);

        $turno = CajaTurno::findOrFail($request->turno_id);

        $ventasTurno = Venta::where('user_id', $turno->user_id)
            ->where('estado', 'pagado')
            ->where('created_at', '>=', $turno->created_at)
            ->get();

        $efectivoVentas = $ventasTurno->where('metodo_pago', 'Efectivo')->sum('total');
        $tarjetaVentas = $ventasTurno->where('metodo_pago', 'Tarjeta')->sum('total');
        $transferenciaVentas = $ventasTurno->where('metodo_pago', 'Transferencia')->sum('total');

        $efectivoEsperado = $turno->monto_apertura + $efectivoVentas;
        $efectivoReal = floatval($request->monto_efectivo_real);
        $diferencia = $efectivoReal - $efectivoEsperado;

        $turno->update([
            'monto_efectivo_ventas' => $efectivoVentas,
            'monto_tarjeta_ventas' => $tarjetaVentas,
            'monto_transferencia_ventas' => $transferenciaVentas,
            'monto_total_esperado' => $efectivoEsperado,
            'monto_efectivo_real' => $efectivoReal,
            'diferencia' => $diferencia,
            'fecha_cierre' => Carbon::now(),
            'estado' => 'cerrada',
            'observaciones' => $request->observaciones ?? null
        ]);

        return redirect()->route('caja.corte_x')->with('success', 'Corte X completado exitosamente.');
    }
}