<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Fatura;
use Carbon\Carbon;

class GerarFaturasMensais extends Command
{
    protected $signature = 'gerar:faturas {--mes= : Mês inicial (formato YYYY-MM)}';

    protected $description = 'Gerar faturas mensais para todos os utilizadores com base na mensalidade e escalão';

    public function handle()
    {
        $mesInicial = $this->option('mes') ? Carbon::createFromFormat('Y-m', $this->option('mes'))->startOfMonth() : Carbon::now()->startOfMonth();
        $mesFinal = Carbon::create(null, 7, 1); // Julho do ano atual

        $utilizadores = User::with('dadosFinanceiros', 'tipoUsers')
            ->whereHas('dadosFinanceiros')
            ->get();

        foreach ($utilizadores as $user) {
            $valor = $user->dadosFinanceiros->mensalidade ?? null;
            if (!$valor) continue;

            // Verifica se tem agregado de pelo menos 3 atletas com contacto comum
            $familiares = User::where('contacto', $user->contacto)
                ->whereHas('tipoUsers', fn($q) => $q->where('nome', 'Atleta'))
                ->count();

            $desconto = $familiares >= 3 ? 0.9 : 1;

            $mesAtual = clone $mesInicial;

            while ($mesAtual <= $mesFinal) {
                $mesFormatado = $mesAtual->format('Y-m');

                // Evita duplicação
                if (Fatura::where('user_id', $user->id)->where('mes', $mesFormatado)->exists()) {
                    $mesAtual->addMonth();
                    continue;
                }

                Fatura::create([
                    'user_id' => $user->id,
                    'mes' => $mesFormatado,
                    'valor' => round($valor * $desconto, 2),
                    'estado_pagamento' => 0
                ]);

                $this->info("Fatura criada para {$user->name} - {$mesFormatado}");
                $mesAtual->addMonth();
            }
        }

        $this->info('Geração de faturas concluída.');
        return 0;
    }
}
