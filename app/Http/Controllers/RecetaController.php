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
        // Busca el producto por ID de MongoDB
        $producto = Producto::findOrFail($producto_id);

        // Carga las recetas trayendo de antemano el insumo asociado
        $receta = Receta::with('insumo')
            ->where('producto_id', (string) $producto_id)
            ->get();

        // Insumos para el select
        $insumos = Insumo::all();

        return view('recetas.index', compact('producto', 'receta', 'insumos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required',
            'insumo_id' => 'required',
            'cantidad_usada' => 'required|numeric|min:0.001',
        ]);

        Receta::create([
            'producto_id'   => (string) $request->producto_id,
            'insumo_id'     => (string) $request->insumo_id,
            'cantidad_usada'=> (float) $request->cantidad_usada,
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