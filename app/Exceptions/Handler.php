<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * Inputs que no se deben “flashear” al ocurrir una validación.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        // Aquí puedes registrar reportables si lo necesitas.
    }

    public function render($request, Throwable $e)
    {
        // No autenticado → login con aviso
        if ($e instanceof \Illuminate\Auth\AuthenticationException) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login')->with('error', 'Inicia sesión para continuar.');
        }

        // Método no permitido (ej. GET /logout donde solo hay POST)
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Method not allowed'], 405);
            }
            return redirect()
                ->route(Auth::check() ? 'dashboard' : 'landing')
                ->with('error', 'Método no permitido.');
        }

        // Ruta no encontrada
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Not found'], 404);
            }
            return redirect()
                ->route(Auth::check() ? 'dashboard' : 'landing')
                ->with('error', 'La ruta solicitada no está disponible.');
        }

        return parent::render($request, $e);
    }
}
