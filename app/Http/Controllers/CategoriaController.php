<?php

namespace App\Http\Controllers;

use App\Models\Categoria; // Importamos el modelo de MongoDB
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        // Obtenemos todas las categorías ordenadas
        $categorias = Categoria::latest()->get();

        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'descripcion' => 'nullable|string'
        ]);

        // Guardado automático mediante Eloquent (Mongo asigna _id y timestamps)
        Categoria::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría guardada con éxito');
    }

    public function destroy($id)
    {
        // Eliminación física usando Eloquent
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada.');
    }
}
