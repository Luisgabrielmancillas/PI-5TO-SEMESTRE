<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SeleccionHortalizas;
use App\Models\RegistroMediciones;

class ScadaController extends Controller
{
    public function index()
    {
        $selectedCrop = SeleccionHortalizas::where('seleccion', 1)
            ->orderByDesc('fecha')
            ->first();

        $latest = $this->getLatestMeasurement();

        return view('Dashboard.ScadaView.scada', [
            'selectedCrop' => $selectedCrop,
            'latest'       => $latest,
        ]);
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
