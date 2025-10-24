<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Convocatoria extends Model
{
    protected $fillable = ['titulo','data','ficheiro_path'];

    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }
}
