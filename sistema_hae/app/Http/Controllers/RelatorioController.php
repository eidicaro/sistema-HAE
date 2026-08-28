<?php

namespace App\Http\Controllers;

use App\Models\Haes;
use App\Models\Relatorio;
use App\Models\RelatorioArquivo;
use App\Models\RelatorioResultado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\ValidationException;

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
        $hae = Haes::findOrFail($id);
        $this->autorizarEnvio($hae);

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'sumario' => ['required', 'string', 'max:60000'],
            'resultados_texto' => ['required', 'string', 'max:60000'],
            'resultados' => ['nullable', 'array', 'max:50'],
            'resultados.*' => ['array:previsto,realizado'],
            'resultados.*.previsto' => ['nullable', 'integer', 'min:0', 'max:1000000000'],
            'resultados.*.realizado' => ['nullable', 'integer', 'min:0', 'max:1000000000'],
            'arquivo_principal' => ['nullable', $this->regraArquivo()],
            'comprovacoes' => ['nullable', 'array', 'max:10'],
            'comprovacoes.*' => [$this->regraArquivo()],
        ]);

        foreach (array_keys($validated['resultados'] ?? []) as $campo) {
            if (! is_string($campo) || mb_strlen($campo) > 255) {
                throw ValidationException::withMessages([
                    'resultados' => 'Um dos indicadores informados é inválido.',
                ]);
            }
        }

        [$haeId, $arquivosAntigos] = DB::transaction(function () use ($request, $validated, $id): array {
            $hae = Haes::lockForUpdate()->findOrFail($id);
            $this->autorizarEnvio($hae);

            $relatorioAtual = Relatorio::with('arquivos')
                ->where('hae_id', $hae->id)
                ->latest('id')
                ->first();

            if ($relatorioAtual && $relatorioAtual->status !== Relatorio::STATUS_RECUSADO) {
                throw ValidationException::withMessages([
                    'relatorio' => 'Já existe um relatório enviado para esta HAE.',
                ]);
            }

            $arquivosAntigos = $relatorioAtual?->arquivos->pluck('caminho')->all() ?? [];
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

            return [$hae->id, $arquivosAntigos];
        }, 3);

        Storage::disk('local')->delete($arquivosAntigos);

        Log::notice('Relatório de HAE enviado', [
            'hae_id' => $haeId,
            'professor_id' => auth()->id(),
        ]);

        return redirect()->route('hae.show', $haeId)->with('success', 'Relatório enviado!');
    }

    public function aprovar($id)
    {
        $relatorio = DB::transaction(function () use ($id): Relatorio {
            $relatorio = Relatorio::lockForUpdate()->findOrFail($id);
            abort_unless($relatorio->status === Relatorio::STATUS_ENVIADO, 422);
            $hae = Haes::lockForUpdate()->findOrFail($relatorio->hae_id);

            $relatorio->update(['status' => Relatorio::STATUS_APROVADO]);
            $hae->update(['status' => Haes::STATUS_FINALIZADA]);

            return $relatorio;
        }, 3);

        Log::notice('Relatório de HAE aprovado', [
            'relatorio_id' => $relatorio->id,
            'direcao_id' => auth()->id(),
        ]);

        return back()->with('sucesso', 'Relatório aprovado!');
    }

    public function reprovar($id)
    {
        $relatorio = DB::transaction(function () use ($id): Relatorio {
            $relatorio = Relatorio::lockForUpdate()->findOrFail($id);
            abort_unless($relatorio->status === Relatorio::STATUS_ENVIADO, 422);
            $hae = Haes::lockForUpdate()->findOrFail($relatorio->hae_id);

            $relatorio->update(['status' => Relatorio::STATUS_RECUSADO]);
            $hae->update(['status' => Haes::STATUS_EM_EXECUCAO]);

            return $relatorio;
        }, 3);

        Log::notice('Relatório de HAE reprovado', [
            'relatorio_id' => $relatorio->id,
            'direcao_id' => auth()->id(),
        ]);

        return back()->with('error', 'Relatório reprovado!');
    }

    public function download($id)
    {
        $arquivo = RelatorioArquivo::with('relatorio.hae.relatores')->findOrFail($id);
        abort_unless($arquivo->relatorio->hae->podeSerVistaPor(auth()->user()), 403);
        abort_unless(Storage::disk('local')->exists($arquivo->caminho), 404);

        Log::info('Anexo de relatório baixado', [
            'arquivo_id' => $arquivo->id,
            'user_id' => auth()->id(),
        ]);

        return Storage::disk('local')->download($arquivo->caminho);
    }

    public function ver($id)
    {
        $arquivo = RelatorioArquivo::with('relatorio.hae.relatores')->findOrFail($id);
        abort_unless($arquivo->relatorio->hae->podeSerVistaPor(auth()->user()), 403);
        abort_unless(Storage::disk('local')->exists($arquivo->caminho), 404);

        Log::info('Anexo de relatório consultado', [
            'arquivo_id' => $arquivo->id,
            'user_id' => auth()->id(),
        ]);

        $extensao = strtolower(pathinfo($arquivo->caminho, PATHINFO_EXTENSION));

        if (! in_array($extensao, ['pdf', 'jpg', 'jpeg', 'png'], true)) {
            return Storage::disk('local')->download($arquivo->caminho);
        }

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

    private function regraArquivo(): File
    {
        $tiposPermitidos = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'odt', 'xls', 'xlsx'];

        return File::types($tiposPermitidos)
            ->extensions($tiposPermitidos)
            ->max(10 * 1024);
    }
}
