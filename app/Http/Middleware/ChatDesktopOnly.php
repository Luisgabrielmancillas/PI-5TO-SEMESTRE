<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChatDesktopOnly
{
    public function handle(Request $request, Closure $next)
    {
        $ua = $request->header('User-Agent', '');

        // detección simple de móvil/tablet
        $isMobile = (bool) preg_match(
            '/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobile|Tablet/i',
            $ua
        );

        if ($isMobile) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'El chat y la descarga de pdf solo están disponibles en escritorio.'], 403);
            }
            return redirect()->route('dashboard')
                ->with('error', 'El chat y la descarga de pdf solo están disponibles en escritorio.');
        }

        return $next($request);
    }
}
