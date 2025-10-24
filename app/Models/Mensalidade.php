<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensalidade extends Model
{
    use HasFactory;

    protected $fillable = [
        'designacao',
        'valor',
    ];

    public function dadosFinanceiros()
    {
        return $this->hasMany(DadosFinanceiros::class);
    }

    public function index()
    {
        $faturas = Fatura::with('user')
            ->orderBy('id', 'desc')
            ->paginate(20);

        $utilizadores = User::with(['tipoUsers', 'escaloes'])
            ->where('estado', 1)
            ->get();

        // Itens “catálogo” a partir de fatura_itens já usados
        $itensFatura = DB::table('fatura_itens')
            ->select(
                'descricao',
                DB::raw('MIN(valor_unitario) AS valor_unitario'),
                DB::raw('MIN(imposto_percentual) AS imposto_percentual')
            )
            ->groupBy('descricao')
            ->orderBy('descricao')
            ->get();

        // Mensalidades (designação + valor)
        $mensalidades = DB::table('mensalidades')
            ->select('id','designacao as descricao','valor as valor_unitario')
            ->orderBy('designacao')
            ->get();

        return view('faturas.index', compact('faturas','utilizadores','itensFatura','mensalidades'));
    }
}

