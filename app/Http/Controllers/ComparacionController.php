<?php

namespace App\Http\Controllers;

use App\Models\SeleccionHortalizas;
use App\Models\ConfigSensores;
use App\Models\RegistroMediciones;
use Illuminate\Http\Request;

class ComparacionController extends Controller
{
    public function index()
    {
        // 1) Hortaliza seleccionada (igual que en Welcome)
        $selectedCrop = SeleccionHortalizas::where('seleccion', 1)
            ->orderByDesc('fecha')
            ->first();

        // 2) Mapa de sensores (clave usada en Blade + unidad)
        $sensorMap = [
            2 => ['key' => 'temp_ambiente', 'label' => 'Temperatura ambiente', 'unit' => ' °C', 'icon' => 'ri-temp-hot-line'],
            4 => ['key' => 'humedad',       'label' => 'Humedad ambiente',     'unit' => ' %',  'icon' => 'ri-water-percent-line'],
            1 => ['key' => 'ph',            'label' => 'pH del agua',           'unit' => '',    'icon' => 'ri-test-tube-line'],
            3 => ['key' => 'temp_agua',     'label' => 'Temperatura del agua',  'unit' => ' °C', 'icon' => 'ri-temp-cold-line'],
            5 => ['key' => 'orp',           'label' => 'ORP (RedOx)',           'unit' => ' mV', 'icon' => 'ri-bubble-chart-line'],
            6 => ['key' => 'ultrasonico',   'label' => 'Nivel ultrasónico',     'unit' => ' cm', 'icon' => 'ri-radar-line'],
        ];

        // 3) Rangos para la hortaliza seleccionada
        $ranges = [];
        if ($selectedCrop) {
            $configs = ConfigSensores::where('id_hortaliza', $selectedCrop->id_hortaliza)->get();
            foreach ($configs as $cfg) {
                if (isset($sensorMap[$cfg->id_sensor])) {
                    $meta = $sensorMap[$cfg->id_sensor];
                    $ranges[$meta['key']] = [
                        'min'  => (float)$cfg->valor_min_acept,
                        'max'  => (float)$cfg->valor_max_acept,
                        'unit' => $meta['unit'],
                        'label'=> $meta['label'],
                        'icon' => $meta['icon'],
                    ];
                }
            }
        }

        // 4) Último registro de mediciones (según tu modelo: fecha)
        $latest = RegistroMediciones::orderByDesc('fecha')->first()
                 ?? RegistroMediciones::orderByDesc('created_at')->first();

        // 5) Medidas mapeadas a las claves usadas en Blade (leyendo columnas reales del modelo)
        $measurements = null;
        if ($latest) {
            $measurements = [
                'temp_ambiente' => is_null($latest->tam_value)   ? null : (float)$latest->tam_value,
                'humedad'       => is_null($latest->hum_value)   ? null : (float)$latest->hum_value,
                'ph'            => is_null($latest->ph_value)    ? null : (float)$latest->ph_value,
                'temp_agua'     => is_null($latest->tagua_value) ? null : (float)$latest->tagua_value,
                'orp'           => is_null($latest->ce_value)    ? null : (float)$latest->ce_value,
                'ultrasonico'   => is_null($latest->us_value)    ? null : (float)$latest->us_value,
            ];
        }

        return view('Dashboard.ComparacionView.comparacion', [
            'selectedCrop' => $selectedCrop,
            'ranges'       => $ranges,
            'measurements' => $measurements,
            'latest'       => $latest,
        ]);
    }
}