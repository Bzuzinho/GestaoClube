<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presenca extends Model
{
    protected $fillable = ['user_id', 'data', 'numero_treino', 'presenca'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
