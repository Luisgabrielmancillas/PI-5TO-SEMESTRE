<?php

namespace App\Http\Controllers;

use App\Models\SeleccionHortalizas;
use App\Models\ConfigSensores;
use App\Models\RegistroMediciones;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function obtenerNotificaciones()
    {
        // 1. Última hortaliza seleccionada
        $selectedCrop = SeleccionHortalizas::where('seleccion', 1)
            ->orderByDesc('fecha')
            ->first();

        if (!$selectedCrop) {
            return response()->json(['notificaciones' => []]);
        }

        // 2. Mapa de sensores
        $sensorMap = [
            2 => ['key' => 'temp_ambiente', 'label' => 'Temperatura ambiente', 'unit' => '°C'],
            4 => ['key' => 'humedad',       'label' => 'Humedad ambiente',     'unit' => '%'],
            1 => ['key' => 'ph',            'label' => 'pH del agua',          'unit' => ''],
            3 => ['key' => 'temp_agua',     'label' => 'Temperatura del agua', 'unit' => '°C'],
            5 => ['key' => 'orp',           'label' => 'ORP (RedOx)',          'unit' => 'mV'],
            6 => ['key' => 'ultrasonico',   'label' => 'Nivel ultrasónico',    'unit' => 'cm'],
        ];

        // 3. Rangos configurados
        $configs = ConfigSensores::where('id_hortaliza', $selectedCrop->id_hortaliza)->get();
        $ranges = [];
        foreach ($configs as $cfg) {
            if (isset($sensorMap[$cfg->id_sensor])) {
                $meta = $sensorMap[$cfg->id_sensor];
                $ranges[$meta['key']] = [
                    'min'  => (float) $cfg->valor_min_acept,
                    'max'  => (float) $cfg->valor_max_acept,
                    'label'=> $meta['label'],
                    'unit' => $meta['unit'],
                ];
            }
        }

        // 4. Último registro de mediciones
        $latest = RegistroMediciones::orderByDesc('fecha')->first()
                   ?? RegistroMediciones::orderByDesc('created_at')->first();

        if (!$latest) {
            return response()->json(['notificaciones' => []]);
        }

        $measurements = [
            'temp_ambiente' => (float) $latest->tam_value,
            'humedad'       => (float) $latest->hum_value,
            'ph'            => (float) $latest->ph_value,
            'temp_agua'     => (float) $latest->tagua_value,
            'orp'           => (float) $latest->ce_value,
            'ultrasonico'   => (float) $latest->us_value,
        ];

        // 5. Generar notificaciones según rangos
        $notificaciones = [];
        foreach ($ranges as $key => $range) {
            $valor = $measurements[$key] ?? null;
            if ($valor === null) continue;

            if ($valor < $range['min']) {
                $notificaciones[] = [
                    'tipo' => 'bajo',
                    'mensaje' => "{$range['label']} está por debajo del rango ({$valor}{$range['unit']})",
                ];
            } elseif ($valor > $range['max']) {
                $notificaciones[] = [
                    'tipo' => 'alto',
                    'mensaje' => "{$range['label']} está por encima del rango ({$valor}{$range['unit']})",
                ];
            }
        }

        return response()->json(['notificaciones' => $notificaciones]);
    }
}
