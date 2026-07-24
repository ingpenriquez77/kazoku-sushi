<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('_id', 'asc')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'username' => 'required|string|max:50|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role'     => 'required|in:Administrador,Mesero,Cajero,Cocina',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $avatarPath = 'img/kazoku.png';

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'avatar'   => $avatarPath,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario registrado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:Administrador,Mesero,Cajero,Cocina'
        ]);

        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);

        return redirect()->route('usuarios.index')->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $user = User::findOrFail($id);

        if ($user->avatar && $user->avatar !== 'img/kazoku.png') {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario y foto borrados de la base de datos.');
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Contraseña actualizada con éxito.');
    }
}
