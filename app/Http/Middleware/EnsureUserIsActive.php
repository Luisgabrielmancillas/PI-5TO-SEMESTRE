<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->estado !== 'Activo') {
            Auth::logout();

            return redirect()->route('login')->with(
                'warning',
                $user->estado === 'Solicitado'
                    ? 'Tu solicitud sigue en proceso. No puedes acceder hasta ser aprobado.'
                    : 'Tu cuenta está Inactiva. Contacta al administrador.'
            );
        }

        return $next($request);
    }
}
