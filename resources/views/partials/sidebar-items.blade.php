<nav class="bscn-sidebar__menu">
  <a class="bscn-nav-item {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" href="{{ route('dashboard') }}">
    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12l9-9 9 9M4 10v10h16V10"/></svg>
    <span>Dashboard</span>
  </a>

  <a class="bscn-nav-item {{ request()->routeIs('utilizadores.*') ? 'is-active' : '' }}" href="{{ route('utilizadores.index') }}">
    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
    </svg>
    <span>Gestão de Pessoas</span>
  </a>

  <a class="bscn-nav-item {{ request()->routeIs('atividades.*') ? 'is-active' : '' }}" href="{{ route('eventos.index') }}">
    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 4h18M3 12h18M3 20h18"/></svg>
    <span>Atividades</span>
  </a>

  <a class="bscn-nav-item {{ request()->routeIs('financeiro.*') ? 'is-active' : '' }}" href="{{ route('faturas.index') }}">
    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18v12H3z"/></svg>
    <span>Financeiro</span>
  </a>

  <a class="bscn-nav-item {{ request()->routeIs('inventario.*') ? 'is-active' : '' }}" href="{{ route('inventario.index') }}">
    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M3 9h18M3 9l2-5h14l2 5M3 9v10h18V9"/>
    </svg>
    <span>Inventário</span>
  </a>

  <a class="bscn-nav-item {{ request()->routeIs('comunicacao.*') ? 'is-active' : '' }}" href="{{ route('campanhas.index') }}">
    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
    </svg>
    <span>Comunicação</span>
  </a>

  <a class="bscn-nav-item {{ request()->routeIs('relatorios.*') ? 'is-active' : '' }}" href="{{ route('relatorios.index') }}">
    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M3 3h18v18H3z"/><path d="M3 9h18M9 21V9"/>
    </svg>
    <span>Relatórios</span>
  </a>

  <a class="bscn-nav-item {{ request()->routeIs('configuracoes.*') ? 'is-active' : '' }}" href="{{ route('configuracoes.index') }}">
    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
    </svg>
    <span>Configurações</span>
  </a>
</nav>
