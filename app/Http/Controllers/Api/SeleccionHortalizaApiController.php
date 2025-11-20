<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SeleccionHortalizas;
use Illuminate\Http\Request;

class SeleccionHortalizaApiController extends Controller
{

    public function get()
    {
        $selected = SeleccionHortalizas::where('seleccion', 1)
            ->orderByDesc('fecha')
            ->first();

        return response()->json($selected);
    }


    public function set(Request $request)
    {
        $request->validate([
            'id_hortaliza' => 'required|integer'
        ]);


        SeleccionHortalizas::query()->update(['seleccion' => 0]);


        $sel = SeleccionHortalizas::create([
            'id_hortaliza' => $request->id_hortaliza,
            'seleccion'    => 1,
            'fecha'        => now()
        ]);

        return response()->json([
            'success' => true,
            'selected' => $sel
        ]);
    }
}
