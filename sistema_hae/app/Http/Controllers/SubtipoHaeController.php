<?php

namespace App\Http\Controllers;

use App\Models\SubtipoHae;
use App\Models\TipoHae;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubtipoHaeController extends Controller
{
    public function store(Request $request, TipoHae $tipoHae)
    {
        $validated = $request->validate($this->regras($tipoHae));
        $validated['ativo'] = $request->boolean('ativo');

        $tipoHae->subtipos()->create($validated);

        return back()->with('success', 'Subtipo criado com sucesso!');
    }

    public function update(Request $request, TipoHae $tipoHae, SubtipoHae $subtipoHae)
    {
        $this->garantirVinculo($tipoHae, $subtipoHae);

        $validated = $request->validate($this->regras($tipoHae, $subtipoHae));
        $validated['ativo'] = $request->boolean('ativo');
        $subtipoHae->update($validated);

        return back()->with('success', 'Subtipo atualizado com sucesso!');
    }

    public function toggle(TipoHae $tipoHae, SubtipoHae $subtipoHae)
    {
        $this->garantirVinculo($tipoHae, $subtipoHae);
        $subtipoHae->update(['ativo' => ! $subtipoHae->ativo]);

        return back()->with('success', 'Status do subtipo atualizado!');
    }

    public function destroy(TipoHae $tipoHae, SubtipoHae $subtipoHae)
    {
        $this->garantirVinculo($tipoHae, $subtipoHae);

        if ($subtipoHae->haes()->exists()) {
            return back()->with('error', 'Este subtipo possui HAEs vinculadas e não pode ser excluído. Desative-o em vez disso.');
        }

        $subtipoHae->delete();

        return back()->with('success', 'Subtipo excluído.');
    }

    private function regras(TipoHae $tipoHae, ?SubtipoHae $subtipoHae = null): array
    {
        return [
            'nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subtipo_haes', 'nome')
                    ->where('tipo_hae_id', $tipoHae->id)
                    ->ignore($subtipoHae?->id),
            ],
            'descricao' => ['nullable', 'string', 'max:10000'],
        ];
    }

    private function garantirVinculo(TipoHae $tipoHae, SubtipoHae $subtipoHae): void
    {
        abort_unless($subtipoHae->tipo_hae_id === $tipoHae->id, 404);
    }
}
