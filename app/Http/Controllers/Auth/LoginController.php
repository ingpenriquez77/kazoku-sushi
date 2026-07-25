<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Mostrar el formulario de login
    public function showLoginForm()
    {
        // Si el usuario ya inició sesión, redirigir directamente al dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }

        return view('auth.login');
    }

    // Procesar el inicio de sesión
    public function login(Request $request)
    {
        // 1. Validar la entrada
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Detectar si es email o username
        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // 3. Intentar autenticar
        $credentials = [
            $fieldType => $request->login,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Usamos route() para garantizar la URL absoluta de Laravel a /dashboard
            return redirect()->intended(route('dashboard.index'))
                ->with('success', '¡Bienvenido ' . Auth::user()->name . '!');
        }

        // 4. Si falla, regresar con error
        return back()->withErrors([
            'login' => 'Las credenciales no coinciden con nuestros registros.',
        ])->withInput($request->only('login'));
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
