<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Actuadores;
use App\Models\ConfigSensores;
use App\Models\Nutrientes;
use App\Models\RegistroMediciones;
use App\Models\SeleccionHortalizas;
use App\Models\Sensores;

class DataApiController extends Controller
{

    public function actuadores()
    {
        return response()->json(Actuadores::all());
    }


    public function configSensores()
    {
        return response()->json(ConfigSensores::all());
    }


    public function nutrientes()
    {
        return response()->json(Nutrientes::all());
    }


    public function registroMediciones()
    {
        return response()->json(RegistroMediciones::all());
    }


    public function seleccionHortalizas()
    {
        return response()->json(SeleccionHortalizas::all());
    }


    public function sensores()
    {
        return response()->json(Sensores::all());
    }
}
