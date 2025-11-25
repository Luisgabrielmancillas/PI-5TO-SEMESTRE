<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegistroActuador;

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


    public function encender(Request $request)
    {
        $request->validate([
            'id_actuador' => 'required|integer'
        ]);

        $anterior = RegistroActuador::where('id_actuador', $request->id_actuador)
            ->orderBy('id_registro', 'DESC')
            ->first();

        $estadoAnterior = $anterior ? $anterior->estado_actual : 0;

        $nuevo = RegistroActuador::create([
            'id_actuador'     => $request->id_actuador,
            'estado_anterior' => $estadoAnterior,
            'estado_actual'   => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Actuador encendido',
            'registro' => $nuevo
        ]);
    }


    public function apagar(Request $request)
    {
        $request->validate([
            'id_actuador' => 'required|integer'
        ]);

        $anterior = RegistroActuador::where('id_actuador', $request->id_actuador)
            ->orderBy('id_registro', 'DESC')
            ->first();

        $estadoAnterior = $anterior ? $anterior->estado_actual : 0;

        $nuevo = RegistroActuador::create([
            'id_actuador'     => $request->id_actuador,
            'estado_anterior' => $estadoAnterior,
            'estado_actual'   => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Actuador apagado',
            'registro' => $nuevo
        ]);
    }
}
