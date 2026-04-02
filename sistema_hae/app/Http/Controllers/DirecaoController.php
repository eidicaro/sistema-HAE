<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Haes;
use App\Http\Controllers\HaeController;
use App\Models\Decisao;

class DirecaoController extends Controller
{
    public function relatores()
    {
        // professores e coordenadores
        $usuarios = User::whereIn('role', ['professor', 'coordenador'])->get();

        // todas as HAEs com relatores
        $haes = Haes::with('relatores')
        ->orderBy('created_at', 'desc')
        ->get();

        return view('ver-relatores', compact('usuarios', 'haes'));
    }

    public function atribuirRelator(Request $request, $hae_id)
    {
        $hae = Haes::findOrFail($hae_id);

        $relatores = $request->relatores;

        $hae->relatores()->sync($relatores);

        return back()->with('sucesso', 'Relatores definidos!');
    }

    public function decisao(Request $request, $id)
    {
        $hae = Haes::findOrFail($id);
    
        switch ($request->acao) {
            case 'aprovada':
                $status = 'finalizada';
                break;
    
            case 'recusada':
                $status = 'recusada';
                break;
    
            case 'diligencia':
                $status = 'com_diligencia';
                break;
        }
    
        // 🔹 atualiza status
        $hae->status = $status;
        $hae->save();
    
        // 🔥 SALVA NA TABELA DECISOES
        Decisao::create([
            'hae_id' => $hae->id,
            'avaliador_id' => auth()->id(),
            'decisao' => $request->acao,
            'comentario' => $request->comentario
        ]);
    
        return back()->with('sucesso', 'Decisão aplicada!');
    }
}
