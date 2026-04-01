<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Haes;
use App\Http\Controllers\HaeController;

class DirecaoController extends Controller
{
    public function relatores()
    {
        // professores e coordenadores
        $usuarios = User::whereIn('role', ['professor', 'coordenador'])->get();

        // todas as HAEs com relatores
        $haes = Haes::with('relatores')->get();

        return view('ver-relatores', compact('usuarios', 'haes'));
    }

    public function atribuirRelator(Request $request, $hae_id)
    {
        $hae = Haes::findOrFail($hae_id);

        $relatores = $request->relatores;

        $hae->relatores()->sync($relatores);

        return back()->with('sucesso', 'Relatores definidos!');
    }
}
