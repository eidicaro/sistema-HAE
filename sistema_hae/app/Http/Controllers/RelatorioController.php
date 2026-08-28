<?php

namespace App\Http\Controllers;

use App\Models\Haes;
use App\Models\Relatorio;
use App\Models\RelatorioArquivo;
use App\Models\RelatorioResultado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RelatorioController extends Controller
{
    public function create($id)
    {
        $hae = Haes::with(['tipoHae', 'relatorio'])->findOrFail($id);
        $this->autorizarEnvio($hae);

        if ($hae->relatorio && $hae->relatorio->status !== Relatorio::STATUS_RECUSADO) {
            return redirect()->route('hae.show', $hae->id)
                ->with('error', 'Já existe um relatório enviado para esta HAE.');
        }

        return view('relatorio.create', compact('hae'));
    }

    public function store(Request $request, $id)
    {
        $hae = Haes::with('relatorio.arquivos')->findOrFail($id);
        $this->autorizarEnvio($hae);

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'sumario' => ['required', 'string'],
            'resultados_texto' => ['required', 'string'],
            'resultados' => ['nullable', 'array'],
            'resultados.*.previsto' => ['nullable', 'integer', 'min:0'],
            'resultados.*.realizado' => ['nullable', 'integer', 'min:0'],
            'arquivo_principal' => ['nullable', 'file', 'max:10240'],
            'comprovacoes' => ['nullable', 'array', 'max:10'],
            'comprovacoes.*' => ['file', 'max:10240'],
        ]);

        $relatorioAtual = $hae->relatorio;

        if ($relatorioAtual && $relatorioAtual->status !== Relatorio::STATUS_RECUSADO) {
            return back()->with('error', 'Já existe um relatório enviado para esta HAE.');
        }

        $arquivosAntigos = $relatorioAtual?->arquivos->pluck('caminho')->all() ?? [];

        DB::transaction(function () use ($request, $validated, $hae, $relatorioAtual): void {
            $relatorio = $relatorioAtual ?? new Relatorio(['hae_id' => $hae->id]);
            $relatorio->fill([
                'titulo' => $validated['titulo'],
                'sumario' => $validated['sumario'],
                'resultados_texto' => $validated['resultados_texto'],
                'status' => Relatorio::STATUS_ENVIADO,
            ]);
            $relatorio->save();

            $relatorio->resultados()->delete();
            $relatorio->arquivos()->delete();

            foreach ($validated['resultados'] ?? [] as $campo => $valores) {
                RelatorioResultado::create([
                    'relatorio_id' => $relatorio->id,
                    'campo' => $campo,
                    'previsto' => $valores['previsto'] ?? 0,
                    'realizado' => $valores['realizado'] ?? 0,
                ]);
            }

            if ($request->hasFile('arquivo_principal')) {
                $this->salvarArquivo($relatorio, $request->file('arquivo_principal'), 'principal');
            }

            foreach ($request->file('comprovacoes', []) as $arquivo) {
                $this->salvarArquivo($relatorio, $arquivo, 'comprovacao');
            }
        });

        Storage::disk('local')->delete($arquivosAntigos);

        return redirect()->route('hae.show', $hae->id)->with('success', 'Relatório enviado!');
    }

    public function aprovar($id)
    {
        $relatorio = Relatorio::with('hae')->findOrFail($id);
        abort_unless($relatorio->status === Relatorio::STATUS_ENVIADO, 422);

        DB::transaction(function () use ($relatorio): void {
            $relatorio->update(['status' => Relatorio::STATUS_APROVADO]);
            $relatorio->hae->update(['status' => Haes::STATUS_FINALIZADA]);
        });

        return back()->with('sucesso', 'Relatório aprovado!');
    }

    public function reprovar($id)
    {
        $relatorio = Relatorio::with('hae')->findOrFail($id);
        abort_unless($relatorio->status === Relatorio::STATUS_ENVIADO, 422);

        DB::transaction(function () use ($relatorio): void {
            $relatorio->update(['status' => Relatorio::STATUS_RECUSADO]);
            $relatorio->hae->update(['status' => Haes::STATUS_EM_EXECUCAO]);
        });

        return back()->with('error', 'Relatório reprovado!');
    }

    public function download($id)
    {
        $arquivo = RelatorioArquivo::with('relatorio.hae.relatores')->findOrFail($id);
        abort_unless($arquivo->relatorio->hae->podeSerVistaPor(auth()->user()), 403);
        abort_unless(Storage::disk('local')->exists($arquivo->caminho), 404);

        return Storage::disk('local')->download($arquivo->caminho);
    }

    public function ver($id)
    {
        $arquivo = RelatorioArquivo::with('relatorio.hae.relatores')->findOrFail($id);
        abort_unless($arquivo->relatorio->hae->podeSerVistaPor(auth()->user()), 403);
        abort_unless(Storage::disk('local')->exists($arquivo->caminho), 404);

        return response()->file(Storage::disk('local')->path($arquivo->caminho));
    }

    private function autorizarEnvio(Haes $hae): void
    {
        abort_unless($hae->user_id === auth()->id(), 403);
        abort_unless($hae->status === Haes::STATUS_EM_EXECUCAO, 422);
    }

    private function salvarArquivo(Relatorio $relatorio, $arquivo, string $tipo): void
    {
        RelatorioArquivo::create([
            'relatorio_id' => $relatorio->id,
            'caminho' => $arquivo->store('relatorios'),
            'tipo' => $tipo,
        ]);
    }
}
