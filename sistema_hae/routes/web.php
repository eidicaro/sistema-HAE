<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DirecaoController;
use App\Http\Controllers\HaeController;
use App\Http\Controllers\ParecerController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\SemestresController;
use App\Http\Controllers\SubtipoHaeController;
use App\Http\Controllers\TipoHaeController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('/login', fn () => redirect('/'))->name('login');
Route::get('/login/{tipo}', [AuthController::class, 'showLogin'])
    ->whereIn('tipo', User::ROLES);
Route::post('/login/{tipo}', [AuthController::class, 'login'])
    ->whereIn('tipo', User::ROLES)
    ->middleware('throttle:login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/professor', [HaeController::class, 'index'])
        ->middleware('auth.tipo:professor')
        ->name('professor');
    Route::get('/coordenador', [HaeController::class, 'index'])
        ->middleware('auth.tipo:coordenador')
        ->name('coordenador');
    Route::get('/direcao', [HaeController::class, 'index'])
        ->middleware('auth.tipo:direcao')
        ->name('direcao');

    Route::get('/hae/{id}', [HaeController::class, 'show'])->name('hae.show');
    Route::get('/hae/{id}/pdf', [HaeController::class, 'downloadPdf'])->name('hae.pdf');
    Route::get('/arquivo/{id}/download', [RelatorioController::class, 'download'])
        ->name('arquivo.download');
    Route::get('/arquivo/{id}/ver', [RelatorioController::class, 'ver'])
        ->name('arquivo.ver');

    Route::middleware('auth.tipo:professor')->group(function () {
        Route::get('/formulario', [HaeController::class, 'create'])->name('hae.create');
        Route::post('/salvar-hae', [HaeController::class, 'store'])->name('hae.store');
        Route::get('/hae/{id}/edit', [HaeController::class, 'edit'])->name('hae.edit');
        Route::put('/hae/{id}', [HaeController::class, 'update'])->name('hae.update');
        Route::get('/hae/{id}/relatorio', [RelatorioController::class, 'create'])
            ->name('relatorio.create');
        Route::post('/hae/{id}/relatorio', [RelatorioController::class, 'store'])
            ->name('relatorio.store');
    });

    Route::post('/parecer/{hae_id}', [ParecerController::class, 'store'])
        ->middleware('auth.tipo:professor,coordenador')
        ->name('parecer.store');

    Route::middleware('auth.tipo:direcao')->group(function () {
        Route::post('/direcao/decisao/{id}', [DirecaoController::class, 'decisao'])
            ->name('direcao.decisao');
        Route::get('/direcao/relatores', [DirecaoController::class, 'relatores'])
            ->name('direcao.relatores');
        Route::post('/direcao/relatores/{hae}', [DirecaoController::class, 'atribuirRelator'])
            ->name('direcao.relatores.update');
        Route::get('/resultados-dir', [DirecaoController::class, 'resultados'])
            ->name('direcao.resultados');

        Route::get('/semestres', [SemestresController::class, 'index'])->name('semestres.index');
        Route::post('/semestres', [SemestresController::class, 'store'])->name('semestres.store');
        Route::post('/semestres/{id}/ativar', [SemestresController::class, 'ativar'])
            ->name('semestres.ativar');

        Route::post('/relatorio/{id}/aprovar', [RelatorioController::class, 'aprovar'])
            ->name('relatorio.aprovar');
        Route::post('/relatorio/{id}/reprovar', [RelatorioController::class, 'reprovar'])
            ->name('relatorio.reprovar');

        Route::prefix('direcao')->name('direcao.')->group(function () {
            Route::resource('tipos-hae', TipoHaeController::class)
                ->except(['show'])
                ->parameters(['tipos-hae' => 'tipoHae']);
            Route::post('tipos-hae/{tipoHae}/toggle', [TipoHaeController::class, 'toggle'])
                ->name('tipos-hae.toggle');
            Route::post('tipos-hae/{tipoHae}/subtipos', [SubtipoHaeController::class, 'store'])
                ->name('tipos-hae.subtipos.store');
            Route::put('tipos-hae/{tipoHae}/subtipos/{subtipoHae}', [SubtipoHaeController::class, 'update'])
                ->name('tipos-hae.subtipos.update');
            Route::post('tipos-hae/{tipoHae}/subtipos/{subtipoHae}/toggle', [SubtipoHaeController::class, 'toggle'])
                ->name('tipos-hae.subtipos.toggle');
            Route::delete('tipos-hae/{tipoHae}/subtipos/{subtipoHae}', [SubtipoHaeController::class, 'destroy'])
                ->name('tipos-hae.subtipos.destroy');

            Route::resource('professores', ProfessorController::class)
                ->except(['show'])
                ->parameters(['professores' => 'professor']);

            Route::get('exportar-csv', [HaeController::class, 'exportarcsv'])
                ->name('exportarcsv');
        });
    });
});
