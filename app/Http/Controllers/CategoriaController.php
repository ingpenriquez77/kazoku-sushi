<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = DB::select("SELECT * FROM categorias ORDER BY id ASC");
        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'descripcion' => 'nullable'
        ]);

        DB::insert("INSERT INTO categorias (nombre, descripcion, created_at, updated_at) VALUES (?, ?, NOW(), NOW())", [
            $request->nombre,
            $request->descripcion
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría guardada con éxito.');
    }

    public function destroy($id)
    {
        DB::delete("DELETE FROM categorias WHERE id = ?", [$id]);
        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada.');
    }
}
