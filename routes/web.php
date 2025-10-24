<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Pessoas\UtilizadorController;
use App\Http\Controllers\Atividades\EventoController;
use App\Http\Controllers\Financeiro\FaturaController;
use App\Http\Controllers\Inventario\InventarioController;
use App\Http\Controllers\Comunicacao\ComunicacaoController;
use App\Http\Controllers\Relatorios\RelatorioController;
use App\Http\Controllers\Config\ConfigController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::prefix('utilizadores')->name('utilizadores.')->group(function () {
        Route::get('{utilizador}',        [UtilizadorController::class,'show'])->name('show');
        Route::get('{utilizador}/editar', [UtilizadorController::class,'edit'])->name('edit');
        Route::put('{utilizador}',        [UtilizadorController::class,'update'])->name('update');
    });
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Gestão de Pessoas
    Route::resource('utilizadores', UtilizadorController::class)
        ->parameters(['utilizadores' => 'utilizador']);

    // Atividades & Eventos
    Route::resource('eventos', EventoController::class)->names('eventos'); // sem GET extra

    // Financeiro
    Route::get('faturas', [FaturaController::class, 'index'])->name('faturas.index');

    // Inventário
    Route::get('inventario', [InventarioController::class, 'index'])->name('inventario.index');

    // Comunicação
    Route::get('comunicacao', [ComunicacaoController::class, 'index'])->name('comunicacao.index');

    // Relatórios
    Route::get('relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');

    // Configurações
    Route::get('configuracoes', [ConfigController::class, 'index'])->name('config.index');
});

/* ===================== LOGOUT ===================== */
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

/* ===================== AUTH / HOME ===================== */
require __DIR__.'/auth.php';
Route::get('/home', fn () => redirect()->route('dashboard'))->name('home');
