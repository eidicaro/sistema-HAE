<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () { return view('welcome'); } );

// tela de login
Route::get('/login/{tipo}', [AuthController::class, 'showLogin']);

// processa login
Route::post('/login/{tipo}', [AuthController::class, 'login']);


Route::get('/professor', function () { return view('professor'); })->middleware('auth.tipo:professor');

Route::get('/coordenador', function () { return view('coordenador'); })->middleware('auth.tipo:coordenador');

Route::get('/direcao', function () { return view('direcao'); })->middleware('auth.tipo:direcao');
