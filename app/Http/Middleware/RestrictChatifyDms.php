<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class RestrictChatifyDms
{
    public function handle(Request $request, Closure $next)
    {
        // Aplica solo a rutas de Chatify (send, id, search, etc.)
        if (str_starts_with($request->path(), 'chatify')) {

            $user = $request->user();
            if (!$user) return abort(401);

            // Si viene envío de mensaje (to_id es común en Chatify para enviar)
            $toId = $request->input('id') ?? $request->input('to_id');

            if ($toId) {
                $to = User::find($toId);
                if (!$to) return abort(404);

                $isAdmin    = $user->role === 'admin';
                $toIsAdmin  = $to->role === 'admin';

                // Regla: solo admin<->usuario; NO usuario<->usuario
                $permitido = $isAdmin || $toIsAdmin;
                if (!$permitido) {
                    return abort(403, 'Mensajería no permitida entre usuarios.');
                }
            }
        }
        return $next($request);
    }
}
