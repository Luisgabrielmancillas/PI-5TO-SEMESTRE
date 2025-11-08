<?php

namespace App\Http\Controllers;

use App\Models\SeleccionHortalizas;

class GestionController extends Controller
{
    public function index()
    {
        $selectedCrop = SeleccionHortalizas::where('seleccion', 1)
            ->orderByDesc('fecha')
            ->first();

        return view('Dashboard.GestionUsuariosView.gestion', ['selectedCrop' => $selectedCrop]);
    }
}
