<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DadosConfiguracao extends Model
{
    protected $table = 'dados_configuracao';

    protected $fillable = [
        'user_id',
        'consentimento',
        'data_consentimento',
        'ficheiro_consentimento',
        'declaracao_transporte',
        'data_transporte',
        'ficheiro_transporte',
        'afiliacao',
        'data_afiliacao',
        'ficheiro_afiliacao',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
