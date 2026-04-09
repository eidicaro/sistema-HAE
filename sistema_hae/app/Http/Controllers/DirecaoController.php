<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Haes;
use App\Http\Controllers\HaeController;
use App\Http\Controllers\SemestresController;
use App\Models\Decisao;
use App\Models\LimiteHae;
use App\Models\Semestres;


class DirecaoController extends Controller
{
    public function relatores()
    {
        // professores e coordenadores
        $usuarios = User::whereIn('role', ['professor', 'coordenador'])->get();
        $semestreAtivo = Semestres::where('ativo', 1)->first();

        // todas as HAEs com relatores
        $haes = Haes::with('relatores')
        ->where('semestre_id', $semestreAtivo->id)
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

                $limite = LimiteHae::where('tipo', $hae->tipo)->first();
            
                if (!$limite) {
                    return back()->with('error', 'Limite não definido para esse tipo de HAE.');
                }
            
                // 🔥 agora soma EM_EXECUCAO + FINALIZADA
                $totalUsado = Haes::where('tipo', $hae->tipo)
                    ->whereIn('status', ['em_execucao', 'finalizada'])
                    ->sum('carga_horaria');
            
                if (($totalUsado + $hae->carga_horaria) > $limite->carga_total) {
                    return back()->with('error', 'Limite de carga horária excedido!');
                }
            
                // 🔥 AQUI É A MUDANÇA PRINCIPAL
                $status = 'em_execucao';
            
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
    
        // 🔥 salva decisão
        Decisao::create([
            'hae_id' => $hae->id,
            'avaliador_id' => auth()->id(),
            'decisao' => $request->acao,
            'comentario' => $request->comentario
        ]);
    
        return back()->with('sucesso', 'Decisão aplicada!');
    }

    public function resultados()
    {
        $finalizadas = \App\Models\Haes::where('status', 'finalizada')->get();
        $emExecucao = Haes::where('status', 'em_execucao')->get();
        $recusadas = \App\Models\Haes::where('status', 'recusada')->get();

        return view('resultados-dir', compact('finalizadas', 'recusadas'));
    }
}
