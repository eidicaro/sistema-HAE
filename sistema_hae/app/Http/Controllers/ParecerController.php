<?php

namespace App\Http\Controllers;

use App\Models\Parecer;
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
    public function store(Request $request)
{
    Parecer::create([
        'hae_id' => $request->hae_id,
        'relator_id' => auth()->id(),
        'comentario' => $request->comentario,
    ]);

    return back();
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
