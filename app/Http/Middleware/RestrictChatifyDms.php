<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

class RestrictChatifyDms
{
    public function handle(Request $request, Closure $next)
    {
        // Solo aplica a rutas de Chatify
        $prefix = trim(config('chatify.routes.prefix', 'chatify'), '/');
        if (! Str::startsWith($request->path(), $prefix)) {
            return $next($request);
        }

        $user = $request->user();
        if (! $user) {
            return $next($request); // dejar que 'auth' haga su trabajo
        }

        // Admin sin restricciones
        if (($user->role ?? null) === 'admin') {
            return $next($request);
        }

        // -------- No-admin: permitir endpoints que NO requieren validar "peer" ----------
        $route = optional($request->route());
        $routeName = $route ? $route->getName() : null;

        $whitelistNames = [
            // borrar 1 mensaje (usa id de mensaje, NO de usuario)
            'message.delete',
            // descarga adjuntos
            config('chatify.attachments.download_route_name'),
            // vistos, favoritos, ajustes, estado activo, auth pusher
            'messages.seen', 'favorites', 'avatar.update', 'activeStatus.set', 'pusher.auth',
            // listar contactos / detalles de la vista
            'contacts.get', 'search', 'shared',
        ];

        if (in_array($routeName, $whitelistNames, true)) {
            return $next($request);
        }

        // -------- Validar el "peer" SOLO en endpoints que sí conversan con alguien ----------
        // Tomamos el posible id del otro usuario desde body o ruta
        $peerId = $request->input('to_id')
               ?? $request->input('id')
               ?? $request->route('id'); // /chatify/{id}

        // Si no hay peer (p.ej abrir /chatify sin id), dejar pasar
        if ($peerId === null) {
            return $next($request);
        }

        $peerId = (int) $peerId;
        $myId   = (int) $user->id;
        $adminId = (int) (User::where('role', 'admin')->value('id') ?? 0);

        // Permitimos "Saved Messages" (hablar conmigo mismo)
        if ($peerId === $myId) {
            return $next($request);
        }

        // Permitimos hablar SOLO con admin
        if ($adminId && $peerId === $adminId) {
            return $next($request);
        }

        // Bloqueamos el resto
        abort(404);
    }
}