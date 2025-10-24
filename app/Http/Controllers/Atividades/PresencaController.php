<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presenca;


class PresencaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'data' => 'required|date',
            'numero_treino' => 'required|integer|min:1',
            'presenca' => 'required|boolean',
        ]);

        $presenca = Presenca::create($request->all());

        return response()->json([
            'message' => 'Presença registada com sucesso!',
            'presenca' => $presenca,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'data' => 'required|date',
            'numero_treino' => 'required|integer|min:1',
            'presenca' => 'required|boolean',
        ]);

        $presenca = Presenca::findOrFail($id);
        $presenca->update($request->only('data', 'numero_treino', 'presenca'));

        return response()->json([
            'message' => 'Presença atualizada com sucesso!',
            'presenca' => $presenca,
        ]);

    }
    public function destroy($id)
    {
        $presenca = Presenca::findOrFail($id);
        $presenca->delete();

        return response()->json([
            'message' => 'Presença removida com sucesso!',
        ]);
    }
}
