<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrador'); // Apenas quem tem o role 'administrador' pode aceder
    }

    public function index()
    {
        // Total de utilizadores ativos (estado = 1)
        $totalUtilizadores = User::where('estado', 1)->count();

        // Total de atletas ativos com tipo de membro "Atleta"
        $totalAtletas = User::where('estado', 1)
            ->whereHas('tipoMembros', function ($q) {
                $q->where('nome', 'Atleta');
            })->count();

        // Mensalidades pendentes (simulado ou ajustar com dados reais)
        try {
            $mensalidadesPendentes = DB::table('faturas')
                ->where('estado_pagamento', 0)
                ->sum('valor');
            } catch (\Exception $e) {
                $mensalidadesPendentes = 0; // fallback seguro
                // Opcional: logar erro
                \Log::error('Erro ao calcular mensalidades pendentes: ' . $e->getMessage());
        }

        // Distribuição por escalões
        $escaloes = DB::table('user_escaloes')
            ->join('escaloes', 'user_escaloes.escalao_id', '=', 'escaloes.id')
            ->select('escaloes.nome as escalao', DB::raw('count(*) as total'))
            ->groupBy('escaloes.nome')
            ->orderBy('total', 'desc')
            ->get();

        // Aniversários do mês
        $aniversariosDia = User::whereMonth('data_nascimento', Carbon::now()->month)
            ->orderByRaw('DAY(data_nascimento)')
            ->get();

        // Atividades simuladas (substituir por dados reais se necessário)
        $atividades = collect([
            (object)['nome' => 'Prova Interna', 'data' => Carbon::now()->addDays(7)],
            (object)['nome' => 'Torneio Regional', 'data' => Carbon::now()->addDays(30)],
        ]);

        $totalAtividades = $atividades->count();

        // Encomendas simuladas
        $totalEncomendas = 2;

        // Roles disponíveis (para dropdown ou gestão futura)
        $rolesDisponiveis = Role::pluck('name');

        return view('dashboard.index', compact(
            'totalUtilizadores',
            'totalAtletas',
            'mensalidadesPendentes',
            'escaloes',
            'aniversariosDia',
            'atividades',
            'totalAtividades',
            'totalEncomendas',
            'rolesDisponiveis'
        ));
    }
}
