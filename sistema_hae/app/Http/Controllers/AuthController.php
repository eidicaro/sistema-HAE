<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin($tipo)
    {
        return view('login', compact('tipo'));
    }

    public function login(Request $request, $tipo)
    {
        $user = DB::table('users')
            ->where('email', $request->email)
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session([
                'tipo' => $user->type,
                'user' => $user
            ]);

            return redirect('/' . $user->type);
        }

        return back()->with('erro', 'Login inválido');
    }

            public function handle($request, Closure $next, $tipo)
        {
            if (session('tipo') !== $tipo && session('tipo') !== 'admin') {
                abort(403);
            }

            return $next($request);
        }
}