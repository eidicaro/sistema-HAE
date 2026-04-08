<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semestres;

class SemestresController extends Controller
{
    // Listar semestres
    public function index()
    {
        $semestres = Semestres::orderBy('id', 'desc')->get();
        $user = auth()->user();
        return view('semestres', compact('semestres','user'));
    }

    // Criar semestre
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date',
        ]);

        Semestres::create([
            'nome' => $request->nome,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
            'ativo' => false
        ]);

        return back()->with('success', 'Semestre criado com sucesso!');
    }

    // Ativar semestre
    public function ativar($id)
    {
        // desativa todos
        Semestres::where('ativo', true)->update(['ativo' => false]);

        // ativa o escolhido
        $semestre = Semestres::findOrFail($id);
        $semestre->ativo = true;
        $semestre->save();

        return back()->with('success', 'Semestre ativado!');
    }
}