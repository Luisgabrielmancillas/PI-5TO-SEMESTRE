<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\HortalizasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ComparacionController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\ChatBadgeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;


Route::middleware('guest')->group(function () {
    // Landing
    Route::get('/', [WelcomeController::class, 'index'])->name('landing');

    // Si alguien intenta /logout sin sesión
    Route::get('/logout', function () {
        return redirect()->route('login')->with('error', 'No tienes una sesión activa.');
    })->name('logout.get');
});

/* Route::get('/mail-test', function () {
    Mail::raw('Prueba OK', function ($m) {
        $m->to('pihydrobox@gmail.com')->subject('HydroBox • Prueba SMTP');
    });
    return 'enviado';
}); */
//Prueba


Route::middleware(['auth', 'active', 'verified'])->group(function () {
    Route::get('/dashboard',         [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/latest',  [DashboardController::class, 'getLatestData'])->name('dashboard.latest');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart');

    Route::get('/history',        [HistoryController::class, 'index'])->name('history');
    Route::get('/history/data',   [HistoryController::class, 'data'])->name('history.data');
    Route::get('/history/table',  [HistoryController::class, 'table'])->name('history.table');

    Route::get('/hortalizas',           [HortalizasController::class, 'index'])->name('hortalizas');
    Route::post('/hortalizas/cambiar',  [HortalizasController::class, 'cambiar'])->name('hortalizas.cambiar');

    Route::get('/comparacion',     [ComparacionController::class, 'index'])->name('comparacion');
    Route::prefix('gestionusuarios')
        ->name('gestion.')
        ->middleware(['admin'])       
        ->group(function () {
            Route::get('/',         [GestionController::class, 'index'])->name('index');
            Route::get('/table',    [GestionController::class, 'table'])->name('table');

            Route::put('/{user}/accept',   [GestionController::class, 'accept'])->name('accept');
            Route::put('/{user}/activate', [GestionController::class, 'activate'])->name('activate');
            Route::put('/{user}/suspend',  [GestionController::class, 'suspend'])->name('suspend');
            Route::delete('/{user}',       [GestionController::class, 'reject'])->name('reject');
        });
});


Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/profile',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/chat/unread-count', [ChatBadgeController::class, 'count'])->name('chat.unread');
});


Route::fallback(function () {
    $msg = 'La ruta solicitada no está disponible.';

    return redirect()
        ->route(Auth::check() ? 'dashboard' : 'landing')
        ->with('error', $msg);
});

require __DIR__ . '/auth.php';
