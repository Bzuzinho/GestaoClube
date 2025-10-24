<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DadosFinanceiros extends Model
{
    protected $table = 'dados_financeiros';

    protected $fillable = [
        'user_id',
        'mensalidade_id',
        'tipo_mensalidade',
        'estado_pagamento',
        'numero_recibo',
        'referencia_pagamento',
    ];

    public function user() {
    return $this->belongsTo(User::class);
    }

    public function itensFatura() {
        return $this->hasMany(FaturaItem::class); // precisa de coluna `dados_financeiros_id`
    }

    public function mensalidade()
    {
        return $this->belongsTo(\App\Models\Mensalidade::class, 'mensalidade_id');
    }
}

