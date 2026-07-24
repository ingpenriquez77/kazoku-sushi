<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Importante para manejar archivos

class UserController extends Controller
{
    public function index()
    {
        // Añadimos avatar al SELECT
        $usuarios = DB::select("SELECT id, name, username, email, role, avatar, created_at FROM users ORDER BY id ASC");
        
        return view('usuarios.index', compact('usuarios'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:Administrador,Mesero,Cajero,Cocina',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // 1. Definimos la ruta por defecto (apuntando a tu carpeta public/img)
        $avatarPath = 'img/kazoku.png'; 

        // 2. Si el usuario SUBIÓ una foto, reemplazamos la ruta por defecto
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            // Nota: store() devuelve "avatars/nombre.jpg", hay que anteponer 'storage/' en la vista
        }

        DB::insert("INSERT INTO users (name, username, email, password, role, avatar, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())", [
            $request->name,
            $request->username,
            $request->email,
            Hash::make($request->password),
            $request->role,
            $avatarPath 
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario registrado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:Administrador,Mesero,Cajero,Cocina'
        ]);

        DB::update("UPDATE users SET role = ?, updated_at = NOW() WHERE id = ?", [
            $request->role,
            $id
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Registro actualizado con SQL directo.');
    }

    public function destroy($id)
    {
        // Lógica de seguridad
        if (auth()->id() == $id) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        // Antes de borrar, obtenemos la ruta del avatar para eliminar el archivo físico
        $user = DB::selectOne("SELECT avatar FROM users WHERE id = ?", [$id]);
        
        if ($user && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Sentencia SQL DELETE manual
        DB::delete("DELETE FROM users WHERE id = ?", [$id]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario y foto borrados de la base de datos.');
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8',
        ]);

        // Corregido: 'password' en lugar de 'passworde'
        DB::update("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?", [
            Hash::make($request->password),
            $id
        ]);

        return redirect()->back()->with('success', 'Contraseña actualizada con éxito.');
    }
}