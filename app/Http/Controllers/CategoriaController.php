<?php

namespace App\Http\Controllers;

use App\Models\Categoria; // Importamos el modelo Categoria de MongoDB
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        // Usamos with('productos') que es 100% compatible con MongoDB Eloquent
        $categorias = Categoria::with('productos')
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

        Categoria::create([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría guardada con éxito');
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada con éxito.');
    }
}