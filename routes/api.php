<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controladores API
use App\Http\Controllers\Api\DataApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\SeleccionHortalizaApiController;



Route::post('/login', [UserApiController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    Route::get('/actuadores', [DataApiController::class, 'actuadores']);
    Route::get('/config-sensores', [DataApiController::class, 'configSensores']);
    Route::get('/hortalizas', [DataApiController::class, 'hortalizas']);
    Route::get('/nutrientes', [DataApiController::class, 'nutrientes']);
    Route::get('/registro-mediciones', [DataApiController::class, 'registroMediciones']);
    Route::get('/seleccion-hortalizas', [DataApiController::class, 'seleccionHortalizas']);
    Route::get('/sensores', [DataApiController::class, 'sensores']);


    Route::get('/hortaliza/actual', [SeleccionHortalizaApiController::class, 'get']);


    Route::post('/hortaliza/cambiar', [SeleccionHortalizaApiController::class, 'set']);
});
