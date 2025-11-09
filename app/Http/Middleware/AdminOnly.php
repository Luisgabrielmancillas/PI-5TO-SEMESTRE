<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || strcasecmp((string) $user->role, 'admin') !== 0) {
            abort(403, 'Solo administradores.');
        }

        return $next($request);
    }
}


