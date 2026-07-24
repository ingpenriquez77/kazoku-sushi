<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecetaController extends Controller
{
    public function index($producto_id)
    {
        $producto = DB::selectOne("SELECT * FROM productos WHERE id = ?", [$producto_id]);
        $insumos = DB::select("SELECT * FROM insumos ORDER BY nombre ASC");

        $receta = DB::select("
            SELECT r.*, i.nombre as insumo_nombre, i.unidad_medida, i.precio_costo_unitario as precio_insumo
            FROM recetas r
            JOIN insumos i ON r.insumo_id = i.id
            WHERE r.producto_id = ?", [$producto_id]);

        return view('recetas.index', compact('producto', 'insumos', 'receta'));
    }

    public function store(Request $request)
    {
        DB::insert("INSERT INTO recetas (producto_id, insumo_id, cantidad_usada, created_at, updated_at)
                    VALUES (?, ?, ?, NOW(), NOW())", [
            $request->producto_id,
            $request->insumo_id,
            $request->cantidad_usada
        ]);

        return redirect()->back()->with('success', 'Ingrediente agregado a la receta.');
    }

    public function destroy($id)
    {
        DB::delete("DELETE FROM recetas WHERE id = ?", [$id]);
        return redirect()->back()->with('success', 'Ingrediente quitado.');
    }
}
