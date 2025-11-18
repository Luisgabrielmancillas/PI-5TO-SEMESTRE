<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateController extends Controller
{
    public function traducir(Request $request)
    {
        $textos = $request->input('textos', []);
        $idioma = $request->input('idioma', 'es');

        if (empty($textos) || !is_array($textos)) {
            return response()->json(['error' => 'No hay textos válidos'], 400);
        }

        $tr = new GoogleTranslate();
        $tr->setTarget($idioma);

        $traducciones = [];
        foreach ($textos as $texto) {
            try {
                $traducciones[] = $tr->translate($texto);
            } catch (\Throwable $e) {
                $traducciones[] = $texto;
            }
        }

        return response()->json([
            'idioma' => $idioma,
            'traducciones' => $traducciones
        ]);
    }
}
