{{-- Separador (mantém a mesma linha do topo) --}}
<hr class="bscn-sidebar__divider">

<div class="bscn-sidebar__account">
  <div class="account-info">
    <div class="name">{{ auth()->user()->name ?? 'Utilizador' }}</div>
    <div class="u-muted email">{{ auth()->user()->email ?? '' }}</div>
  </div>

  {{-- Logout por POST, acionado pelo ícone --}}
  <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
    @csrf
  </form>
  <button type="submit" form="logout-form" class="account-logout" title="Sair" aria-label="Sair">
    {{-- ícone sair --}}
    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
      <path d="M16 17l5-5-5-5"/>
      <path d="M21 12H9"/>
    </svg>
  </button>
</div>
