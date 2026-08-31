<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin($tipo)
    {
        return view('login', compact('tipo'));
    }

    public function login(Request $request, $tipo)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        $credentials['email'] = Str::lower(trim($credentials['email']));

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role !== $tipo) {
                Log::warning('Falha de autenticação por perfil incorreto', [
                    'email_hash' => hash('sha256', $credentials['email']),
                    'perfil_solicitado' => $tipo,
                    'ip' => $request->ip(),
                ]);

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withInput($request->only('email'))
                    ->with('erro', 'E-mail, senha ou perfil inválido.');
            }

            $request->session()->regenerate();

            Log::info('Autenticação realizada', [
                'user_id' => $user->id,
                'role' => $user->role,
                'ip' => $request->ip(),
            ]);

            return redirect()->route($user->role);
        }

        Log::warning('Falha de autenticação', [
            'email_hash' => hash('sha256', $credentials['email']),
            'ip' => $request->ip(),
        ]);

        return back()->withInput($request->only('email'))
            ->with('erro', 'E-mail, senha ou perfil inválido.');
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Sessão encerrada', [
            'user_id' => $userId,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('home');
    }
}
