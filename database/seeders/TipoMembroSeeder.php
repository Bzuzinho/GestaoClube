<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoUser;

class TipoMembroSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            'Administrador',
            'Treinador',
            'Atleta',
            'Encarregado de Educação',
            'Patrocinador',
            'Sócio'
        ];

        foreach ($tipos as $tipo) {
            TipoMembro::firstOrCreate(['nome' => $tipo]);
        }
    }
}
