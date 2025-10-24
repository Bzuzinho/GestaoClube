<?php

namespace App\Models;

use App\Models\Resultado;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'numero_socio',
        'estado',
        'name',
        'email',
        'password',
        'nif',
        'cartao_cidadao',
        'contacto',
        'data_nascimento',
        'sexo',
        'morada',
        'codigo_postal',
        'localidade',
        'empresa',
        'escola',
        'estado_civil',
        'ocupacao',
        'nacionalidade',
        'numero_irmaos',
        'menor',
        'estado_utilizador',
        'role',
        'profile_photo_path',
        'mensalidade_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'data_nascimento' => 'date',
        'menor' => 'boolean',
        'estado' => 'integer',
        'estado_utilizador' => 'integer',
    ];

    // Se definires 'estado_utilizador', espelha em 'estado'
    public function setEstadoUtilizadorAttribute($value)
    {
        $v = is_numeric($value) ? (int)$value : null;
        $this->attributes['estado_utilizador'] = $v;
        $this->attributes['estado'] = $v;
    }

    // Se definires 'estado', espelha em 'estado_utilizador'
    public function setEstadoAttribute($value)
    {
        $v = is_numeric($value) ? (int)$value : null;
        $this->attributes['estado'] = $v;
        $this->attributes['estado_utilizador'] = $v;
    }

    // Texto amigável para a lista
    public function getEstadoTextoAttribute(): string
    {
        return match ((int)($this->estado ?? $this->estado_utilizador)) {
            1 => 'Ativo',
            2 => 'Inativo',
            0 => 'Suspenso',
            default => '-',
        };
    }

    // Relações
    public function tipoMembros()
    {
        return $this->belongsToMany(TipoUser::class, 'tipo_user_user', 'user_id', 'tipo_user_id');
    }

    public function faturas()
    {
        return $this->hasMany(Fatura::class);
    }
    public function dadosDesportivos()
    {
        return $this->hasOne(DadosDesportivos::class);
    }

    public function dadosFinanceiros()
    {
        return $this->hasOne(\App\Models\DadosFinanceiros::class, 'user_id');
    }

    public function dadosConfiguracao()
    {
        return $this->hasOne(DadosConfiguracao::class);
    }

    public function tipoUsers()
    {
        return $this->belongsToMany(TipoUser::class, 'tipo_user_user', 'user_id', 'tipo_user_id');
    }

    public function tipoUser()
    {
        return $this->hasOne(TipoUserUser::class);
    }

    public function escaloes()
    {
        return $this->belongsToMany(Escalao::class, 'user_escaloes');
    }

    public function encarregados()
    {
        return $this->belongsToMany(User::class, 'encarregado_user', 'user_id', 'encarregado_id');
    }
    
    public function educandos()
    {
        return $this->belongsToMany(User::class, 'encarregado_user', 'encarregado_id', 'user_id');
    }

    public function resultados()
    {
        return $this->hasMany(Resultado::class);
    }

    public function presencas()
    {
         return $this->hasMany(Presenca::class);
    }

    public function treinos()
    {
        return $this->hasMany(Treino::class);
    }
    
    public function mensalidade()
    {
        return $this->belongsTo(\App\Models\Mensalidade::class, 'mensalidade_id');
    }

    }
