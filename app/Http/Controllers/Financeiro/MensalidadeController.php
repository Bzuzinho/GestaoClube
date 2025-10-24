<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensalidade;

class MensalidadeController extends Controller
{
    public function index()
    {
        $mensalidades = Mensalidade::all();
        return view('faturas.mensalidades', compact('mensalidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mensalidades' => 'required|array',
            'mensalidades.*.designacao' => 'required|string',
            'mensalidades.*.valor' => 'required|numeric|min:0'
        ]);

        foreach ($request->mensalidades as $mensalidade) {
            Mensalidade::updateOrCreate(
                ['designacao' => $mensalidade['designacao']],
                ['valor' => $mensalidade['valor']]
            );
        }

        return redirect()->route('faturas.mensalidades')->with('success', 'Mensalidades gravadas com sucesso.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'designacao' => 'required|string',
            'valor' => 'required|numeric|min:0'
        ]);

        $mensalidade = Mensalidade::findOrFail($id);
        $mensalidade->update([
            'designacao' => $request->designacao,
            'valor' => $request->valor
        ]);

        return response()->json(['success' => true, 'mensalidade' => $mensalidade]);
    }

    public function destroy($id)
    {
        $mensalidade = Mensalidade::findOrFail($id);
        $mensalidade->delete();

        return response()->json(['success' => true]);
    }
}
