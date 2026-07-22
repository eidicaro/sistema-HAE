<?php

namespace App\Http\Controllers;

use App\Models\Haes;
use App\Models\Semestres;
use App\Models\Relatorio;
use App\Models\TipoHae;
use Illuminate\Http\Request;

class HaeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $semestreAtual = Semestres::where('ativo', true)->first();
        $tipos = TipoHae::where('ativo', true)->orderBy('nome')->get();

        $baseData = [
            'pendentes'     => collect(),
            'diligencia'    => collect(),
            'finalizadas'   => collect(),
            'recusadas'     => collect(),
            'haesRelator'   => collect(),
            'emExecucao'    => collect(),
            'semestreAtual' => $semestreAtual,
            'nomes'         => $tipos,
        ];

        if (!$semestreAtual) {
            return match ($user->role) {
                'professor'   => view('professor', $baseData)->with('erro', 'Nenhum semestre ativo.'),
                'coordenador' => view('coordenador', $baseData)->with('erro', 'Nenhum semestre ativo.'),
                default       => view('direcao', array_merge($baseData, ['dadosLimites' => []]))
                    ->with('erro', 'Nenhum semestre ativo.'),
            };
        }

        $query = Haes::with(['user', 'relatores'])
            ->where('semestre_id', $semestreAtual->id)
            ->latest();

        if ($user->role == 'professor') {
            $query->where('user_id', $user->id);
        } elseif ($user->role == 'coordenador') {
            $query->where('curso', $user->curso)
                ->whereIn('status', ['pendente', 'com_diligencia']);
        }

        $haes = $query->get();

        $pendentes   = $haes->where('status', 'pendente');
        $diligencia  = $haes->where('status', 'com_diligencia');
        $emExecucao  = $haes->where('status', 'em_execucao');
        $finalizadas = $haes->where('status', 'finalizada');
        $recusadas   = $haes->where('status', 'recusada');

        $haesRelator = Haes::whereHas('relatores', fn($q) => $q->where('user_id', $user->id))
            ->where('semestre_id', $semestreAtual->id)
            ->when($user->role == 'coordenador', fn($q) => $q->where('curso', $user->curso))
            ->orderBy('created_at', 'desc')
            ->get();

        if ($user->role == 'direcao') {
            $dadosLimites = $tipos->map(function ($tipo) use ($semestreAtual) {
                $usado = Haes::where('tipo_hae_id', $tipo->id)
                    ->whereIn('status', ['em_execucao', 'finalizada'])
                    ->where('semestre_id', $semestreAtual->id)
                    ->sum('carga_horaria');

                return [
                    'id'       => $tipo->id,
                    'tipo'     => $tipo->nome,
                    'limite'   => $tipo->limite,
                    'usado'    => $usado,
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
        ]);
    }

    public function store(Request $request)
    {
        $semestre = Semestres::where('ativo', true)->first();

        if (!$semestre) {
            return back()->with('erro', 'Nenhum semestre ativo encontrado.');
        }

        $validated = $request->validate([
            'tipo_hae_id'    => 'required|exists:tipo_haes,id',
            'titulo'         => 'required|string',
            'curso'          => 'required|string',
            'carga_horaria'  => 'required|integer|min:1',
            'resumo'         => 'required|string',
            'justificativa'  => 'required|string',
            'cronograma'     => 'nullable|string',
            'especificacoes' => 'nullable|string',
        ]);

        $tipo = TipoHae::findOrFail($validated['tipo_hae_id']);

        $horasUsadas = Haes::where('tipo_hae_id', $tipo->id)
            ->where('semestre_id', $semestre->id)
            ->whereIn('status', ['pendente', 'com_diligencia', 'em_execucao', 'finalizada'])
            ->sum('carga_horaria');

        if (($horasUsadas + $validated['carga_horaria']) > $tipo->limite) {
            return back()
                ->withInput()
                ->withErrors([
                    'carga_horaria' => "A carga horaria excede o limite disponivel para {$tipo->nome}. Restam" . max(0, $tipo->limite - $horasUsadas) . "horas"
                ]);
        }

        Haes::create([
            'user_id'       => auth()->id(),
            'tipo_hae_id'   => $validated['tipo_hae_id'],
            'edital_aceito' => $request->boolean('edital'),
            'curso'         => $validated['curso'],
            'titulo'        => $validated['titulo'],
            'carga_horaria' => $validated['carga_horaria'],
            'resumo'        => $validated['resumo'],
            'justificativa' => $validated['justificativa'],
            'cronograma'    => $validated['cronograma'] ?? null,
            'especificacoes' => $validated['especificacoes'] ?? null,
            'status'        => 'pendente',
            'semestre_id'   => $semestre->id,
        ]);

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

        $podeVer = match ($user->role) {
            'professor'   => $hae->user_id == $user->id,
            'coordenador' => $hae->curso == $user->curso,
            'direcao'     => true,
            default       => false,
        };

        abort_unless($podeVer, 403);

        $relatorio = Relatorio::with(['arquivos', 'resultados'])
            ->where('hae_id', $id)
            ->latest()
            ->first();

        return view('hae.show', compact('hae', 'relatorio'));
    }

    public function edit($id)
    {
        $hae = Haes::findOrFail($id);

        abort_unless($hae->user_id == auth()->id(), 403);

        return view('formulario', [
            'hae'   => $hae,
            'nomes' => TipoHae::where('ativo', true)->orderBy('nome')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $hae = Haes::findOrFail($id);

        if ($hae->status != Haes::STATUS_DILIGENCIA) {
            return back()->with('error', 'Só é possível editar HAE com diligência.');
        }

        $validated = $request->validate([
            'tipo_hae_id'    => 'required|exists:tipo_haes,id',
            'titulo'         => 'required|string',
            'curso'          => 'required|string',
            'carga_horaria'  => 'required|integer|min:1',
            'resumo'         => 'required|string',
            'justificativa'  => 'required|string',
            'cronograma'     => 'nullable|string',
            'especificacoes' => 'nullable|string',
        ]);

        $tipo = TipoHae::findOrFail($validated['tipo_hae_id']);

        $horasUsadas = Haes::where('tipo_hae_id', $tipo->id)
            ->where('semestre_id', $hae->semestre_id)
            ->where('id', '!=', $hae->id)
            ->whereIn('status', ['pendente', 'com_diligencia', 'em_execucao', 'finalizada'])
            ->sum('carga_horaria');

        if (($horasUsadas + $validated['carga_horaria']) > $tipo->limite) {
            return back()
                ->withInput()
                ->withErrors([
                    'carga_horaria' => "A carga horária excede o limite disponível para {$tipo->nome}. Restam " . max(0, $tipo->limite - $horasUsadas) . " horas."
                ]);
        }

        $hae->update([
            ...$validated,
            'edital_aceito' => $request->boolean('edital'),
            'status'        => Haes::STATUS_PENDENTE,
        ]);

        return redirect('/professor')->with('success', 'HAE atualizada com sucesso!');
    }

    public function destroy($id)
    {
        //
    }

    public function exportarcsv()
    {
        $semestreAtual = Semestres::where('ativo', true)->first();

        if (!$semestreAtual) {
            return back()->with('erro', 'nenhum semestre ativo encontrado');
        }

        $haes = Haes::with([
            'user',
            'tipoHae',
            'semestre',
            'relatores',
        ])
            ->where('semestre_id', $semestreAtual->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $nomeArquivo = 'haes_' . str_replace(['', '/'], ['_', '-'], $semestreAtual->nome) . '.csv';

        $headers = [
            'content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$nomeArquivo\"",
        ];

        return response()->stream(function () use ($haes) {
            $arquivo = fopen('php://output', 'w');

            // Para o excel reconhecer utf-8

            fprintf($arquivo, chr(0xEF).chr(0xBB).chr(0xBF));
            // cabeçalho da planilha

            fputcsv($arquivo, [
                'ID',
                'Professor',
                'E-mail',
                'Relatores',
                'Curso',
                'Semestre',
                'Tipo HAE',
                'Edital Aceito',
                'Status',
            
                'Título',
                'Carga Horária',
            
                'Resumo',
                'Justificativa',
                'Especificações',
                'Cronograma',
            
                'Indicadores',
                'Horários HAE',
            
                'Mês 1',
                'Mês 2',
                'Mês 3',
                'Mês 4',
                'Mês 5',
            
                'Criado em',
                'Atualizado em',
            ], ';');

            foreach ($haes as $hae) {

                $relatores = $hae->relatores
                    ->pluck('name')
                    ->implode(' | ');
            
                $status = match ($hae->status) {
                    'pendente'       => 'Pendente',
                    'com_diligencia' => 'Com diligência',
                    'aprovada'       => 'Aprovada',
                    'em_execucao'    => 'Em execução',
                    'finalizada'     => 'Finalizada',
                    'recusada'       => 'Recusada',
                    default          => ucfirst($hae->status),
                };
            
                fputcsv($arquivo, [
                    $hae->id,
                    $hae->user->name ?? '',
                    $hae->user->email ?? '',
                    $relatores,
            
                    $hae->curso,
                    $hae->semestre->nome ?? '',
                    $hae->tipoHae->nome ?? '',
            
                    $hae->edital_aceito ? 'Sim' : 'Não',
                    $status,
            
                    $hae->titulo,
                    $hae->carga_horaria,
            
                    $hae->resumo,
                    $hae->justificativa,
                    $hae->especificacoes,
                    $hae->cronograma,
            
                    $hae->indicadores ?? '',
                    $hae->horarios_hae ?? '',
            
                    $hae->mes_1 ?? '',
                    $hae->mes_2 ?? '',
                    $hae->mes_3 ?? '',
                    $hae->mes_4 ?? '',
                    $hae->mes_5 ?? '',
            
                    optional($hae->created_at)->format('d/m/Y H:i'),
                    optional($hae->updated_at)->format('d/m/Y H:i'),
            
                ], ';');
            }

            fclose($arquivo);
        }, 200, $headers);
    }
}
