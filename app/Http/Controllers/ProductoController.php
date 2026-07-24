<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $perPage;

        $totalCount = DB::selectOne("SELECT COUNT(*) as total FROM productos")->total;

        $data = DB::select("
            SELECT * FROM productos
            ORDER BY nombre ASC
            LIMIT ? OFFSET ?
        ", [$perPage, $offset]);

        $productos = new LengthAwarePaginator(
            $data,
            $totalCount,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('productos.index', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:150',
            'precio' => 'required|numeric|min:0',
        ]);

        DB::insert("INSERT INTO productos (nombre, descripcion, precio, stock_actual, created_at, updated_at)
                    VALUES (?, ?, ?, 0, NOW(), NOW())", [
            $request->nombre,
            $request->descripcion,
            $request->precio
        ]);

        return redirect()->back()->with('success', 'Producto registrado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|max:150',
            'precio' => 'required|numeric|min:0',
        ]);

        DB::update("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, updated_at = NOW() WHERE id = ?", [
            $request->nombre,
            $request->descripcion,
            $request->precio,
            $id
        ]);

        return redirect()->back()->with('success', 'Producto actualizado.');
    }

    public function destroy($id)
    {
        DB::delete("DELETE FROM productos WHERE id = ?", [$id]);
        return redirect()->back()->with('success', 'Producto eliminado.');
    }

    public function gestionarReceta($id)
    {
        $producto = DB::selectOne("SELECT * FROM productos WHERE id = ?", [$id]);

        if (!$producto) {
            return redirect()->route('productos.index')->with('error', 'Producto no encontrado');
        }

        $receta = DB::select("
            SELECT r.*, i.nombre as insumo_nombre, i.unidad_medida
            FROM recetas r
            INNER JOIN insumos i ON r.insumo_id = i.id
            WHERE r.producto_id = ?
        ", [$id]);

        $insumos_disponibles = DB::select("SELECT * FROM insumos ORDER BY nombre ASC");

        return view('recetas.index', compact('producto', 'receta', 'insumos_disponibles'));
    }

    public function updateReceta(Request $request, $id)
    {
        DB::delete("DELETE FROM recetas WHERE producto_id = ?", [$id]);

        if ($request->insumos) {
            foreach ($request->insumos as $key => $insumo_id) {
                DB::insert("INSERT INTO recetas (producto_id, insumo_id, cantidad, created_at, updated_at)
                            VALUES (?, ?, ?, NOW(), NOW())", [
                    $id,
                    $insumo_id,
                    $request->cantidades[$key]
                ]);
            }
        }
        return redirect()->route('productos.index')->with('success', 'Receta actualizada.');
    }
}
