<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title','Benedita Sport Club')</title>
  @vite(['resources/css/app.scss','resources/js/app.js'])
</head>
<body class="bscn">
  <div class="shell" id="app-shell">
    <!-- SIDEBAR -->
    <aside class="sidebar" id="app-sidebar">
      <div class="brand">
        <img class="brand__logo" src="{{ config('app.theme_logo', asset('images/logo-clube.png')) }}" alt="BSCN">
        <div class="brand__text">
          <strong>Benedita Sport Club</strong>
          <small>Sistema de Gestão</small>
        </div>
      </div>

      @includeIf('partials.sidebar')

      <div class="sidebar__account">
        <div class="acc__user">
            <div class="acc__avatar" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="18" height="18">
                <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2" fill="none"/>
                <path d="M4 20c0-4 4-6 8-6s8 2 8 6" stroke="currentColor" stroke-width="2" fill="none"/>
            </svg>
            </div>
            <div class="acc__name">
            <div class="name">{{ auth()->user()->name ?? 'Utilizador' }}</div>
            <div class="mail">{{ auth()->user()->email ?? '' }}</div>
            </div>
        </div>

        <div class="acc__actions">
            <a class="btn btn-ghost btn-compact btn--icon-left" href="{{ route('logout') }}"
            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                <path d="M16 17l5-5-5-5M21 12H9" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="btn__text">Sair</span>
            </a>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </aside>

    <!-- CONTENT -->
    <div class="content">
      <header class="header">
        <button id="sidebar-toggle" class="icon-btn icon-btn--sm" aria-label="Recolher menu" aria-pressed="false">
            <!-- ícone seta esquerda (menu aberto) -->
            <svg class="chev-left" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
                <path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <!-- ícone seta direita (menu colapsado) -->
            <svg class="chev-right" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
                <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            </button>

        <div class="header__title">
          <h1 class="page-title">@yield('page_title','Dashboard')</h1>
          @hasSection('page_subtitle')
            <p class="page-subtitle">@yield('page_subtitle')</p>
          @endif
        </div>

        <div class="header__actions">
          @yield('page_actions')
        </div>
      </header>

      <main class="main">
        @includeIf('shared.alerts')
        @yield('content')
      </main>
    </div>
  </div>

  @yield('modals')
</body>
</html>
