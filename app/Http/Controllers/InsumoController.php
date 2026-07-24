<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class InsumoController extends Controller
{
    public function index(Request $request)
    {
        $porPagina = 10;
        $paginaActual = $request->input('page', 1);
        $offset = ($paginaActual - 1) * $porPagina;

        // MySQL usa COUNT(*) de forma eficiente
        $totalRegistros = DB::selectOne("SELECT COUNT(*) as total FROM insumos")->total;

        // Sintaxis LIMIT/OFFSET estándar de MySQL
        $datos = DB::select("
            SELECT * FROM insumos 
            ORDER BY nombre ASC 
            LIMIT ? OFFSET ?
        ", [$porPagina, $offset]);

        $insumos = new LengthAwarePaginator(
            $datos,
            $totalRegistros,
            $porPagina,
            $paginaActual,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('inventario.insumos', compact('insumos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'unidad_medida' => 'required',
            'stock_minimo' => 'required|numeric',
            'precio_costo_unitario' => 'required|numeric'
        ]);

        // NOW() es perfecto para los timestamps de MySQL
        DB::insert("INSERT INTO insumos (nombre, unidad_medida, stock_actual, stock_minimo, precio_costo_unitario, created_at, updated_at) 
                    VALUES (?, ?, 0, ?, ?, NOW(), NOW())", [
            $request->nombre,
            $request->unidad_medida,
            $request->stock_minimo,
            $request->precio_costo_unitario
        ]);

        return redirect()->back()->with('success', 'Insumo registrado correctamente.');
    }

    public function addStock(Request $request)
    {
        $request->validate([
            'insumo_id' => 'required',
            'cantidad' => 'required|numeric|min:0.01',
            'unidad_ingreso' => 'required', 
            'costo_total' => 'required|numeric|min:0'
        ]);

        $cantidadFinal = $request->cantidad;

        if ($request->unidad_ingreso == 'kg') {
            $cantidadFinal = $request->cantidad * 1000;
        }

        // Usamos una transacción para asegurar que tanto el stock como el historial se guarden juntos
        DB::transaction(function () use ($request, $cantidadFinal) {
            
            // En MySQL, el cálculo del CASE WHEN debe ser preciso con los paréntesis
            DB::update("UPDATE insumos 
                        SET precio_costo_unitario = (
                            CASE 
                                WHEN (stock_actual + ?) > 0 
                                THEN ((stock_actual * precio_costo_unitario) + ?) / (stock_actual + ?) 
                                ELSE precio_costo_unitario 
                            END
                        ),
                        stock_actual = stock_actual + ?, 
                        updated_at = NOW() 
                        WHERE id = ?", [
                $cantidadFinal, 
                $request->costo_total, 
                $cantidadFinal, 
                $cantidadFinal,
                $request->insumo_id
            ]);

            // MySQL: Usamos CURDATE() o NOW() para la fecha de compra
            DB::insert("INSERT INTO compras_insumos (insumo_id, cantidad, costo_total, fecha_compra, created_at, updated_at) 
                        VALUES (?, ?, ?, CURDATE(), NOW(), NOW())", [
                $request->insumo_id, 
                $cantidadFinal, 
                $request->costo_total
            ]);
        });

        return redirect()->back()->with('success', 'Stock actualizado correctamente.');
    }
    
    public function destroy($id)
    {
        // MySQL DELETE estándar
        DB::delete("DELETE FROM insumos WHERE id = ?", [$id]);
        return redirect()->back()->with('success', 'Insumo eliminado correctamente.');
    }
}