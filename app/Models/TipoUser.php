<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoUser extends Model
{
    protected $table = 'tipo_users';

    public function utilizadores()
    {
        return $this->belongsToMany(User::class, 'tipo_user_user', 'tipo_user_id', 'user_id');
    }
}
