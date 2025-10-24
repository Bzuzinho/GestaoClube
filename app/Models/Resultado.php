<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Resultado extends Model
{
   protected $fillable = [
        'user_id', 'prova', 'tempo', 'data', 'epoca', 'competicao', 'local', 'piscina', 'escalao'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
