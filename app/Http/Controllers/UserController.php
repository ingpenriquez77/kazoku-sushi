<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = DB::select("SELECT id, name, username, email, role, avatar, permissions, created_at FROM users ORDER BY id ASC");

        // Decodificamos el JSON de permisos para cada registro
        foreach ($usuarios as $u) {
            $u->permissions = json_decode($u->permissions ?? '[]', true);
        }

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
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'permissions' => 'nullable|array'
        ]);

        $avatarPath = 'img/kazoku.png';

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $permissionsJson = json_encode($request->permissions ?? []);

        DB::insert("INSERT INTO users (name, username, email, password, role, avatar, permissions, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())", [
            $request->name,
            $request->username,
            $request->email,
            Hash::make($request->password),
            $request->role,
            $avatarPath,
            $permissionsJson
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario registrado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:Administrador,Mesero,Cajero,Cocina',
            'permissions' => 'nullable|array'
        ]);

        $permissionsJson = json_encode($request->permissions ?? []);

        DB::update("UPDATE users SET role = ?, permissions = ?, updated_at = NOW() WHERE id = ?", [
            $request->role,
            $permissionsJson,
            $id
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Permisos y rol actualizados.');
    }

    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $user = DB::selectOne("SELECT avatar FROM users WHERE id = ?", [$id]);

        if ($user && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        DB::delete("DELETE FROM users WHERE id = ?", [$id]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8',
        ]);

        DB::update("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?", [
            Hash::make($request->password),
            $id
        ]);

        return redirect()->back()->with('success', 'Contraseña actualizada con éxito.');
    }
}
