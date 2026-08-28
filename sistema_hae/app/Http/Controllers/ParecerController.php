<?php

namespace App\Http\Controllers;

use App\Models\Haes;
use App\Models\Parecer;
use App\Models\User;
use Illuminate\Http\Request;

class ParecerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    // salva o novo parecer
    public function store(Request $request, $hae_id)
    {
        $hae = Haes::findOrFail($hae_id);
        $validated = $request->validate([
            'comentario' => ['required', 'string', 'max:10000'],
        ]);

        $usuario = auth()->user();

        $ehRelator = $hae->relatores->contains($usuario->id);
        $ehCoordenador = $usuario->role === User::ROLE_COORDENADOR
            && $hae->curso === $usuario->curso;

        // valida se é relator ou coordenador
        if (! $ehRelator && ! $ehCoordenador) {
            abort(403);
        }

        // impede duplicidade
        $jaExiste = Parecer::where('hae_id', $hae->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($jaExiste) {
            return back()->with('erro', 'Você já enviou um parecer.');
        }

        $tipo = $usuario->role === User::ROLE_COORDENADOR
            ? 'coordenador'
            : 'relator';

        Parecer::create([
            'hae_id' => $hae->id,
            'user_id' => auth()->id(),
            'tipo' => $tipo,
            'comentario' => $validated['comentario'],
        ]);

        return back()->with('sucesso', 'Parecer enviado!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Parecer $parecer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Parecer $parecer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Parecer $parecer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parecer $parecer)
    {
        //
    }
}
