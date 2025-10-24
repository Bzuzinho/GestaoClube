<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaturaItem extends Model
{
    protected $table = 'fatura_itens';

    protected $fillable = [
        'fatura_id',
        'descricao',
        'valor_unitario',
        'quantidade',
        'imposto_percentual',
        'total_linha',
        'dados_financeiros_id',
    ];

    public function fatura()
    {
        return $this->belongsTo(Fatura::class, 'fatura_id');
    }

    public function dadosFinanceiros() 
    {
        return $this->belongsTo(DadosFinanceiros::class);
    }

}
