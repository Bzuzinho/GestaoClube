<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventoTipo extends Model
{
    protected $table = 'eventos_tipos'; // nome correto da tabela
    protected $fillable = ['nome', 'cor', 'icon'];
     public $timestamps = false;

    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class);
    }
}
