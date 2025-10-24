{{-- Layout base BSCN --}}
<!doctype html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Benedita Sport Club')</title>

  {{-- Bootstrap + icons (ajusta se já carregas noutro sítio) --}}
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bscn.css') }}" rel="stylesheet">
  @stack('styles')
</head>
<body>
<div id="bscn" class="bscn-wrapper">
  @include('layouts.partials.sidebar')

  <main class="bscn-content">
    {{-- Topbar: botão colapsar e título da página --}}
    <header class="bscn-topbar">
      <div class="d-flex align-items-center gap-2">
        <button id="bscnToggleSidebar" type="button" class="btn btn-light btn-sm bscn-btn-compact"
                aria-label="Alternar menu">
          <i class="bi bi-layout-sidebar-inset"></i>
        </button>
        <h1 class="bscn-page-title">@yield('page_title', 'Dashboard')</h1>
      </div>
      {{-- espaço para ações globais (opcional) --}}
      <div class="bscn-topbar-actions">
        @yield('topbar_actions')
      </div>
    </header>

    {{-- Área de páginas --}}
    <div class="bscn-page">
      @yield('content') {{-- cada página coloca aqui o seu conteúdo --}} 
    </div>
  </main>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/bscn.js') }}"></script>
@stack('scripts')
</body>
</html>
