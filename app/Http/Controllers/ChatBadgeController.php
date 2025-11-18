<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatBadgeController extends Controller
{
    public function count(Request $request)
    {
        $userId = $request->user()->id;

        // Chatify guarda mensajes en 'ch_messages' (from_id, to_id, seen, ...)
        // Contamos los que están dirigidos al usuario autenticado y no vistos.
        $unread = DB::table('ch_messages')
            ->where('to_id', $userId)
            ->where('seen', 0)
            ->count();

        return response()->json(['unread' => $unread]);
    }
}