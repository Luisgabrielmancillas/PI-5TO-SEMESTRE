<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SeleccionHortalizas;
use Carbon\Carbon;

class HortalizasController extends Controller
{
    // Mostrar la vista con todas las hortalizas
    public function index()
    {
        $hortalizas = SeleccionHortalizas::all();
        $seleccionada = SeleccionHortalizas::where('seleccion', 1)->first();

        return view('Dashboard.HortalizasView.hortalizas', compact('hortalizas', 'seleccionada'));
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
