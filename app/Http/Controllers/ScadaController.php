<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SeleccionHortalizas;

class ScadaController extends Controller
{
    public function index()
    {
        $selectedCrop = SeleccionHortalizas::where('seleccion', 1)
            ->orderByDesc('fecha')
            ->first();

        return view('Dashboard.ScadaView.scada', ['selectedCrop' => $selectedCrop]);
    }
}