<?php

namespace App\Http\Controllers;

use App\Models\Semestres;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SemestresController extends Controller
{
    // Listar semestres
    public function index()
    {
        $semestres = Semestres::orderBy('id', 'desc')->get();
        $user = auth()->user();

        return view('semestres', compact('semestres', 'user'));
    }

    // Criar semestre
    public function store(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);

        Semestres::create([
            'nome' => $request->nome,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
            'ativo' => false,
        ]);

        return back()->with('success', 'Semestre criado com sucesso!');
    }

    // Ativar semestre
    public function ativar($id)
    {
        DB::transaction(function () use ($id): void {
            Semestres::where('ativo', true)->update(['ativo' => false]);
            Semestres::findOrFail($id)->update(['ativo' => true]);
        });

        return back()->with('success', 'Semestre ativado!');
    }
}
