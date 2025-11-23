<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroMediciones;
use App\Models\SeleccionHortalizas;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /** Vista principal del dashboard */
    public function index()
    {
        $selectedCrop = SeleccionHortalizas::where('seleccion', 1)
            ->orderByDesc('fecha')
            ->first();

        return view('dashboard', ['selectedCrop' => $selectedCrop]);
    }

    /** Último registro (para tarjetas y gauges) */
    public function getLatestData()
    {
        $latest = RegistroMediciones::orderBy('fecha', 'desc')->first();

        if (!$latest) {
            return response()->json(['error' => 'No data found'], 404);
        }

        return response()->json([
            'tempAire' => $latest->tam_value,
            'humAire' => $latest->hum_value,
            'tempAgua' => $latest->tagua_value,
            'ph' => $latest->ph_value,
            'orp' => $latest->ce_value,
            'nivelAgua' => $latest->us_value,
            'timestamp' => Carbon::parse($latest->fecha)->format('d/m H:i:s')
        ]);
    }

    /** Datos para gráficas, solo de la hortaliza seleccionada */
    public function getChartData()
    {
        $selectedCrop = SeleccionHortalizas::where('seleccion', 1)
            ->orderByDesc('fecha')
            ->first();

        $records = RegistroMediciones::query()
            ->when($selectedCrop, function ($q) use ($selectedCrop) {
                $q->where('id_hortaliza', $selectedCrop->id_hortaliza);
            }, function ($q) {
                $q->whereRaw('1 = 0');
            })
            ->orderBy('fecha', 'desc')
            ->take(20)
            ->get()
            ->reverse();

        // Si no hay registros para esa hortaliza, devolvemos empty: true
        if ($records->isEmpty()) {
            return response()->json([
                'empty' => true,
            ]);
        }

        $labels = $records->pluck('fecha')->map(function ($f) {
            return Carbon::parse($f)->format('H:i');
        });

        // Datos para todas las mediciones
        $ph = $records->pluck('ph_value');
        $ce = $records->pluck('ce_value');
        $tempAgua = $records->pluck('tagua_value');
        $tempAire = $records->pluck('tam_value');
        $humAire = $records->pluck('hum_value');
        $nivelAgua = $records->pluck('us_value');

        // Calcular promedios para TODOS los sensores
        $avgPh = round($ph->avg(), 2);
        $avgCe = round($ce->avg(), 2);
        $avgTempAgua = round($tempAgua->avg(), 2);
        $avgTempAire = round($tempAire->avg(), 2);
        $avgHumAire = round($humAire->avg(), 2);
        $avgNivelAgua = round($nivelAgua->avg(), 2);

        return response()->json([
            'empty' => false,
            'labels' => $labels,
            'datasets' => [
                'ph' => $ph,
                'ce' => $ce,
                'tempAgua' => $tempAgua,
                'tempAire' => $tempAire,
                'humAire' => $humAire,
                'nivelAgua' => $nivelAgua
            ],
            'averages' => [
                'ph' => $avgPh,
                'ce' => $avgCe,
                'tempAgua' => $avgTempAgua,
                'tempAire' => $avgTempAire,
                'humAire' => $avgHumAire,
                'nivelAgua' => $avgNivelAgua
            ]
        ]);
    }
}