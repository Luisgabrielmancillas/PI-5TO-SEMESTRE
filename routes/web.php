<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\HortalizasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ComparacionController;
use App\Http\Controllers\ActuadoresController;
use App\Http\Controllers\TranslateController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Landing
    Route::get('/', [WelcomeController::class, 'index'])->name('landing');

    Route::get('/logout', function () {
        return redirect()->route('login')->with('error', 'No tienes una sesión activa.');
    })->name('logout.get');
});


/* if (app()->environment('local')) {
    Route::get('/forzar-404', fn() => abort(404))->name('test.404');
    Route::get('/forzar-403', fn() => abort(403))->name('test.403');
    Route::get('/forzar-419', fn() => abort(419))->name('test.419');
    Route::get('/forzar-500', fn() => abort(500))->name('test.500');
    Route::get('/forzar-503', fn() => abort(503))->name('test.503');
} */


/*
| Rutas autenticadas y (cuando procede) verificadas
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/latest', [DashboardController::class, 'getLatestData'])->name('dashboard.latest');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart');

    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/history/data', [HistoryController::class, 'data'])->name('history.data');
    Route::get('/history/table', [HistoryController::class, 'table'])->name('history.table');

    Route::get('/hortalizas', [HortalizasController::class, 'index'])->name('hortalizas');
    Route::post('/hortalizas/cambiar', [HortalizasController::class, 'cambiar'])->name('hortalizas.cambiar');

    Route::get('/comparacion', [ComparacionController::class, 'index'])->name('comparacion');

    // Dentro de middleware 'auth' y 'verified'
    Route::get('/actuadores', [ActuadoresController::class, 'index'])->name('actuadores.index');
    Route::get('/sensores', [App\Http\Controllers\SensoresController::class, 'index'])->name('sensores.index');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
| Fallback para rutas inexistentes: devuelve a la landing con aviso
*/
Route::fallback(function () {
    return redirect()->route('landing')->with('error', 'La ruta solicitada no está disponible.');
});

require __DIR__ . '/auth.php';


Route::fallback(function () {
    $msg = 'La ruta solicitada no está disponible.';

    return redirect()
        ->route(Auth::check() ? 'dashboard' : 'landing')
        ->with('error', $msg);
});

Route::post('/traducir', [TranslateController::class, 'traducir'])->name('traducir');