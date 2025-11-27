<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
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
            'estado'      => 'required|in:0,1',  // 0 = OFF, 1 = ON
        ]);

        $idActuador = (int) $data['id_actuador'];
        $estado     = (int) $data['estado'];

        try {
            // 2) Intentar usar el PROCEDURE (si existe y funciona)
            DB::statement('CALL sp_cambiar_estado_actuador(?, ?)', [
                $idActuador,
                $estado,
            ]);
        } catch (QueryException $e) {
            // ⚠️ Si el procedure no existe o falla, hacemos el cambio "a mano" en una transacción
            if (str_contains($e->getMessage(), 'sp_cambiar_estado_actuador')) {

                DB::transaction(function () use ($idActuador, $estado) {
                    // Leer estado anterior
                    $anterior = DB::table('actuadores')
                        ->where('id_actuador', $idActuador)
                        ->value('estado_actual');

                    if ($anterior === null) {
                        throw new \RuntimeException('Actuador no encontrado');
                    }

                    // Actualizar tabla actuadores
                    DB::table('actuadores')
                        ->where('id_actuador', $idActuador)
                        ->update(['estado_actual' => $estado]);

                    // Insertar en historial
                    DB::table('registro_actuador')->insert([
                        'id_actuador'     => $idActuador,
                        'estado_anterior' => (int) $anterior,
                        'estado_actual'   => $estado,
                        'fecha_cambio'    => now(),
                    ]);
                });

            } else {
                // Otro error de BD → devolvemos 500
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cambiar el estado del actuador',
                    'error'   => $e->getMessage(),
                ], 500);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado del actuador',
                'error'   => $e->getMessage(),
            ], 500);
        }

        // 3) Leer último registro del historial para regresarlo
        $registro = DB::table('registro_actuador')
            ->where('id_actuador', $idActuador)
            ->orderByDesc('id_registro')
            ->first();

        return response()->json([
            'success'  => true,
            'message'  => $estado === 1 ? 'Actuador encendido' : 'Actuador apagado',
            'registro' => $registro,
        ]);
    }


}