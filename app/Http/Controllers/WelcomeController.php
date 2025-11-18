<?php

namespace App\Http\Controllers;

use App\Models\SeleccionHortalizas;
use App\Models\ConfigSensores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class WelcomeController extends Controller
{
    public function index()
    {
        // Si ya hay sesión iniciada, manda directo al dashboard (mejor UX)
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // ===== Ya lo tenías (rangos para la hortaliza seleccionada, "Monitoreo") =====
        $selectedCrop = SeleccionHortalizas::where('seleccion', 1)
            ->orderByDesc('fecha')
            ->first();

        $sensorMap = [
            2 => ['key' => 'temp_ambiente', 'unit' => ' °C'],
            4 => ['key' => 'humedad',       'unit' => ' %'],
            1 => ['key' => 'ph',            'unit' => ''],
            3 => ['key' => 'temp_agua',     'unit' => ' °C'],
            5 => ['key' => 'orp',           'unit' => ' mV'],
            6 => ['key' => 'ultrasonico',   'unit' => 'cm'],
        ];

        $ranges = [];
        if ($selectedCrop) {
            $configs = ConfigSensores::where('id_hortaliza', $selectedCrop->id_hortaliza)->get();
            foreach ($configs as $cfg) {
                if (isset($sensorMap[$cfg->id_sensor])) {
                    $meta = $sensorMap[$cfg->id_sensor];
                    $ranges[$meta['key']] = [
                        'min'  => $cfg->valor_min_acept,
                        'max'  => $cfg->valor_max_acept,
                        'unit' => $meta['unit'],
                    ];
                }
            }
        }

        // ===== NUEVO: rangos por hortaliza para la sección "Guía de cultivo" =====
        // Necesitamos solo pH (id_sensor=1), ORP (id_sensor=5) y Temp agua (id_sensor=3)
        $sensorIds = ['ph' => 1, 'orp' => 5, 'temp_agua' => 3];

        // Tomamos todas las hortalizas definidas
        $crops = SeleccionHortalizas::orderBy('id_hortaliza')->get();

        // Estructura base: id_hortaliza => ['nombre'=>..., 'ph'=>null, 'orp'=>null, 'temp_agua'=>null]
        $guide = [];
        foreach ($crops as $c) {
            $guide[$c->id_hortaliza] = [
                'nombre'    => $c->nombre,
                'ph'        => null,
                'orp'       => null,
                'temp_agua' => null,
            ];
        }

        // Traemos solo las configuraciones de esos 3 sensores
        $configs = ConfigSensores::whereIn('id_hortaliza', $crops->pluck('id_hortaliza'))
            ->whereIn('id_sensor', array_values($sensorIds))
            ->get();

        // Helper para formatear min–max (recorta ceros y añade unidades)
        $fmt = function ($min, $max, $unit = '') {
            $f = function ($v) {
                $s = number_format((float)$v, 2, '.', '');
                return rtrim(rtrim($s, '0'), '.');
            };
            $min = $f($min); $max = $f($max);
            return "{$min}–{$max}{$unit}";
        };

        foreach ($configs as $cfg) {
            $hid = $cfg->id_hortaliza;
            if (!isset($guide[$hid])) continue;

            if ($cfg->id_sensor === $sensorIds['ph']) {
                $guide[$hid]['ph'] = $fmt($cfg->valor_min_acept, $cfg->valor_max_acept, '');
            } elseif ($cfg->id_sensor === $sensorIds['orp']) {
                $guide[$hid]['orp'] = $fmt($cfg->valor_min_acept, $cfg->valor_max_acept, ' mV');
            } elseif ($cfg->id_sensor === $sensorIds['temp_agua']) {
                $guide[$hid]['temp_agua'] = $fmt($cfg->valor_min_acept, $cfg->valor_max_acept, '°C');
            }
        }

        return view('welcome', [
            'selectedCrop' => $selectedCrop,
            'ranges'       => $ranges,
            'crops'        => $crops,   // Monitoreo
            'guide'        => $guide,   // Guía de cultivo (NUEVO)
        ]);
    }
}
