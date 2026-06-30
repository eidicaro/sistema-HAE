<?php

namespace App\Http\Controllers;

use App\Models\TipoHae;
use Illuminate\Http\Request;

class TipoHaeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nomes = \App\Models\TipoHae::where('ativo', true)->orderBy('nome')->get();

            return view('direcao.limites', [
                'nomes' => $nomes,
            ]);

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
            \App\Models\TipoHae::updateOrCreate(
                ['nome' => $request->nome],
                ['limite' => $request->limite]
            );
    
            return back()->with('success', 'Limite salvo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoHae $tipoHae)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoHae $tipoHae)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoHae $tipoHae)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoHae $tipoHae)
    {
        //
    }
}
