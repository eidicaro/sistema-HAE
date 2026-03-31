<?php

namespace App\Http\Controllers;

use App\Models\Decisao;
use Illuminate\Http\Request;

class DecisaoController extends Controller
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
    public function store(Request $request)
    {
        Decisao::create([
            'hae_id' => $request->hae_id,
            'avaliador_id' => auth()->id(),
            'decisao' => $request->decisao,
            'comentario' => $request->comentario,
        ]);

        // muda status da hae
        $hae = Hae::find($request->hae_id);

        if ($request->decisao == 'aprovado') {
            $hae->status = 'finalizada';
        } elseif ($request->decisao == 'recusado') {
            $hae->status = 'recusada';
        } else {
            $hae->status = 'com_diligencia';
        }

        $hae->save();

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Decisao $decisao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Decisao $decisao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Decisao $decisao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Decisao $decisao)
    {
        //
    }
}
