<?php

namespace App\Http\Controllers;

use App\Models\Categoria; // Importamos el modelo Categoria de MongoDB
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        // Obtenemos las categorías ordenadas e incluyendo el conteo automático de productos
        $categorias = Categoria::withCount('productos')
            ->latest()
            ->get();

        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|max:255',
            'descripcion' => 'nullable|string'
        ]);

        // Guardado automático mediante Eloquent
        Categoria::create([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría guardada con éxito');
    }

    public function destroy($id)
    {
        // Eliminación usando Eloquent
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada con éxito.');
    }
}