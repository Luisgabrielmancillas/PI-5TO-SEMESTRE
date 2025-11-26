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
        // Ajusta si tu amigo usó otro orden para las peristálticas 💡
        $map = [
            1 => 'doser_b',   // Bomba FloraGro
            2 => 'doser_a',   // Bomba FloraMicro
            3 => 'doser_c',   // Bomba FloraBloom
            4 => 'pump',      // Bomba de Agua
            5 => 'light',     // Lámpara LED
            6 => 'fan',       // Ventilador
        ];

        $deviceId = $map[$idActuador] ?? null;

        // 3) Enviar comando por MQTT SOLO si sabemos el deviceId
        if ($deviceId !== null) {
            MqttActuatorsClient::sendSwitch($deviceId, $isOn);
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
}
