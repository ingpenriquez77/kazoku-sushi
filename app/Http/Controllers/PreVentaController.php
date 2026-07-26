<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreVentaController extends Controller
{
    public function index()
    {
        // 1. Ventas pendientes con la relación del usuario (mesero)
        $ventas_pendientes = Venta::with('user')
            ->where('estado', 'pendiente')
            ->latest()
            ->get()
            ->map(function ($venta) {
                $venta->mesero = $venta->user->name ?? 'N/A';
                $venta->hora = $venta->created_at ? $venta->created_at->format('H:i') : '';
                return $venta;
            });

        // 2. Traer los IDs de ventas pendientes
        $ventas_ids = $ventas_pendientes->pluck('_id');

        // 3. Obtener detalles de productos pendientes
        $detalles_totales = DetalleVenta::with('producto')
            ->whereIn('venta_id', $ventas_ids)
            ->where('estado_item', '!=', 'cancelado')
            ->get()
            ->map(function ($detalle) {
                $detalle->nombre = $detalle->producto->nombre ?? 'Producto';
                $detalle->precio = $detalle->precio_unitario;
                return $detalle;
            });

        $productos = Producto::orderBy('nombre', 'asc')->get();

        return view('preventa.index', compact('ventas_pendientes', 'productos', 'detalles_totales'));
    }

    public function getInsumos($id)
    {
        try {
            $recetas = Receta::with('insumo')->where('producto_id', $id)->get();

            $insumos = $recetas->map(function ($r) {
                return [
                    'id'     => $r->insumo->_id ?? $r->insumo_id,
                    'nombre' => $r->insumo->nombre ?? 'Sin nombre',
                ];
            });

            $extras = Producto::where('categoria_id', 9)
                ->select('_id as id', 'nombre', 'precio')
                ->get();

            return response()->json(['insumos' => $insumos, 'extras' => $extras]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate(['mesa' => 'required|max:50']);

        $codigo = 'PED-' . strtoupper(substr(uniqid(), -8));

        Venta::create([
            'codigo_pedidido' => $codigo,
            'mesa'            => $request->mesa,
            'estado'          => 'pendiente',
            'total'           => 0,
            'user_id'         => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Mesa abierta.');
    }

    public function agregarProducto(Request $request)
    {
        $venta_id = $request->venta_id;
        $productos_ids = $request->productos;
        $cantidades = $request->cantidades;
        $comentarios = $request->comentarios ?? [];
        $precios_con_extras = $request->precios;

        if (!$productos_ids) return back()->with('error', 'No hay productos.');

        try {
            $venta = Venta::findOrFail($venta_id);

            foreach ($productos_ids as $i => $producto_id) {
                $precio_final = $precios_con_extras[$i];
                $subtotal = $precio_final * $cantidades[$i];

                DetalleVenta::create([
                    'venta_id'        => $venta->_id,
                    'codigo_pedidido' => $venta->codigo_pedidido,
                    'producto_id'     => $producto_id,
                    'cantidad'        => $cantidades[$i],
                    'precio_unitario' => $precio_final,
                    'subtotal'        => $subtotal,
                    'comentario'      => $comentarios[$i] ?? null,
                    'estado_item'     => 'pendiente',
                ]);
            }

            // Recalculamos el total de la venta
            $nuevo_total = DetalleVenta::where('venta_id', $venta->_id)
                ->where('estado_item', '!=', 'cancelado')
                ->sum('subtotal');

            $venta->update(['total' => $nuevo_total]);

            return redirect()->back()->with('success', 'Pedido enviado a cocina.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DetalleVenta::where('venta_id', $id)->update(['estado_item' => 'cancelado']);
            Venta::where('_id', $id)->update(['estado' => 'cancelado']);

            return redirect()->back()->with('success', 'Mesa cancelada.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cancelar.');
        }
    }

    public function finalizarCobro(Request $request)
    {
        $request->validate([
            'venta_id'        => 'required',
            'metodo_pago'     => 'required',
            'pago_con'        => 'required|numeric',
            'total_pagar'     => 'required|numeric',
            'referencia_pago' => 'nullable|string|max:100', // Validación opcional para vouchers/folios
        ]);

        try {
            $venta_id = $request->venta_id;
            $montoRecibido = floatval($request->pago_con);
            $totalVenta = floatval($request->total_pagar);
            $cambio = $montoRecibido - $totalVenta;

            $venta = Venta::findOrFail($venta_id);
            $venta->update([
                'estado'          => 'pagado',
                'metodo_pago'     => $request->metodo_pago,
                'referencia_pago' => $request->referencia_pago ?? null,
                'monto_pagado'    => $montoRecibido,
                'cambio'          => $cambio,
            ]);

            DetalleVenta::where('venta_id', $venta->_id)
                ->where('estado_item', '!=', 'cancelado')
                ->update(['estado_item' => 'pagado']);

            return redirect()->route('preventa.index')->with('success', 'Venta finalizada y mesa liberada.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error crítico: ' . $e->getMessage());
        }
    }
}