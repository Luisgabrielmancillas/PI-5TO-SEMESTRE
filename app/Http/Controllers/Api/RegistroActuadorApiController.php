<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegistroActuador;
use Illuminate\Support\Facades\DB;

class RegistroActuadorApiController extends Controller
{

    public function estado(Request $request)
    {
        $request->validate([
            'id_actuador' => 'required|integer'
        ]);

        $ultimo = RegistroActuador::where('id_actuador', $request->id_actuador)
            ->orderBy('id_registro', 'DESC')
            ->first();

        return response()->json([
            'success' => true,
            'estado_actual' => $ultimo ? $ultimo->estado_actual : 0
        ]);
    }


    public function cambiarEstado(Request $request)
    {
        // 1) Validar datos de entrada
        $data = $request->validate([
            'id_actuador' => 'required|integer',
            // puedes usar 'boolean' si quieres true/false, pero con el SP es más claro 0/1
            'estado'      => 'required|in:0,1',
        ]);

        $idActuador = (int) $data['id_actuador'];
        $estado     = (int) $data['estado']; // 1 = ON, 0 = OFF

        try {
            // 2) Llamar al procedure
            DB::statement('CALL sp_cambiar_estado_actuador(?, ?)', [
                $idActuador,
                $estado,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Si el PROCEDURE hizo SIGNAL 'Actuador no encontrado'
            if (str_contains($e->getMessage(), 'Actuador no encontrado')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Actuador no encontrado',
                ], 404);
            }

            // Otro error de BD
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado del actuador',
            ], 500);
        }

        // 3) Leer el último registro del historial para regresarlo en la respuesta
        $registro = DB::table('registro_actuador')
            ->where('id_actuador', $idActuador)
            ->orderByDesc('id_registro')   // mismo que usabas antes
            ->first();

        return response()->json([
            'success'  => true,
            'message'  => $estado === 1 ? 'Actuador encendido' : 'Actuador apagado',
            'registro' => $registro,
        ]);
    }

}