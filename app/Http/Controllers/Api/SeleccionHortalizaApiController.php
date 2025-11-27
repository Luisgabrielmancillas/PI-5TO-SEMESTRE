<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SeleccionHortalizas;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SeleccionHortalizaApiController extends Controller
{

    public function get()
    {
        $selected = SeleccionHortalizas::where('seleccion', 1)
            ->orderByDesc('fecha')
            ->first();

        return response()->json($selected);
    }


    public function set(Request $request)
    {
        // 1) Validar entrada
        $data = $request->validate([
            'id_hortaliza' => 'required|integer',
        ]);

        $id = (int) $data['id_hortaliza'];

        // 2) Llamar al procedure que hace todo el trabajo (pone todas en 0
        //    y sólo la indicada en 1, manejando la transacción dentro)
        DB::statement('CALL sp_seleccionar_hortaliza(?)', [$id]);

        // 3) Volver a leer la hortaliza seleccionada para devolverla al frontend
        $sel = SeleccionHortalizas::where('id_hortaliza', $id)
            ->where('seleccion', 1)
            ->first();

        // Si por algún motivo no quedó seleccionada (id inválido, rollback, etc.)
        if (!$sel) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo seleccionar la hortaliza (id inválido o sin cambios).',
            ], 422);
        }

        // 4) Respuesta normal
        return response()->json([
            'success'  => true,
            'selected' => $sel,
        ]);
    }
}
