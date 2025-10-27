<?php

namespace App\Http\Controllers;

use App\Models\Actuadores;

class ActuadoresController extends Controller
{
    public function index()
    {
        $actuadores = Actuadores::all(); // Obtener todos los actuadores
        return view('Dashboard.ActuadoresView.actuadores', compact('actuadores'));
    }
}
