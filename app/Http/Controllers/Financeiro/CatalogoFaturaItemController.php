<?php
namespace App\Http\Controllers;

use App\Models\CatalogoFaturaItem;
use Illuminate\Http\Request;

class CatalogoFaturaItemController extends Controller
{
    public function index() {
        $itens = CatalogoFaturaItem::orderBy('descricao')->paginate(20);
        return view('catalogo_itens.index', compact('itens'));
    }
    public function create() { return view('catalogo_itens.create'); }
    public function store(Request $r) {
        $data = $r->validate([
            'descricao'=>['required','string','max:255'],
            'valor_unitario'=>['required','numeric','min:0'],
            'imposto_percentual'=>['nullable','numeric','min:0'],
        ]);
        CatalogoFaturaItem::create($data);
        return redirect()->route('catalogo-itens.index')->with('success','Item criado.');
    }
    public function edit(CatalogoFaturaItem $catalogo_iten) {
        return view('catalogo_itens.edit', ['item'=>$catalogo_iten]);
    }
    public function update(Request $r, CatalogoFaturaItem $catalogo_iten) {
        $data = $r->validate([
            'descricao'=>['required','string','max:255'],
            'valor_unitario'=>['required','numeric','min:0'],
            'imposto_percentual'=>['nullable','numeric','min:0'],
        ]);
        $catalogo_iten->update($data);
        return redirect()->route('catalogo-itens.index')->with('success','Item atualizado.');
    }
    public function destroy(CatalogoFaturaItem $catalogo_iten) {
        $catalogo_iten->delete();
        return back()->with('success','Item removido.');
    }
}
