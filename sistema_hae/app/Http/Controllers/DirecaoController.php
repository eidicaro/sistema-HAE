<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Haes;
use App\Http\Controllers\HaeController;
use App\Models\Decisao;
use App\Models\LimiteHae;

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
    
                // 🔥 BUSCA LIMITE DO TIPO
                $limite = LimiteHae::where('tipo', $hae->tipo)->first();
    
                if (!$limite) {
                    return back()->with('error', 'Limite não definido para esse tipo de HAE.');
                }
    
                // 🔥 SOMA O QUE JÁ FOI APROVADO
                $totalUsado = Haes::where('tipo', $hae->tipo)
                    ->where('status', 'finalizada') // 👈 seu aprovado vira finalizada
                    ->sum('carga_horaria');
    
                // 🔥 VERIFICA SE VAI ESTOURAR
                if (($totalUsado + $hae->carga_horaria) > $limite->carga_total) {
                    return back()->with('error', 'Limite de carga horária excedido!');
                }
    
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
        $recusadas = \App\Models\Haes::where('status', 'recusada')->get();

        return view('resultados-dir', compact('finalizadas', 'recusadas'));
    }
}
