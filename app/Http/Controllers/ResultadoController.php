<?php

namespace App\Http\Controllers;

use App\Models\Resultado;
use Illuminate\Http\Request;


class ResultadoController extends Controller
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
        \Log::info('Dados recebidos em /resultados', $request->all());

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'prova' => 'required|string|max:255',
            'tempo' => 'required|string|max:20',
            'data' => 'required|date',
            'epoca' => 'required|string|max:20',
            'competicao' => 'nullable|string|max:255',
            'local' => 'nullable|string|max:255',
            'piscina' => 'nullable|string|max:10',
            'escalao' => 'nullable|string|max:50',
        ]);

        $resultado = Resultado::create($validated);

        return response()->json(['resultado' => $resultado]);
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
    public function update(Request $request, Resultado $resultado)
    {
        $validated = $request->validate([
            'prova' => 'required|string|max:255',
            'tempo' => 'required|string|max:20',
            'data' => 'required|date',
            'epoca' => 'required|string|max:20',
            'competicao' => 'nullable|string|max:255',
            'local' => 'nullable|string|max:255',
            'piscina' => 'nullable|string|max:10',
            'escalao' => 'nullable|string|max:50',
        ]);

        $resultado->update($validated);

        return response()->json(['resultado' => $resultado]);
    }

    public function destroy(Resultado $resultado)
    {
        $resultado->delete();
        return response()->json(['message' => 'Resultado apagado com sucesso']);
    }
        
}
