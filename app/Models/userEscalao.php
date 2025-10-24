<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEscalao extends Model
{
    protected $table = 'user_escaloes';

    protected $fillable = [
        'user_id',
        'escalao_id',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function escalao()
    {
        return $this->belongsTo(Escalao::class);
    }
}
