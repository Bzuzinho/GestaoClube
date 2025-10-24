<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Escalao;

class EscalaoSeeder extends Seeder
{
    public function run(): void
    {
        $escaloes = [
            'Pré-Competição',
            'Cadetes A',
            'Cadetes B',
            'Infantis',
            'Juvenis',
            'Juniores',
            'Seniores',
            'Masters',
        ];

        foreach ($escaloes as $nome) {
            Escalao::firstOrCreate(['nome' => $nome]);
        }
    }
}
