<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HaeController;
use App\Http\Controllers\ParecerController;
use App\Http\Controllers\DirecaoController;

/*
|--------------------------------------------------------------------------
| ROTA INICIAL
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTENTICAÇÃO
|--------------------------------------------------------------------------
*/

// tela de login (tipo: professor, coordenador, direcao)
Route::get('/login/{tipo}', [AuthController::class, 'showLogin']);

Route::get('/login', function () {
    return redirect('/'); // padrão
})->name('login');

Route::post('/logout', [AuthController::class, 'logout']);

// processa login
Route::post('/login/{tipo}', [AuthController::class, 'login']);

// logout
Route::post('/logout', [AuthController::class, 'logout']);


/*
|--------------------------------------------------------------------------
| ROTAS PROTEGIDAS (PRECISA ESTAR LOGADO)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PROFESSOR / COORD / DIREÇÃO (DASHBOARDS)
    |--------------------------------------------------------------------------
    */

    // cada rota usa o mesmo controller (index decide o que mostrar)
    Route::get('/professor', [HaeController::class, 'index']);
    Route::get('/coordenador', [HaeController::class, 'index']);
    Route::get('/direcao', [HaeController::class, 'index']);


    /*
    |--------------------------------------------------------------------------
    | FORMULÁRIO HAE
    |--------------------------------------------------------------------------
    */

    // abre formulário com tipo dinâmico (?tipo=graduacao)
    Route::get('/formulario', function () {
        $tipo = request('tipo');
        return view('formulario', compact('tipo'));
    });

    // salvar HAE
    Route::post('/salvar-hae', [HaeController::class, 'store']);

    //mostrar hae
    Route::get('/hae/{id}', [HaeController::class, 'show']);

    // salvar parecer
    Route::post('/parecer/{hae_id}', [ParecerController::class, 'store'])->middleware('auth');


    /*
    |--------------------------------------------------------------------------
    | PARECER (RELATOR)
    |--------------------------------------------------------------------------
    */

    Route::post('/parecer', [ParecerController::class, 'store']);


    /*
    |--------------------------------------------------------------------------
    | DECISÃO (COORDENAÇÃO / DIREÇÃO)
    |--------------------------------------------------------------------------
    */

    Route::get('/direcao/relatores', [DirecaoController::class, 'relatores']);
    Route::post('/direcao/relatores/{hae}', [DirecaoController::class, 'atribuirRelator']);


    /*
    |--------------------------------------------------------------------------
    | TELAS AUXILIARES (DIREÇÃO)
    |--------------------------------------------------------------------------
    */

    Route::get('/resultados-dir', function () {
        return view('resultados-dir');
    });

    Route::get('/ver-relatores', function () {
        return view('ver-relatores');
    });

});
