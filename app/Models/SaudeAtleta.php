<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaudeAtleta extends Model
{
    protected $table = 'saude_atletas';

    protected $fillable = [
        'atleta_id',
        'patologias',
        'medicamentos',
    ];

    public function atleta()
    {
        return $this->belongsTo(Atleta::class);
    }
}
