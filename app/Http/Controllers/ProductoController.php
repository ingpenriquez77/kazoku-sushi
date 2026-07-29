<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Receta;
use App\Models\Insumo;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with('categoria:_id,nombre'); // Carga solo id y nombre de la categoría

        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            
            // Expresión regular nativa de MongoDB para búsquedas rápidas case-insensitive
            $regex = new \MongoDB\BSON\Regex($buscar, 'i');

            $query->where(function($q) use ($regex) {
                $q->where('nombre', 'regex', $regex)
                ->orWhere('descripcion', 'regex', $regex);
            });
        }

        $productos = $query->paginate(10)->appends($request->all());
        $categorias = Categoria::orderBy('nombre', 'asc')->get();

        return view('productos.index', compact('productos', 'categorias'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|max:150',
            'precio'       => 'required|numeric|min:0',
            'categoria_id' => 'required',
        ]);

        Producto::create([
            'nombre'       => $request->nombre,
            'descripcion'  => $request->descripcion,
            'precio'       => floatval($request->precio),
            'categoria_id' => $request->categoria_id,
            'stock_actual' => 0,
            'disponible'   => true,
        ]);

        return redirect()->back()->with('success', 'Producto registrado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'       => 'required|max:150',
            'precio'       => 'required|numeric|min:0',
            'categoria_id' => 'required',
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update([
            'nombre'       => $request->nombre,
            'descripcion'  => $request->descripcion,
            'precio'       => floatval($request->precio),
            'categoria_id' => $request->categoria_id,
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