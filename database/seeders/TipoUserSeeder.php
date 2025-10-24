<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoUserSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            ['nome' => 'Administrador'],
            ['nome' => 'Atleta'],
            ['nome' => 'Treinador'],
            ['nome' => 'Encarregado de Educação'],
            ['nome' => 'Patrocinador'],
            ['nome' => 'Sócio'],
        ];

        DB::table('tipo_users')->insert($tipos);
    }
}

