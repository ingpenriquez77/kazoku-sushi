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

        // Cargar las recetas junto con sus insumos
        $recetas = Receta::with('insumo')
            ->where('producto_id', $producto_id)
            ->get();

        // Calcular costo total del plato basándose en los insumos
        $costoTotalPlato = $recetas->sum(function ($item) {
            $costoUnitario = $item->insumo->costo_unitario ?? $item->insumo->costo ?? 0;
            return $item->cantidad_usada * $costoUnitario;
        });

        return view('recetas.index', compact('producto', 'insumos', 'recetas', 'costoTotalPlato'));
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