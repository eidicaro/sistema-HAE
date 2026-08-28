<?php

namespace App\Http\Controllers;

use App\Models\Haes;
use App\Models\TipoHae;
use Illuminate\Http\Request;

class TipoHaeController extends Controller
{
    public function index()
    {
        $tipos = TipoHae::withCount('subtipos')->orderBy('nome')->get();

        return view('direcao.tipoHae.index', compact('tipos'));
    }

    public function create()
    {
        return view('direcao.tipoHae.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:tipo_haes,nome',
            'descricao' => 'nullable|string',
            'limite' => 'required|integer|min:0',
        ]);

        $validated['ativo'] = $request->boolean('ativo');

        TipoHae::create($validated);

        return redirect()
            ->route('direcao.tipos-hae.index')
            ->with('success', 'Tipo de HAE criado com sucesso!');
    }

    public function edit(TipoHae $tipoHae)
    {
        $tipoHae->load(['subtipos' => fn ($query) => $query->orderBy('nome')]);

        return view('direcao.tipoHae.edit', compact('tipoHae'));
    }

    public function update(Request $request, TipoHae $tipoHae)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:tipo_haes,nome,'.$tipoHae->id,
            'descricao' => 'nullable|string',
            'limite' => 'required|integer|min:0',
        ]);

        $validated['ativo'] = $request->boolean('ativo');

        $tipoHae->update($validated);

        return redirect()
            ->route('direcao.tipos-hae.index')
            ->with('success', 'Tipo de HAE atualizado com sucesso!');
    }

    public function toggle(TipoHae $tipoHae)
    {
        $tipoHae->update(['ativo' => ! $tipoHae->ativo]);

        return back()->with('success', 'Status atualizado!');
    }

    public function destroy(TipoHae $tipoHae)
    {
        $emUso = Haes::where('tipo_hae_id', $tipoHae->id)->exists();

        if ($emUso) {
            return back()->with('error', 'Este tipo possui HAEs vinculadas e não pode ser excluído. Desative-o em vez disso.');
        }

        $tipoHae->delete();

        return redirect()
            ->route('direcao.tipos-hae.index')
            ->with('success', 'Tipo de HAE excluído.');
    }
}
