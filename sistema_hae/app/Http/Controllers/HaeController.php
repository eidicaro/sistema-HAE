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


class HaeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
    
        $query = Haes::with(['user', 'relatores'])->latest();
    
        if ($user->role == 'professor') {
            $query->where('user_id', $user->id);
        }
    
        elseif ($user->role == 'coordenador') {
            $query->whereIn('status', ['pendente', 'com_diligencia']);
        }
    
        elseif ($user->role == 'direcao') {
            // vê tudo
        }
    
        else {
            // fallback (caso queira usar depois)
            $query->whereHas('relatores', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
    
        $haes = $query->get();
    
        // 📊 separação por status (como você já fazia)
        $pendentes = $haes->where('status', 'pendente');
        $diligencia = $haes->where('status', 'com_diligencia');
        $finalizadas = $haes->where('status', 'finalizada');
        $recusadas = $haes->where('status', 'recusada');
    
        // 🔥 NOVO: HAEs onde o usuário é relator
        $haesRelator = Haes::whereHas('relatores', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get();
    
        // 🔥 retorno das views
        if ($user->role == 'professor') {
            return view('professor', compact(
                'pendentes','diligencia','finalizadas','recusadas','haesRelator'
            ));
        }
    
        if ($user->role == 'coordenador') {
            return view('coordenador', compact(
                'pendentes','diligencia','finalizadas','recusadas','haesRelator'
            ));
        }
    
        if ($user->role == 'direcao') {
            return view('direcao', compact(
                'pendentes','diligencia','finalizadas','recusadas','haesRelator'
            ));
        }
    
        return view('professor', compact(
            'pendentes','diligencia','finalizadas','recusadas','haesRelator'
        ));
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
    public function store(Request $request)
    {
        // 🧾 VALIDAÇÃO BÁSICA
        $request->validate([
            'tipo' => 'required',
            'titulo' => 'required',
            'curso' => 'required',
            'carga_horaria' => 'required|integer',
            'resumo' => 'required',
            'justificativa' => 'required',
        ]);

        // 📄 1. CRIA HAE
        $hae = Haes::create([
            'user_id' => auth()->id(),
            'tipo' => $request->tipo,
            'edital_aceito' => $request->edital == "Li e estou de acordo com o Edital",
            'curso' => $request->curso,
            'titulo' => $request->titulo,
            'carga_horaria' => $request->carga_horaria,
            'resumo' => $request->resumo,
            'justificativa' => $request->justificativa,

            'fevereiro' => $request->fevereiro,
            'marco' => $request->marco,
            'abril' => $request->abril,
            'maio' => $request->maio,
            'junho' => $request->junho,

            'status' => 'pendente'
        ]);

        // 🧩 2. SALVA ESPECÍFICO
        switch ($request->tipo) {

            case 'graduacao':
                HaeGraduacao::create([
                    'hae_id' => $hae->id,
                    'tipo_graduacao' => $request->tipo_graduacao,
                    'orientacoes' => $request->orientacoes,
                    'bancas_orientador' => $request->bancas_orientador,
                    'bancas_membro' => $request->bancas_membro,
                    'indicador' => $request->indicador,
                ]);
            break;

            case 'administracao':
                HaeAdministracao::create([
                    'hae_id' => $hae->id,
                    'tipo_admin' => $request->tipo_admin,
                    'encontros' => $request->encontros,
                    'relatorios' => $request->relatorios,
                    'acoes_interdisciplinares' => $request->acoes_interdisciplinares,
                    'formacoes' => $request->formacoes,
                    'materiais' => $request->materiais,
                    'indicador' => $request->indicador,
                    'outra_acao' => $request->outra_acao,
                ]);
            break;

            case 'estudos':
                HaeEstudos::create([
                    'hae_id' => $hae->id,
                    'tipo_estudo' => $request->tipo_estudo,
                    'alunos' => $request->alunos,
                    'propostas' => $request->propostas,
                    'prototipos' => $request->prototipos,
                    'artigos' => $request->artigos,
                    'resumos' => $request->resumos,
                    'indicador' => $request->indicador,
                    'outra_acao' => $request->outra_acao,
                ]);
            break;

            case 'extensao':
                HaeExtensao::create([
                    'hae_id' => $hae->id,
                    'tipo_extensao' => $request->tipo_extensao,
                    'pessoas' => $request->pessoas,
                    'instituicoes' => $request->instituicoes,
                    'eventos' => $request->eventos,
                    'beneficiarios' => $request->beneficiarios,
                    'indicador' => $request->indicador,
                    'outra_acao' => $request->outra_acao,
                ]);
            break;

            case 'plantao':
                try {
                    HaePlantao::create([
                        'hae_id' => $hae->id,
                        'tipo_plantao' => $request->tipo_plantao,
                        'alunos_atendidos' => $request->alunos_atendidos,
                        'simulados' => $request->simulados,
                        'relatorios' => $request->relatorios,
                        'acoes' => $request->acoes,
                        'indicador' => $request->indicador,
                        'outra_acao' => $request->outra_acao,
                    ]);
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            break;

            case 'ams':
                HaeAms::create([
                    'hae_id' => $hae->id,
                    'tipo_ams' => $request->tipo_ams,
                    'escolas' => $request->escolas,
                    'eventos' => $request->eventos,
                    'encontros_alunos' => $request->encontros_alunos,
                    'indicador' => $request->indicador,
                    'outra_acao' => $request->outra_acao,
                ]);
            break;
        }

        return redirect('/professor')->with('success', 'HAE enviada com sucesso!');
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $hae = Haes::with([
            'user',
            'graduacao',
            'administracao',
            'estudos',
            'extensao',
            'plantao',
            'ams',
            'pareceres.user',
            'relatores',
            'decisoes'
        ])->findOrFail($id);
    
        return view('hae.show', compact('hae'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hae $hae)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hae $hae)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hae $hae)
    {
        //
    }
}
