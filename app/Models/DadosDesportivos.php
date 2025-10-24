<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DadosDesportivos extends Model
{
    protected $table = 'dados_desportivos';
    protected $fillable = [
        'user_id',
        'altura',
        'peso',
        'batimento',
        'observacoes',
        'patologias',
        'medicamentos',
        'numero_federacao',
        'pmb',
        'data_inscricao',
        'atestado_medico',
        'data_atestado',
        'informacoes_medicas',
        'arquivo_am_path',
    ];
}