<?php

namespace App\Http\Controllers;

use App\Models\Treino;
use Illuminate\Http\Request;

class TreinoController extends Controller
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
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'numero' => 'required|integer|min:1',
                'data' => 'required|date',
                'sessao' => 'required|string|max:255',
            ]);

            $treino = Treino::create($validated);

            return response()->json([
                'message' => 'Treino registado com sucesso!',
                'treino' => $treino,
            ]);
        }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, Treino $treino)
    {
        $validated = $request->validate([
            'numero' => 'required|integer|min:1',
            'data' => 'required|date',
            'sessao' => 'required|string|max:255',
        ]);

        $treino->update($validated);

        return response()->json([
            'message' => 'Treino atualizado com sucesso!',
            'treino' => $treino,
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Treino $treino)
    {
        $treino->delete();

        return response()->json([
            'message' => 'Treino apagado com sucesso!',
        ]);
    }
}
