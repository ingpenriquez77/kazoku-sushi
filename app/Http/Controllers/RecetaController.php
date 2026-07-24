<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Insumo;
use App\Models\Receta;
use Illuminate\Http\Request;

class RecetaController extends Controller
{
    public function index($producto_id)
    {
        $producto = Producto::findOrFail($producto_id);
        $insumos = Insumo::orderBy('nombre', 'asc')->get();

        $receta = Receta::with('insumo')->where('producto_id', $producto_id)->get();

        return view('recetas.index', compact('producto', 'insumos', 'receta'));
    }

    public function store(Request $request)
    {
        Receta::create([
            'producto_id'   => $request->producto_id,
            'insumo_id'     => $request->insumo_id,
            'cantidad_usada'=> $request->cantidad_usada,
        ]);

        return redirect()->back()->with('success', 'Ingrediente agregado a la receta.');
    }

    public function destroy($id)
    {
        $receta = Receta::findOrFail($id);
        $receta->delete();

        return redirect()->back()->with('success', 'Ingrediente quitado.');
    }
}
