<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin($tipo)
    {
        return view('login', compact('tipo'));
    }

    public function login(Request $request, $tipo)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role !== $tipo) {
                Auth::logout();

                return back()->withInput($request->only('email'))
                    ->with('erro', 'Este usuário não pertence ao perfil selecionado.');
            }

            return redirect('/'.$user->role);
        }

        return back()->withInput($request->only('email'))->with('erro', 'Login inválido');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); // tira o login e manda para a página inicial
    }
}
