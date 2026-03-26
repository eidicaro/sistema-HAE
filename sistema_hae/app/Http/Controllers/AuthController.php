<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin($tipo)
    {
        return view('login', compact('tipo'));
    }

    public function login(Request $request, $tipo)
    {
        if ($tipo == 'direcao') {
            $user = DB::table('direcao')
                ->where('nome', $request->nome)
                ->where('senha', $request->senha)
                ->first();
        } else {
            $user = DB::table($tipo)
                ->where('cpf', $request->cpf)
                ->where('senha', $request->senha)
                ->first();
        }

        if ($user) {
            session(['tipo' => $tipo, 'user' => $user]);
            return redirect("/$tipo");
        }

        return back()->with('erro', 'Login inválido');
    }
}
