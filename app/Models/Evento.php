<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Evento extends Model
{
    protected $table = 'eventos';

    protected $fillable = [
        'titulo',
        'descricao',
        'data_inicio',
        'data_fim',
        'local',
        'tipo_evento_id',
        'visibilidade',
        'local_partida',
        'hora_partida',
        'convocatoria_path',
        'regulamento_path',
        'observacoes',
        'convocatoria_id',
        'tem_transporte',
        'transporte_descricao',
        'observacoes',
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim'    => 'datetime',
        'tem_transporte' => 'bool',
        //'hora_partida' => 'datetime:H:i',
    ];

    public function convocatoria()
    {
        return $this->belongsTo(Convocatoria::class);
    }
    /* -------------------------
     | Scopes de filtro
     * ------------------------*/
    public function scopeTitulo(Builder $q, ?string $titulo): Builder
    {
        return $titulo ? $q->where('titulo', 'like', "%{$titulo}%") : $q;
    }

    public function scopeTipo(Builder $q, $tipoId): Builder
    {
        return $tipoId ? $q->where('tipo_evento_id', $tipoId) : $q;
    }

    public function scopeLocal(Builder $q, ?string $local): Builder
    {
        return $local ? $q->where('local', 'like', "%{$local}%") : $q;
    }

    public function scopeDataDe(Builder $q, ?string $de): Builder
    {
        return $de ? $q->whereDate('data_inicio', '>=', $de) : $q;
    }

    public function scopeDataAte(Builder $q, ?string $ate): Builder
    {
        // Se quiseres incluir 23:59:59, altera para where('data_fim', '<=', "$ate 23:59:59")
        return $ate ? $q->whereDate('data_fim', '<=', $ate) : $q;
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(EventoTipo::class, 'tipo_evento_id');
    }

    public function participacoes(): HasMany
    {
        return $this->hasMany(EventoUser::class);
    }

    public function escaloes()
    {
        return $this->belongsToMany(Escalao::class, 'evento_escalao');
    }

}
