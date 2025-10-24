@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Visão geral do clube')

@section('page_actions')
  <a href="{{ route('eventos.create') }}" class="btn btn-primary btn-compact">+ Nova Atividade</a>
@endsection

@section('content')
  {{-- KPIs --}}
  <div class="grid grid--kpi">
    <x-ui.card class="card-kpi">
      <div class="u-muted kpi-label">Total de Atletas</div>
      <div class="kpi-value">{{ $totalAtletas ?? 0 }}</div>
      <div class="u-muted kpi-foot">↑ Atletas ativos</div>
    </x-ui.card>

    <x-ui.card class="card-kpi">
      <div class="u-muted kpi-label">Atividades do Mês</div>
      <div class="kpi-value">{{ $atividadesMes ?? 0 }}</div>
      <div class="u-muted kpi-foot">↑ Eventos este mês</div>
    </x-ui.card>

    <x-ui.card class="card-kpi">
      <div class="u-muted kpi-label">Receita Mensal</div>
      <div class="kpi-value">{{ $receitaMensal ?? '0,00 €' }}</div>
      <div class="u-muted kpi-foot">↑ Faturas pagas</div>
    </x-ui.card>

    <x-ui.card class="card-kpi">
      <div class="u-muted kpi-label">Taxa de Presença</div>
      <div class="kpi-value">{{ $taxaPresenca ?? '0%' }}</div>
      <div class="u-muted kpi-foot">↓ Média do mês</div>
    </x-ui.card>
  </div>

  {{-- 2 colunas --}}
  <div class="grid grid--2col">
    <x-ui.card>
      <x-slot name="title">Próximas Atividades</x-slot>
      @forelse(($proximas ?? []) as $a)
        <div class="row-activity">
          <div class="title">
            {{ $a->titulo }}
            @if($a->tipo_label) <span class="badge badge--neutral">{{ $a->tipo_label }}</span> @endif
          </div>
          <div class="meta">
            <span>📅 {{ \Carbon\Carbon::parse($a->data)->translatedFormat('d \\de F \\de Y') }}</span>
            <span>⏰ {{ $a->hora_inicio }} – {{ $a->hora_fim }}</span>
            <span>📍 {{ $a->local }}</span>
            @if($a->participantes) <span>👥 {{ $a->participantes }} participantes</span> @endif
          </div>
        </div>
      @empty
        <x-empty-state>Nenhuma atividade encontrada. Crie a primeira atividade!</x-empty-state>
      @endforelse
    </x-ui.card>

    <x-ui.card>
      <x-slot name="title">Atividade Recente</x-slot>
      <div class="list-recent">
        @forelse(($feed ?? []) as $item)
          <div class="recent-item">
            <div class="ri-title">{{ $item->titulo }}</div>
            <div class="ri-time u-muted">{{ $item->quando }}</div>
          </div>
        @empty
          <x-empty-state>Sem registos recentes.</x-empty-state>
        @endforelse
      </div>
    </x-ui.card>
  </div>
@endsection
