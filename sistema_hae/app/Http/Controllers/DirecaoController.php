<?php

namespace App\Http\Controllers;

use App\Models\Decisao;
use App\Models\Haes;
use App\Models\Semestres;
use App\Models\TipoHae;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
        $haes = Haes::with(['relatores', 'user'])
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
                'distinct',
                'integer',
                Rule::exists('users', 'id')->whereIn('role', ['professor', 'coordenador']),
            ],
        ]);

        $hae->relatores()->sync($validated['relatores'] ?? []);

        Log::notice('Relatores de HAE atualizados', [
            'hae_id' => $hae->id,
            'direcao_id' => auth()->id(),
            'relatores' => $validated['relatores'] ?? [],
        ]);

        return back()->with('sucesso', 'Relatores definidos!');
    }

    // altera o status da hae conforme a decisão
    public function decisao(Request $request, $id)
    {
        $validated = $request->validate([
            'acao' => ['required', Rule::in(['aprovada', 'recusada', 'diligencia'])],
            'comentario' => ['nullable', 'string', 'max:10000'],
        ]);

        $hae = DB::transaction(function () use ($id, $validated): Haes {
            $hae = Haes::lockForUpdate()->findOrFail($id);

            if (! in_array($hae->status, [Haes::STATUS_PENDENTE, Haes::STATUS_DILIGENCIA], true)) {
                throw ValidationException::withMessages([
                    'acao' => 'Esta HAE não está aguardando decisão.',
                ]);
            }

            $status = match ($validated['acao']) {
                'aprovada' => Haes::STATUS_EM_EXECUCAO,
                'recusada' => Haes::STATUS_RECUSADA,
                'diligencia' => Haes::STATUS_DILIGENCIA,
            };

            if ($validated['acao'] === 'aprovada') {
                $tipo = TipoHae::whereKey($hae->tipo_hae_id)->lockForUpdate()->firstOrFail();
                $totalUsado = Haes::where('tipo_hae_id', $tipo->id)
                    ->where('semestre_id', $hae->semestre_id)
                    ->whereIn('status', [Haes::STATUS_EM_EXECUCAO, Haes::STATUS_FINALIZADA])
                    ->sum('carga_horaria');

                if (($totalUsado + $hae->carga_horaria) > $tipo->limite) {
                    throw ValidationException::withMessages([
                        'acao' => 'Limite de carga horária excedido.',
                    ]);
                }
            }

            $hae->update(['status' => $status]);

            Decisao::create([
                'hae_id' => $hae->id,
                'avaliador_id' => auth()->id(),
                'decisao' => $validated['acao'],
                'comentario' => $validated['comentario'] ?? null,
            ]);

            return $hae;
        }, 3);

        Log::notice('Decisão de HAE registrada', [
            'hae_id' => $hae->id,
            'direcao_id' => auth()->id(),
            'acao' => $validated['acao'],
        ]);

        return back()->with('sucesso', 'Decisão aplicada!');
    }

    // so exibe
    public function resultados()
    {
        $semestreAtivo = Semestres::where('ativo', true)->first();

        $haes = $semestreAtivo
            ? Haes::with('user')->where('semestre_id', $semestreAtivo->id)->get()
            : collect();

        $finalizadas = $haes->where('status', Haes::STATUS_FINALIZADA);
        $recusadas = $haes->where('status', Haes::STATUS_RECUSADA);

        return view('resultados-dir', compact('finalizadas', 'recusadas'));
    }
}
