<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PreVentaController extends Controller
{
    public function index()
    {
        $ventas_pendientes = DB::select("
            SELECT v.*, u.name as mesero,
            DATE_FORMAT(v.created_at, '%H:%i') as hora
            FROM ventas v
            JOIN users u ON v.user_id = u.id
            WHERE v.estado = 'pendiente'
            ORDER BY v.created_at DESC
        ");

        $detalles_totales = DB::select("
            SELECT dv.venta_id, dv.cantidad, p.nombre, dv.comentario, dv.precio_unitario as precio
            FROM detalle_ventas dv
            JOIN productos p ON dv.producto_id = p.id
            WHERE dv.venta_id IN (SELECT id FROM ventas WHERE estado = 'pendiente')
            AND dv.estado_item != 'cancelado'
        ");

        $productos = DB::select("SELECT id, nombre, precio, categoria_id FROM productos ORDER BY nombre ASC");

        return view('preventa.index', compact('ventas_pendientes', 'productos', 'detalles_totales'));
    }

    public function getInsumos($id)
    {
        try {
            $insumos = DB::table('recetas')
                ->join('insumos', 'recetas.insumo_id', '=', 'insumos.id')
                ->where('recetas.producto_id', $id)
                ->select('insumos.id', 'insumos.nombre')
                ->get();

            $extras = DB::table('productos')
                ->where('categoria_id', 9)
                ->select('id', 'nombre', 'precio')
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

        DB::table('ventas')->insert([
            'codigo_pedidido' => $codigo,
            'mesa' => $request->mesa,
            'estado' => 'pendiente',
            'total' => 0,
            'user_id' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now()
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
            DB::transaction(function() use ($venta_id, $productos_ids, $cantidades, $comentarios, $precios_con_extras) {
                $venta = DB::table('ventas')->where('id', $venta_id)->first();
                if (!$venta) throw new \Exception("Venta no encontrada.");

                foreach ($productos_ids as $i => $producto_id) {
                    $precio_final = $precios_con_extras[$i];
                    $subtotal = $precio_final * $cantidades[$i];

                    DB::table('detalle_ventas')->insert([
                        'venta_id' => $venta_id,
                        'codigo_pedidido' => $venta->codigo_pedidido,
                        'producto_id' => $producto_id,
                        'cantidad' => $cantidades[$i],
                        'precio_unitario' => $precio_final,
                        'subtotal' => $subtotal,
                        'comentario' => $comentarios[$i] ?? null,
                        'estado_item' => 'pendiente',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                $nuevo_total = DB::table('detalle_ventas')
                                ->where('venta_id', $venta_id)
                                ->where('estado_item', '!=', 'cancelado')
                                ->sum('subtotal');

                DB::table('ventas')->where('id', $venta_id)->update(['total' => $nuevo_total]);
            });

            return redirect()->back()->with('success', 'Pedido enviado a cocina.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function() use ($id) {
                DB::table('detalle_ventas')->where('venta_id', $id)->update(['estado_item' => 'cancelado']);
                DB::table('ventas')->where('id', $id)->update(['estado' => 'cancelado']);
            });
            return redirect()->back()->with('success', 'Mesa cancelada.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cancelar.');
        }
    }

    public function finalizarCobro(Request $request)
    {
        $request->validate([
            'venta_id' => 'required',
            'metodo_pago' => 'required',
            'pago_con' => 'required|numeric',
            'total_pagar' => 'required|numeric',
        ]);

        try {
            $venta_id = $request->venta_id;
            $montoRecibido = floatval($request->pago_con);
            $totalVenta = floatval($request->total_pagar);
            $cambio = $montoRecibido - $totalVenta;

            DB::transaction(function() use ($venta_id, $request, $montoRecibido, $cambio) {
                DB::table('ventas')->where('id', $venta_id)->update([
                    'estado' => 'pagado',
                    'metodo_pago' => $request->metodo_pago,
                    'monto_pagado' => $montoRecibido,
                    'cambio' => $cambio,
                    'updated_at' => now()
                ]);

                DB::table('detalle_ventas')
                    ->where('venta_id', $venta_id)
                    ->where('estado_item', '!=', 'cancelado')
                    ->update([
                        'estado_item' => 'pagado',
                        'updated_at' => now()
                    ]);
            });

            return redirect()->route('preventa.index')->with('success', 'Venta finalizada y mesa liberada.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error crítico: ' . $e->getMessage());
        }
    }
}
