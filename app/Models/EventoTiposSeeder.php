
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventoTiposSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('eventos_tipos')->insert([
            ['nome' => 'Prova', 'cor' => '#0d6efd', 'icon' => 'bi-flag'],
            ['nome' => 'Reunião', 'cor' => '#6f42c1', 'icon' => 'bi-people'],
            ['nome' => 'Encontro', 'cor' => '#198754', 'icon' => 'bi-calendar-heart'],
            ['nome' => 'Treino', 'cor' => '#ffc107', 'icon' => 'bi-activity'],
            ['nome' => 'Treino Especial', 'cor' => '#fd7e14', 'icon' => 'bi-stopwatch'],
            ['nome' => 'Evento Interno', 'cor' => '#20c997', 'icon' => 'bi-house'],
        ]);
    }
}
