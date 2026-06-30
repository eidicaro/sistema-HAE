<?php

namespace App\Http\Controllers;

use App\Models\Hae;
use Illuminate\Http\Request;
use App\Models\Haes;
use App\Models\HaeGraduacao;
use App\Models\HaeAdministracao;
use App\Models\HaeEstudos;
use App\Models\HaeExtensao;
use App\Models\HaePlantao;
use App\Models\HaeAms;
use App\Models\User;
use App\Models\LimiteHae;
use App\Models\Semestres;
use App\Models\Relatorio;
use App\Models\TipoHae;


class HaeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // joga infos do banco para as views e retorna  elas
    public function index()
{
    $user = auth()->user();

    $semestreAtual = Semestres::where('ativo', true)->first();

    // 🔥 SEMPRE carregar isso (independente da role)
    $nomes = TipoHae::where('ativo', true)
        ->orderBy('nome')
        ->get();

    // fallback padrão
    $baseData = [
        'pendentes' => collect(),
        'diligencia' => collect(),
        'finalizadas' => collect(),
        'recusadas' => collect(),
        'haesRelator' => collect(),
        'emExecucao' => collect(),
        'semestreAtual' => $semestreAtual,
        'nomes' => $nomes,
    ];

    if (!$semestreAtual) {
        return match ($user->role) {
            'professor' => view('professor', $baseData)->with('erro', 'Nenhum semestre ativo.'),
            'coordenador' => view('coordenador', $baseData)->with('erro', 'Nenhum semestre ativo.'),
            default => view('direcao', array_merge($baseData, [
                'dadosLimites' => []
            ]))->with('erro', 'Nenhum semestre ativo.'),
        };
    }

    $query = Haes::with(['user', 'relatores'])
        ->where('semestre_id', $semestreAtual->id)
        ->latest();

    if ($user->role == 'professor') {
        $query->where('user_id', $user->id);
    }

    elseif ($user->role == 'coordenador') {
        $query->where('curso', $user->curso)
              ->whereIn('status', ['pendente', 'com_diligencia']);
    }

    $haes = $query->get();

    $pendentes = $haes->where('status', 'pendente');
    $diligencia = $haes->where('status', 'com_diligencia');
    $emExecucao = $haes->where('status', 'em_execucao');
    $finalizadas = $haes->where('status', 'finalizada');
    $recusadas = $haes->where('status', 'recusada');

    $haesRelator = Haes::whereHas('relatores', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->where('semestre_id', $semestreAtual->id)
        ->when($user->role == 'coordenador', function ($q) use ($user) {
            $q->where('curso', $user->curso);
        })
        ->orderBy('created_at', 'desc')
        ->get();

    // 👇 DIREÇÃO (com limites)
    if ($user->role == 'direcao') {

        $tipos = TipoHae::where('ativo', true)
            ->orderBy('nome')
            ->get();
    
        $dadosLimites = [];
    
        foreach ($tipos as $tipo) {
    
            $limite = LimiteHae::where('tipo', $tipo->id)->first();
    
            $usado = Haes::where('tipo', $tipo->id)
                ->whereIn('status', ['em_execucao', 'finalizada'])
                ->where('semestre_id', $semestreAtual->id)
                ->sum('carga_horaria');
    
            $dadosLimites[] = [
                'id' => $tipo->id,
                'tipo' => $tipo->nome,
                'limite' => $limite->carga_total ?? 0,
                'usado' => $usado,
                'restante' => ($limite->carga_total ?? 0) - $usado
            ];
        }
    
        return view('direcao', array_merge($baseData, [
            'pendentes' => $pendentes,
            'diligencia' => $diligencia,
            'finalizadas' => $finalizadas,
            'recusadas' => $recusadas,
            'haesRelator' => $haesRelator,
            'emExecucao' => $emExecucao,
            'dadosLimites' => $dadosLimites,
        ]));
    }

    // professor
    if ($user->role == 'professor') {
        return view('professor', array_merge($baseData, compact(
            'pendentes','diligencia','finalizadas','recusadas','haesRelator','emExecucao'
        )));
    }

    // coordenador
    if ($user->role == 'coordenador') {
        return view('coordenador', array_merge($baseData, compact(
            'pendentes','diligencia','finalizadas','recusadas','haesRelator','emExecucao'
        )));
    }

    return view('professor', array_merge($baseData, compact(
        'pendentes','diligencia','finalizadas','recusadas','haesRelator','emExecucao'
    )));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    
    /**
     * Store a newly created resource in storage.
     */

     //salva as haes novas
    public function store(Request $request)
    {
        // pega semestre ativo
        $semestre = Semestres::where('ativo', true)->first();

        if (!$semestre) {
            return back()->with('erro', 'Nenhum semestre ativo encontrado.');
        }
        // VALIDAÇÃO BÁSICA
        $request->validate([
            'tipo' => 'required',
            'titulo' => 'required',
            'curso' => 'required',
            'carga_horaria' => 'required|integer',
            'resumo' => 'required',
            'justificativa' => 'required',
        ]);

        // 1. CRIA HAE
        $hae = Haes::create([
            'user_id' => auth()->id(),
            'tipo' => $request->tipo,
            'edital_aceito' => (bool) $request->edital,
            'curso' => $request->curso,
            'titulo' => $request->titulo,
            'carga_horaria' => $request->carga_horaria,
            'resumo' => $request->resumo,
            'justificativa' => $request->justificativa,
            'cronograma' => $request->cronograma,
        
            'status' => 'pendente',
        
            // PRA NÃO QUEBRAR O BGL
            'semestre_id' => $semestre->id
        ]);

        return redirect('/professor')->with('success', 'HAE enviada com sucesso!');
    }
    

    /**
     * Display the specified resource.
     */
    // mostrar haes
    public function show($id)
    {
        
        $hae = Haes::with([
            'user',
            'pareceres.user',
            'relatores',
            'decisoes',
            'relatorio.resultados',
            'relatorio.arquivos'

        ])->findOrFail($id);

        $relatorio = Relatorio::with(['arquivos', 'resultados'])
        ->where('hae_id', $id)
        ->latest()
        ->first();
    
        return view('hae.show', compact('hae', 'relatorio'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        $hae = Haes::findOrFail($id);
        
        $tipo = $hae->tipo;
        
        return view('formulario', compact('hae', 'tipo'));
    }
    
    /**
     * Update the specified resource in storage.
     */

    // edita as haes com diligencia
    public function update(Request $request, $id)
    {
        $hae = Haes::findOrFail($id);
    
        // Só pode editar se foi marcado como DILIGENCIA
        if ($hae->status != Haes::STATUS_DILIGENCIA) {
            return back()->with('error', 'Só é possível editar HAE com diligencia.');
        }
    
        $hae->update([
            'titulo' => $request->titulo,
            'curso' => $request->curso,
            'carga_horaria' => $request->carga_horaria,
            'resumo' => $request->resumo,
            'justificativa' => $request->justificativa,
        
            'cronograma' => $request->cronograma,
        
            'edital_aceito' => $request->edital,
        
            'status' => Haes::STATUS_PENDENTE
        ]);
    
        return redirect('/professor')->with('success', 'HAE enviada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    
    public function destroy(Hae $hae)
    {
        //
    }
}
