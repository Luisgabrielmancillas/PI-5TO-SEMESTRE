<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SeleccionHortalizas;
use App\Models\ConfigSensores;
use App\Models\Sensores;
use Carbon\Carbon;

class HortalizasController extends Controller
{
    // Mostrar la vista con todas las hortalizas y sus rangos óptimos
    public function index()
    {
        // Cargar todas las hortalizas con sus configuraciones y sensores relacionados
        $hortalizas = SeleccionHortalizas::with(['config_sensores.sensore'])->get();
        $seleccionada = SeleccionHortalizas::where('seleccion', 1)->first();

        // Iconos de hortalizas (ya centralizados aquí)
        $icons = [
            'lechuga' => 'lechuga.png',
            'espinaca' => 'espinaca.png',
            'acelga' => 'acelga.png',
            'rucula' => 'rucula.png',
            'albahaca' => 'albahaca.png',
            'mostaza' => 'mostaza.png',
        ];

        return view('Dashboard.HortalizasView.hortalizas', compact('hortalizas', 'seleccionada', 'icons'));
    }

    // Cambiar hortaliza activa
    public function cambiar(Request $request)
    {
        $request->validate([
            'id_hortaliza' => 'required|integer|exists:seleccion_hortalizas,id_hortaliza'
        ]);

        // Reinicia todas las selecciones
        SeleccionHortalizas::query()->update(['seleccion' => 0]);

        // Activa la nueva
        $hortaliza = SeleccionHortalizas::find($request->id_hortaliza);
        $hortaliza->seleccion = 1;
        $hortaliza->fecha = Carbon::now();
        $hortaliza->save();

        return redirect()->back()->with('success', 'Hortaliza seleccionada correctamente');
    }
}
