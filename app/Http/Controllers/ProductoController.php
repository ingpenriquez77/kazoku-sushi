<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Receta;
use App\Models\Insumo;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        // Eloquent gestiona la paginación nativamente en MongoDB
        $productos = Producto::orderBy('nombre', 'asc')->paginate(10);

        return view('productos.index', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:150',
            'precio' => 'required|numeric|min:0',
        ]);

        Producto::create([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio'      => $request->precio,
            'stock_actual'=> 0,
        ]);

        return redirect()->back()->with('success', 'Producto registrado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|max:150',
            'precio' => 'required|numeric|min:0',
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio'      => $request->precio,
        ]);

        return redirect()->back()->with('success', 'Producto actualizado.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->back()->with('success', 'Producto eliminado.');
    }

    public function gestionarReceta($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return redirect()->route('productos.index')->with('error', 'Producto no encontrado');
        }

        $receta = Receta::with('insumo')->where('producto_id', $id)->get();
        $insumos_disponibles = Insumo::orderBy('nombre', 'asc')->get();

        return view('recetas.index', compact('producto', 'receta', 'insumos_disponibles'));
    }

    public function updateReceta(Request $request, $id)
    {
        Receta::where('producto_id', $id)->delete();

        if ($request->insumos) {
            foreach ($request->insumos as $key => $insumo_id) {
                Receta::create([
                    'producto_id' => $id,
                    'insumo_id'   => $insumo_id,
                    'cantidad'    => $request->cantidades[$key],
                ]);
            }
        }
        return redirect()->route('productos.index')->with('success', 'Receta actualizada.');
    }
}
