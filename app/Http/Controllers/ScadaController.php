<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SeleccionHortalizas;
use App\Models\RegistroMediciones;
use App\Models\Actuadores;
use App\Models\RegistroActuador;
use App\Services\MqttActuatorsClient;

class ScadaController extends Controller
{
    /**
     * Mapa id_actuador -> deviceId MQTT
     */
    protected array $deviceMap = [
        1 => 'doser_b', // Bomba FloraGro
        2 => 'doser_a', // Bomba FloraMicro
        3 => 'doser_c', // Bomba FloraBloom
        4 => 'pump',    // Bomba de agua
        5 => 'light',   // Lámpara LED
        6 => 'fan',     // Ventilador
    ];

    public function index()
    {
        $selectedCrop = SeleccionHortalizas::where('seleccion', 1)
            ->orderByDesc('fecha')
            ->first();

        $latest = RegistroMediciones::orderByDesc('fecha')->first()
                 ?? RegistroMediciones::orderByDesc('created_at')->first();

        // Estados actuales de actuadores
        $actuators = Actuadores::orderBy('id_actuador')->get();

        $statesById     = [];
        $statesByDevice = [];

        foreach ($actuators as $act) {
            $stateRow = RegistroActuador::where('id_actuador', $act->id_actuador)
                ->orderByDesc('fecha_cambio')
                ->first();

            $current = $stateRow ? (int) $stateRow->estado_actual : 0;

            $statesById[$act->id_actuador] = $current;

            if (isset($this->deviceMap[$act->id_actuador])) {
                $statesByDevice[$this->deviceMap[$act->id_actuador]] = $current;
            }
        }

        return view('Dashboard.ScadaView.scada', [
            'selectedCrop'        => $selectedCrop,
            'latest'              => $latest,
            'actuatorStatesById'  => $statesById,
            'actuatorStatesDevice'=> $statesByDevice,
        ]);
    }

    public function toggleActuator(Request $request)
    {
        // 1) Solo validamos lo que realmente llega desde JS
        $data = $request->validate([
            'id_actuador' => 'required|integer',
            'on'          => 'required|boolean',
        ]);

        $idActuador = (int) $data['id_actuador'];
        $isOn       = (bool) $data['on'];

        // 2) Mapeo id_actuador -> deviceId que entiende tu gateway MQTT
        $map = $this->deviceMap;

        $deviceId = $map[$idActuador] ?? null;

        // 3) Enviar comando por MQTT SOLO si sabemos el deviceId
        if ($deviceId !== null) {
            /* MqttActuatorsClient::sendSwitch($deviceId, $isOn); */
        }

        // 4) Actualizar (o crear) la fila de registro_actuador
        $registro = RegistroActuador::where('id_actuador', $idActuador)->first();

        if ($registro) {
            $registro->estado_anterior = $registro->estado_actual;
            $registro->estado_actual   = $isOn ? 1 : 0;
            $registro->fecha_cambio    = now();
            $registro->save();
        } else {
            RegistroActuador::create([
                'id_actuador'     => $idActuador,
                'estado_anterior' => 0,
                'estado_actual'   => $isOn ? 1 : 0,
                'fecha_cambio'    => now(),
            ]);
        }

        // 5) Respuesta para el JS (por si luego quieres usarla)
        return response()->json([
            'ok'            => true,
            'id_actuador'   => $idActuador,
            'device_id'     => $deviceId,
            'estado_actual' => $isOn ? 1 : 0,
        ]);
    }

    /** Ajax: devuelve estados actuales (por id_actuador) para refrescar desde web */
    public function actuatorStates()
    {
        $actuators = Actuadores::orderBy('id_actuador')->get();
        $data = [];

        foreach ($actuators as $act) {
            $stateRow = RegistroActuador::where('id_actuador', $act->id_actuador)
                ->orderByDesc('fecha_cambio')
                ->first();

            $data[$act->id_actuador] = $stateRow ? (int) $stateRow->estado_actual : 0;
        }

        return response()->json($data);
    }

    /**
     * Bloque parcial con los sensores (último registro).
     * Se usa para refrescar cada 10 segundos vía fetch().
     */
    public function block()
    {
        $latest = $this->getLatestMeasurement();

        return view('Dashboard.ScadaView._sensors-block', [
            'latest' => $latest,
        ]);
    }

    /**
     * Centraliza la obtención del último registro.
     */
    protected function getLatestMeasurement()
    {
        return RegistroMediciones::orderByDesc('fecha')->first()
            ?? RegistroMediciones::orderByDesc('created_at')->first();
    }

    // ==========================================================
    //      NUEVA LÓGICA DE DOSIFICACIÓN MANUAL PERISTÁLTICAS
    // ==========================================================

    /**
     * Arranca una dosificación manual para las bombas peristálticas (id 1,2,3).
     * - Envía comando MQTT con los mililitros.
     * - Marca estado_actual = 1 en registro_actuador.
     * - Devuelve la duración estimada (segundos) para que el front haga el timer.
     */
    public function manualDoseStart(Request $request)
    {
        $data = $request->validate([
            'id_actuador' => 'required|integer|in:1,2,3',
            'ml'          => 'required|integer|min:1|max:2000',
        ]);

        $idActuador = (int) $data['id_actuador'];
        $ml         = (int) $data['ml'];

        $deviceId = $this->deviceMap[$idActuador] ?? null;
        if (!$deviceId) {
            return response()->json([
                'ok'      => false,
                'message' => 'Actuador no encontrado',
            ], 404);
        }

        // 🔧 Factor de conversión ml -> segundos (ajústalo cuando calibres la bomba)
        $secondsPerMl = (float) config('hydrobox_mqtt.dose_seconds_per_ml', 1.0);
        $duration     = max(1, (int) ceil($ml * $secondsPerMl));

        // DB: estado_actual = 1
        $registro = RegistroActuador::where('id_actuador', $idActuador)->first();

        if ($registro) {
            $registro->estado_anterior = $registro->estado_actual;
            $registro->estado_actual   = 1;
            $registro->fecha_cambio    = now();
            $registro->save();
        } else {
            RegistroActuador::create([
                'id_actuador'     => $idActuador,
                'estado_anterior' => 0,
                'estado_actual'   => 1,
                'fecha_cambio'    => now(),
            ]);
        }

        // MQTT: enviamos la dosis en ml (el bridge se encarga del tiempo real de encendido)
        /* MqttActuatorsClient::sendDose($deviceId, $ml); */

        return response()->json([
            'ok'       => true,
            'duration' => $duration, // segundos estimados para mantener bloqueado el modal
        ]);
    }

    /**
     * Finaliza la dosificación manual:
     * - Envía comando de apagado por MQTT.
     * - Marca estado_actual = 0 en registro_actuador.
     */
    public function manualDoseStop(Request $request)
    {
        $data = $request->validate([
            'id_actuador' => 'required|integer|in:1,2,3',
        ]);

        $idActuador = (int) $data['id_actuador'];

        $deviceId = $this->deviceMap[$idActuador] ?? null;

        // DB: estado_actual = 0
        $registro = RegistroActuador::where('id_actuador', $idActuador)->first();

        if ($registro) {
            $registro->estado_anterior = $registro->estado_actual;
            $registro->estado_actual   = 0;
            $registro->fecha_cambio    = now();
            $registro->save();
        } else {
            RegistroActuador::create([
                'id_actuador'     => $idActuador,
                'estado_anterior' => 1,
                'estado_actual'   => 0,
                'fecha_cambio'    => now(),
            ]);
        }

        if ($deviceId) {
            /* MqttActuatorsClient::sendSwitch($deviceId, false); */
        }

        return response()->json(['ok' => true]);
    }
}