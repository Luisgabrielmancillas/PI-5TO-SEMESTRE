<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Chatify\Models\Message as ChatifyMessage; // <- ESTE es el namespace correcto

class ChatifyPatchController extends Controller
{
    public function deleteMessage(Request $request)
    {
        $id = (int) $request->input('id');
        if (!$id) {
            return response()->json(['ok' => false, 'error' => 'Missing id'], 422);
        }

        $msg = ChatifyMessage::find($id);

        // Si ya no existe, responde OK (idempotente)
        if (!$msg) {
            return response()->json(['ok' => true, 'deleted' => true], 200);
        }

        // Seguridad: solo participantes pueden borrar
        $me = (int) Auth::id();
        if ($msg->from_id !== $me && $msg->to_id !== $me) {
            return response()->json(['ok' => false, 'error' => 'Forbidden'], 403);
        }

        // Borrar adjunto si existe (sin romper si falla)
        if (!empty($msg->attachment)) {
            try {
                $disk   = config('chatify.storage_disk_name', 'public');
                $folder = trim(config('chatify.attachments.folder', 'attachments'), '/');
                $path   = $folder . '/' . ltrim($msg->attachment, '/');
                if (Storage::disk($disk)->exists($path)) {
                    Storage::disk($disk)->delete($path);
                }
            } catch (\Throwable $e) {
                Log::warning('Chatify delete attachment soft-fail', [
                    'message_id' => $msg->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Borrar el mensaje
        try {
            $msg->delete();
        } catch (\Throwable $e) {
            Log::error('Chatify delete message failed', [
                'message_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['ok' => false, 'error' => 'Delete failed'], 500);
        }

        // Chatify code.js solo revisa "deleted"
        return response()->json(['deleted' => true], 200);
    }
}