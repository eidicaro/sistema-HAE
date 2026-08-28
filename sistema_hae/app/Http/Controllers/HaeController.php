<?php

namespace App\Http\Controllers;

use App\Exports\HaesExport;
use App\Models\Haes;
use App\Models\Semestres;
use App\Models\TipoHae;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class HaeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $semestreAtual = Semestres::where('ativo', true)->first();
        $tipos = TipoHae::where('ativo', true)->orderBy('nome')->get();

        $baseData = [
            'pendentes' => collect(),
            'diligencia' => collect(),
            'finalizadas' => collect(),
            'recusadas' => collect(),
            'haesRelator' => collect(),
            'emExecucao' => collect(),
            'semestreAtual' => $semestreAtual,
            'nomes' => $tipos,
        ];

        if (! $semestreAtual) {
            return match ($user->role) {
                'professor' => view('professor', $baseData)->with('erro', 'Nenhum semestre ativo.'),
                'coordenador' => view('coordenador', $baseData)->with('erro', 'Nenhum semestre ativo.'),
                default => view('direcao', array_merge($baseData, ['dadosLimites' => []]))
                    ->with('erro', 'Nenhum semestre ativo.'),
            };
        }

        $query = Haes::with(['user', 'relatores'])
            ->where('semestre_id', $semestreAtual->id)
            ->latest();

        if ($user->role == 'professor') {
            $query->where('user_id', $user->id);
        } elseif ($user->role == 'coordenador') {
            $query->where('curso', $user->curso);
        }

        $haes = $query->get();

        $pendentes = $haes->where('status', 'pendente');
        $diligencia = $haes->where('status', 'com_diligencia');
        $emExecucao = $haes->where('status', 'em_execucao');
        $finalizadas = $haes->where('status', 'finalizada');
        $recusadas = $haes->where('status', 'recusada');

        $haesRelator = Haes::with('user')
            ->whereHas('relatores', fn ($q) => $q->where('user_id', $user->id))
            ->where('semestre_id', $semestreAtual->id)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($user->role == 'direcao') {
            $dadosLimites = $tipos->map(function ($tipo) use ($semestreAtual) {
                $usado = Haes::where('tipo_hae_id', $tipo->id)
                    ->whereIn('status', ['em_execucao', 'finalizada'])
                    ->where('semestre_id', $semestreAtual->id)
                    ->sum('carga_horaria');

                return [
                    'id' => $tipo->id,
                    'tipo' => $tipo->nome,
                    'limite' => $tipo->limite,
                    'usado' => $usado,
                    'restante' => $tipo->limite - $usado,
                ];
            })->values()->all();

            return view('direcao', array_merge($baseData, compact(
                'pendentes',
                'diligencia',
                'finalizadas',
                'recusadas',
                'haesRelator',
                'emExecucao',
                'dadosLimites'
            )));
        }

        $view = $user->role == 'coordenador' ? 'coordenador' : 'professor';

        return view($view, array_merge($baseData, compact(
            'pendentes',
            'diligencia',
            'finalizadas',
            'recusadas',
            'haesRelator',
            'emExecucao'
        )));
    }

    public function create()
    {
        return view('formulario', [
            'nomes' => TipoHae::where('ativo', true)
                ->orderBy('nome')
                ->get(),
            'cursos' => Haes::CURSOS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->regrasValidacao());

        DB::transaction(function () use ($request, $validated): void {
            $semestre = Semestres::where('ativo', true)->lockForUpdate()->first();

            if (! $semestre) {
                throw ValidationException::withMessages([
                    'semestre' => 'Nenhum semestre ativo encontrado.',
                ]);
            }

            $tipo = TipoHae::whereKey($validated['tipo_hae_id'])
                ->where('ativo', true)
                ->lockForUpdate()
                ->firstOrFail();

            $horasUsadas = Haes::where('tipo_hae_id', $tipo->id)
                ->where('semestre_id', $semestre->id)
                ->whereIn('status', Haes::STATUS_QUE_RESERVAM_CARGA)
                ->sum('carga_horaria');

            $this->validarLimite($tipo, $horasUsadas, $validated['carga_horaria']);

            Haes::create([
                'user_id' => auth()->id(),
                'tipo_hae_id' => $validated['tipo_hae_id'],
                'edital_aceito' => $request->boolean('edital'),
                'curso' => $validated['curso'],
                'titulo' => $validated['titulo'],
                'carga_horaria' => $validated['carga_horaria'],
                'resumo' => $validated['resumo'],
                'justificativa' => $validated['justificativa'],
                'cronograma' => $validated['cronograma'] ?? null,
                'especificacoes' => $validated['especificacoes'] ?? '',
                'status' => Haes::STATUS_PENDENTE,
                'semestre_id' => $semestre->id,
            ]);
        }, 3);

        return redirect('/professor')->with('success', 'HAE enviada com sucesso!');
    }

    public function show($id)
    {
        $hae = Haes::with([
            'user',
            'pareceres.user',
            'relatores',
            'decisoes',
            'relatorio.resultados',
            'relatorio.arquivos',
        ])->findOrFail($id);

        $user = auth()->user();

        abort_unless($hae->podeSerVistaPor($user), 403);

        $relatorio = $hae->relatorio;

        return view('hae.show', compact('hae', 'relatorio'));
    }

    public function edit($id)
    {
        $hae = Haes::findOrFail($id);

        abort_unless($hae->user_id === auth()->id(), 403);
        abort_unless($hae->status === Haes::STATUS_DILIGENCIA, 403);

        return view('formulario', [
            'hae' => $hae,
            'nomes' => TipoHae::where('ativo', true)->orderBy('nome')->get(),
            'cursos' => Haes::CURSOS,
        ]);
    }

    public function update(Request $request, $id)
    {
        $hae = Haes::findOrFail($id);

        abort_unless($hae->user_id === auth()->id(), 403);

        if ($hae->status != Haes::STATUS_DILIGENCIA) {
            return back()->with('error', 'Só é possível editar HAE com diligência.');
        }

        $validated = $request->validate($this->regrasValidacao());

        DB::transaction(function () use ($id, $request, $validated): void {
            $hae = Haes::lockForUpdate()->findOrFail($id);

            abort_unless($hae->user_id === auth()->id(), 403);

            if ($hae->status !== Haes::STATUS_DILIGENCIA) {
                throw ValidationException::withMessages([
                    'status' => 'Só é possível editar HAE com diligência.',
                ]);
            }

            $tipo = TipoHae::whereKey($validated['tipo_hae_id'])
                ->where('ativo', true)
                ->lockForUpdate()
                ->firstOrFail();

            $horasUsadas = Haes::where('tipo_hae_id', $tipo->id)
                ->where('semestre_id', $hae->semestre_id)
                ->where('id', '!=', $hae->id)
                ->whereIn('status', Haes::STATUS_QUE_RESERVAM_CARGA)
                ->sum('carga_horaria');

            $this->validarLimite($tipo, $horasUsadas, $validated['carga_horaria']);

            $hae->update([
                ...$validated,
                'especificacoes' => $validated['especificacoes'] ?? '',
                'edital_aceito' => $request->boolean('edital'),
                'status' => Haes::STATUS_PENDENTE,
            ]);
        }, 3);

        return redirect('/professor')->with('success', 'HAE atualizada com sucesso!');
    }

    public function exportarcsv()
    {
        $semestreAtual = Semestres::where('ativo', true)->first();

        if (! $semestreAtual) {
            return back()->with('erro', 'Nenhum semestre ativo encontrado');
        }

        $nomeArquivo = 'relatorio_HAEs_'.$semestreAtual->nome.'.xlsx';

        return Excel::download(
            new HaesExport($semestreAtual->id),
            $nomeArquivo
        );
    }

    private function regrasValidacao(): array
    {
        return [
            'tipo_hae_id' => [
                'required',
                Rule::exists('tipo_haes', 'id')->where('ativo', true),
            ],
            'edital' => ['required', 'accepted'],
            'titulo' => ['required', 'string', 'max:255'],
            'curso' => ['required', 'string', Rule::in(Haes::CURSOS)],
            'carga_horaria' => ['required', 'integer', 'min:1'],
            'resumo' => ['required', 'string', 'max:60000'],
            'justificativa' => ['required', 'string', 'max:60000'],
            'cronograma' => ['nullable', 'string', 'max:60000'],
            'especificacoes' => ['nullable', 'string', 'max:60000'],
        ];
    }

    private function validarLimite(TipoHae $tipo, int $horasUsadas, int $horasSolicitadas): void
    {
        if (($horasUsadas + $horasSolicitadas) <= $tipo->limite) {
            return;
        }

        throw ValidationException::withMessages([
            'carga_horaria' => "A carga horária excede o limite disponível para {$tipo->nome}. Restam "
                .max(0, $tipo->limite - $horasUsadas).' horas.',
        ]);
    }
}
