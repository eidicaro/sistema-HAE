<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Relatorio;
use App\Models\RelatorioResultado;
use App\Models\RelatorioArquivo;
use App\Models\Haes;

class RelatorioController extends Controller
{
    public function create($id)
    {
        $hae = Haes::with(['ams','extensao','plantao','administracao','estudos','graduacao'])
            ->findOrFail($id);

        return view('relatorio.create', compact('hae'));
    }

    public function store(Request $request, $id)
    {
        $relatorio = Relatorio::create([
            'hae_id' => $id,
            'titulo' => $request->titulo,
            'sumario' => $request->sumario,
            'resultados_texto' => $request->resultados_texto,
            'status' => 'enviado',
        ]);

        // salvar comparações
        if ($request->has('resultados')) {

            foreach ($request->resultados as $campo => $valores) {
        
                RelatorioResultado::create([
                    'relatorio_id' => $relatorio->id,
                    'campo' => $campo,
                    'previsto' => $valores['previsto'] ?? 0,
                    'realizado' => $valores['realizado'] ?? 0,
                ]);
            }
        }

        // arquivo principal
        if ($request->hasFile('arquivo_principal')) {
            $path = $request->file('arquivo_principal')->store('relatorios');

            RelatorioArquivo::create([
                'relatorio_id' => $relatorio->id,
                'caminho' => $path,
                'tipo' => 'principal'
            ]);
        }

        // comprovacoes
        if ($request->hasFile('comprovacoes')) {
            foreach ($request->file('comprovacoes') as $file) {
                $path = $file->store('relatorios');

                RelatorioArquivo::create([
                    'relatorio_id' => $relatorio->id,
                    'caminho' => $path,
                    'tipo' => 'comprovacao'
                ]);
            }
        }

        return redirect("/hae/$id")->with('success', 'Relatório enviado!');
    }



    public function aprovar($id)
    {
        $relatorio = Relatorio::findOrFail($id);

        $relatorio->status = 'aprovado';
        $relatorio->save();

        // 🔥 finaliza a HAE
        $relatorio->hae->status = 'finalizada';
        $relatorio->hae->save();

        return back()->with('sucesso', 'Relatório aprovado!');
    }

    public function reprovar($id)
    {
        $relatorio = Relatorio::findOrFail($id);

        $relatorio->status = 'reprovado';
        $relatorio->save();

        // 🔥 volta pra execução
        $relatorio->hae->status = 'em_execucao';
        $relatorio->hae->save();

        return back()->with('error', 'Relatório reprovado!');
    }
}
