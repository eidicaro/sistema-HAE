<?php

namespace App\Http\Controllers;

use App\Models\Decisao;
use App\Models\Haes;
use App\Models\Semestres;
use App\Models\TipoHae;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DirecaoController extends Controller
{
    public function relatores()
    {
        // professores e coordenadores
        $usuarios = User::whereIn('role', ['professor', 'coordenador'])->get();
        $semestreAtivo = Semestres::where('ativo', 1)->first();

        if (! $semestreAtivo) {
            return redirect()->route('direcao')->with('erro', 'Nenhum semestre ativo encontrado.');
        }

        // todas as HAEs com relatores
        $haes = Haes::with('relatores')
            ->where('semestre_id', $semestreAtivo->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ver-relatores', compact('usuarios', 'haes'));
    }

    // atribuir o relator
    public function atribuirRelator(Request $request, $hae_id)
    {
        $hae = Haes::findOrFail($hae_id);

        $validated = $request->validate([
            'relatores' => ['nullable', 'array'],
            'relatores.*' => [
                'integer',
                Rule::exists('users', 'id')->whereIn('role', ['professor', 'coordenador']),
            ],
        ]);

        $hae->relatores()->sync($validated['relatores'] ?? []);

        return back()->with('sucesso', 'Relatores definidos!');
    }

    // altera o status da hae conforme a decisão
    public function decisao(Request $request, $id)
    {
        $hae = Haes::findOrFail($id);

        $validated = $request->validate([
            'acao' => ['required', Rule::in(['aprovada', 'recusada', 'diligencia'])],
            'comentario' => ['nullable', 'string'],
        ]);

        if (! in_array($hae->status, [Haes::STATUS_PENDENTE, Haes::STATUS_DILIGENCIA], true)) {
            return back()->with('error', 'Esta HAE não está aguardando decisão.');
        }

        $tipo = $hae->tipoHae; // ou TipoHae::findOrFail($hae->tipo_hae_id)

        if (! $tipo) {
            return back()->with('error', 'Tipo de HAE inválido.');
        }

        switch ($validated['acao']) {

            case 'aprovada':

                $totalUsado = Haes::where('tipo_hae_id', $tipo->id)
                    ->where('semestre_id', $hae->semestre_id)
                    ->whereIn('status', [Haes::STATUS_EM_EXECUCAO, Haes::STATUS_FINALIZADA])
                    ->sum('carga_horaria');

                if (($totalUsado + $hae->carga_horaria) > $tipo->limite) {
                    return back()->with('error', 'Limite de carga horária excedido!');
                }

                $status = Haes::STATUS_EM_EXECUCAO;

                break;

            case 'recusada':
                $status = Haes::STATUS_RECUSADA;
                break;

            case 'diligencia':
                $status = Haes::STATUS_DILIGENCIA;
                break;
        }

        DB::transaction(function () use ($hae, $status, $validated): void {
            $hae->update(['status' => $status]);

            Decisao::create([
                'hae_id' => $hae->id,
                'avaliador_id' => auth()->id(),
                'decisao' => $validated['acao'],
                'comentario' => $validated['comentario'] ?? null,
            ]);
        });

        return back()->with('sucesso', 'Decisão aplicada!');
    }

    // so exibe
    public function resultados()
    {
        $semestreAtivo = Semestres::where('ativo', true)->first();

        $haes = $semestreAtivo
            ? Haes::where('semestre_id', $semestreAtivo->id)->get()
            : collect();

        $finalizadas = $haes->where('status', Haes::STATUS_FINALIZADA);
        $recusadas = $haes->where('status', Haes::STATUS_RECUSADA);

        return view('resultados-dir', compact('finalizadas', 'recusadas'));
    }
}
