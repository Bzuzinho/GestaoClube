@php
  $is = fn(string $p) => request()->is($p) ? 'is-active' : '';
@endphp

<nav class="menu">
  <a href="{{ route('dashboard') }}" class="menu__item {{ $is('/') }}">
    <span class="icon">📊</span><span class="label">Dashboard</span>
  </a>
  <a href="{{ route('utilizadores.index') }}" class="menu__item {{ $is('utilizadores*') }}">
    <span class="icon">👥</span><span class="label">Gestão de Pessoas</span>
  </a>
  <a href="{{ route('eventos.index') }}" class="menu__item {{ $is('eventos*') }}">
    <span class="icon">📅</span><span class="label">Atividades</span>
  </a>
  <a href="{{ route('faturas.index') }}" class="menu__item {{ $is('faturas*') }}">
    <span class="icon">💳</span><span class="label">Financeiro</span>
  </a>
  <a href="{{ route('inventario.index') }}" class="menu__item {{ $is('inventario*') }}">
    <span class="icon">📦</span><span class="label">Inventário</span>
  </a>
  <a href="{{ route('comunicacao.index') }}" class="menu__item {{ $is('comunicacao*') }}">
    <span class="icon">✉️</span><span class="label">Comunicação</span>
  </a>
  <a href="{{ route('relatorios.index') }}" class="menu__item {{ $is('relatorios*') }}">
    <span class="icon">📈</span><span class="label">Relatórios</span>
  </a>
  <a href="{{ route('config.index') }}" class="menu__item {{ $is('configuracoes*') }}">
    <span class="icon">⚙️</span><span class="label">Configurações</span>
  </a>
</nav>

