<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DataApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\SeleccionHortalizaApiController;
use App\Http\Controllers\Api\RegistroActuadorApiController;



Route::post('/login', [UserApiController::class, 'login']);
Route::get('/actuadores', [DataApiController::class, 'actuadores']);
Route::get('/config-sensores', [DataApiController::class, 'configSensores']);
Route::get('/nutrientes', [DataApiController::class, 'nutrientes']);
Route::get('/registro-mediciones', [DataApiController::class, 'registroMediciones']);
Route::get('/seleccion-hortalizas', [DataApiController::class, 'seleccionHortalizas']);
Route::get('/sensores', [DataApiController::class, 'sensores']);
Route::get('/hortaliza/actual', [SeleccionHortalizaApiController::class, 'get']);
Route::post('/hortaliza/cambiar', [SeleccionHortalizaApiController::class, 'set']);


Route::post('/actuador/estado', [RegistroActuadorApiController::class, 'estado']);
Route::post('/actuador/encender', [RegistroActuadorApiController::class, 'encender']);
Route::post('/actuador/apagar', [RegistroActuadorApiController::class, 'apagar']);
