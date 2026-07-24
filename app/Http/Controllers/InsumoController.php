<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\CompraInsumo;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    public function index()
    {
        // Eloquent gestiona la paginación y conteo automáticamente para MongoDB
        $insumos = Insumo::orderBy('nombre', 'asc')->paginate(10);

        return view('inventario.insumos', compact('insumos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'                => 'required|max:100',
            'unidad_medida'         => 'required',
            'stock_minimo'          => 'required|numeric',
            'precio_costo_unitario' => 'required|numeric'
        ]);

        Insumo::create([
            'nombre'                => $request->nombre,
            'unidad_medida'         => $request->unidad_medida,
            'stock_actual'          => 0,
            'stock_minimo'          => $request->stock_minimo,
            'precio_costo_unitario' => $request->precio_costo_unitario,
        ]);

        return redirect()->back()->with('success', 'Insumo registrado correctamente.');
    }

    public function addStock(Request $request)
    {
        $request->validate([
            'insumo_id'      => 'required',
            'cantidad'       => 'required|numeric|min:0.01',
            'unidad_ingreso' => 'required',
            'costo_total'    => 'required|numeric|min:0'
        ]);

        $cantidadFinal = $request->cantidad;

        if ($request->unidad_ingreso === 'kg') {
            $cantidadFinal = $request->cantidad * 1000;
        }

        $insumo = Insumo::findOrFail($request->insumo_id);

        // Cálculo del promedio ponderado en PHP
        $stockActual = $insumo->stock_actual ?? 0;
        $precioActual = $insumo->precio_costo_unitario ?? 0;
        $nuevoStockTotal = $stockActual + $cantidadFinal;

        if ($nuevoStockTotal > 0) {
            $nuevoPrecioUnitario = (($stockActual * $precioActual) + $request->costo_total) / $nuevoStockTotal;
        } else {
            $nuevoPrecioUnitario = $precioActual;
        }

        // Actualizamos el insumo
        $insumo->update([
            'stock_actual'          => $nuevoStockTotal,
            'precio_costo_unitario' => $nuevoPrecioUnitario,
        ]);

        // Guardamos el historial de compra
        CompraInsumo::create([
            'insumo_id'    => $insumo->_id,
            'cantidad'     => $cantidadFinal,
            'costo_total'  => $request->costo_total,
            'fecha_compra' => now()->toDateString(),
        ]);

        return redirect()->back()->with('success', 'Stock actualizado correctamente.');
    }

    public function destroy($id)
    {
        $insumo = Insumo::findOrFail($id);
        $insumo->delete();

        return redirect()->back()->with('success', 'Insumo eliminado correctamente.');
    }
}
