<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Valida credenciales + rate limiting
        $request->authenticate();

        // Usuario autenticado temporalmente
        $user = $request->user();

        $isAdmin = method_exists($user, 'hasRole') ? $user->hasRole('admin') : (($user->role ?? null) === 'admin');
        
        // Bloquear si el estado no es "Activo"
        if ($user->estado !== 'Activo') {
            $msg = match ($user->estado) {
                'Solicitado' => 'Tu solicitud sigue en proceso. No puedes iniciar sesión hasta ser aprobada.',
                'Inactivo'   => 'Tu cuenta está Inactiva. Si crees que es un error, contáctanos.',
                default      => 'Tu cuenta no está activa.',
            };

            \Illuminate\Support\Facades\Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->with('warning', $msg)
                ->withInput($request->only('email'));
        }

        // Solo usuarios activos
        $request->session()->regenerate();

        // ==== Redirección según rol ====
        // Ajusta los nombres de roles y rutas si los tienes distintos
        if ($isAdmin) {
            $defaultRoute = route('scada.index', absolute: false);
        } else {
            // ruta para usuario normal
            $defaultRoute = route('dashboard', absolute: false);
        }

        return redirect()->intended($defaultRoute)
            ->with('success', 'Has iniciado sesión con éxito.');
    }



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
