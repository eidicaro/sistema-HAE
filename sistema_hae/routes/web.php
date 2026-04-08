<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HaeController;
use App\Http\Controllers\ParecerController;
use App\Http\Controllers\DirecaoController;
use App\Http\Controllers\SemestresController;

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
    Route::get('/professor', [HaeController::class, 'index'])->name('professor');
    Route::get('/coordenador', [HaeController::class, 'index'])->name('coordenador');
    Route::get('/direcao', [HaeController::class, 'index'])->name('direcao');


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

    //editar Hae
    Route::put('/hae/{id}', [HaeController::class, 'update'])->name('hae.update');
    Route::get('/hae/{id}/edit', [HaeController::class, 'edit'])->name('hae.edit');

    // salvar parecer
    Route::post('/parecer/{hae_id}', [ParecerController::class, 'store'])->middleware('auth');

    //decisão
    Route::post('/direcao/decisao/{id}', [DirecaoController::class, 'decisao']);


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
    // limitação de qtde de hae
    Route::get('/direcao/limites', function () {
        return view('direcao.limites');
    });

    Route::post('/direcao/limites', function (Request $request) {

        \App\Models\LimiteHae::updateOrCreate(
            ['tipo' => $request->tipo],
            ['carga_total' => $request->carga_total]
        );

        return back()->with('success', 'Limite salvo!');
    })->name('direcao.limites.salvar');



    /*
    |--------------------------------------------------------------------------
    | TELAS AUXILIARES (DIREÇÃO)
    |--------------------------------------------------------------------------
    */

    Route::get('/resultados-dir', [DirecaoController::class, 'resultados']);


    /*
    |--------------------------------------------------------------------------
    | CONTROLLE DE SEMESTRE
    |--------------------------------------------------------------------------
    */
    Route::get('/semestres', [SemestresController::class, 'index']);
    Route::post('/semestres', [SemestresController::class, 'store']);
    Route::post('/semestres/{id}/ativar', [SemestresController::class, 'ativar']);
});
