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
     * Corte X: Vista individual por Cajero / Turno
     */
    public function corteX()
    {
        try {
            $user = Auth::user();
            $userId = Auth::id();
            // Usamos username si name no existe para evitar el Error 500
            $userNombre = $user->name ?? $user->username ?? 'Cajero';

            // 1. Buscar si el cajero tiene un turno abierto
            $turnoActivo = CajaTurno::where('user_id', $userId)
                ->where('estado', 'abierta')
                ->first();

            if (!$turnoActivo) {
                return view('admin.caja.apertura', compact('userNombre'));
            }

            // 2. Ventas realizadas por este cajero durante el turno actual
            $ventasTurno = Venta::where('user_id', $userId)
                ->where('estado', 'pagado')
                ->where('created_at', '>=', $turnoActivo->created_at)
                ->get();

            $totalEfectivo = $ventasTurno->where('metodo_pago', 'Efectivo')->sum('total');
            $totalTarjeta = $ventasTurno->where('metodo_pago', 'Tarjeta')->sum('total');
            $totalTransferencia = $ventasTurno->where('metodo_pago', 'Transferencia')->sum('total');
            $totalVentas = $ventasTurno->sum('total');

            $efectivoEsperado = ($turnoActivo->monto_apertura ?? 0) + $totalEfectivo;

            return view('admin.caja.corte_x', compact(
                'turnoActivo',
                'totalEfectivo',
                'totalTarjeta',
                'totalTransferencia',
                'totalVentas',
                'efectivoEsperado',
                'ventasTurno'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar Corte X: ' . $e->getMessage());
        }
    }

    /**
     * Corte Z: Vista global del Administrador
     */
    public function corteZ()
    {
        try {
            $inicioHoy = Carbon::today();

            // 1. Turnos registrados el día de hoy
            $turnosDelDia = CajaTurno::where('created_at', '>=', $inicioHoy)->get();

            // 2. Validar si hay cajeros con turno abierto
            $turnosAbiertos = $turnosDelDia->where('estado', 'abierta');
            $puedoCerrarZ = ($turnosAbiertos->count() === 0);

            // 3. Ventas totales pagadas el día de hoy
            $ventasHoy = Venta::where('estado', 'pagado')
                ->where('created_at', '>=', $inicioHoy)
                ->get();

            $totalEfectivo = $ventasHoy->where('metodo_pago', 'Efectivo')->sum('total');
            $totalTarjeta = $ventasHoy->where('metodo_pago', 'Tarjeta')->sum('total');
            $totalTransferencia = $ventasHoy->where('metodo_pago', 'Transferencia')->sum('total');
            $totalGeneral = $ventasHoy->sum('total');

            return view('admin.caja.corte_z', compact(
                'turnosDelDia',
                'turnosAbiertos',
                'puedoCerrarZ',
                'totalEfectivo',
                'totalTarjeta',
                'totalTransferencia',
                'totalGeneral',
                'ventasHoy'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar Corte Z: ' . $e->getMessage());
        }
    }

    /**
     * Abrir Turno de Cajero
     */
    public function abrirTurno(Request $request)
    {
        $request->validate(['monto_apertura' => 'required|numeric|min:0']);

        $user = Auth::user();

        CajaTurno::create([
            'user_id' => Auth::id(),
            'cajero_nombre' => $user->name ?? $user->username ?? 'Cajero',
            'monto_apertura' => floatval($request->monto_apertura),
            'fecha_apertura' => Carbon::now(),
            'estado' => 'abierta'
        ]);

        return redirect()->route('caja.corte_x')->with('success', 'Turno de caja iniciado correctamente.');
    }

    /**
     * Ejecutar Corte X (Cierre de Turno)
     */
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

    /**
     * Ejecutar Corte Z (Cierre Global Diario)
     */
    public function cerrarDiaZ(Request $request)
    {
        return redirect()->route('caja.corte_z')->with('success', 'Cierre General (Corte Z) ejecutado con éxito.');
    }
}