@extends('layouts.app')

@section('title','Ficha de Utilizador')
@section('page_title','Editar Utilizador')
@section('page_subtitle', $utilizador->name)

@section('content')
  <x-ui.card>

    {{-- Alertas de validação --}}
    @if ($errors->any())
      <div class="alert alert-danger" style="margin-bottom:12px">
        <strong>Corrija os seguintes erros:</strong>
        <ul style="margin:6px 0 0 18px">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('utilizadores.update',$utilizador) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      {{-- Reutiliza o partial com dados já preparados no controller --}}
      @include('utilizadores._form', [
        'utilizador'       => $utilizador,
        'readonly'         => false,
        'escaloes'         => $escaloes,   {{-- vindo do controller --}}
        'tipos'            => $tipos,      {{-- vindo do controller --}}
        'perfis'           => $perfis,     {{-- vindo do controller --}}
        'podeDefinirRole'  => $podeDefinirRole ?? false,
      ])

      <div class="mt-3" style="display:flex;gap:8px;justify-content:flex-end">
        <a href="{{ route('utilizadores.show',$utilizador) }}" class="btn btn-compact">Cancelar</a>
        <button type="submit" class="btn btn-primary btn-compact">Guardar</button>
      </div>
    </form>

  </x-ui.card>
@endsection

@push('scripts')
<script>
  (function() {
    const tabs = document.querySelectorAll('[data-tab-target]');
    const panes = document.querySelectorAll('[data-tab-pane]');
    if (!tabs.length) return;

    function activate(id) {
      tabs.forEach(t => t.classList.toggle('is-active', t.dataset.tabTarget === id));
      panes.forEach(p => p.hidden = (p.dataset.tabPane !== id));
    }
    tabs.forEach(t => t.addEventListener('click', (e) => {
      e.preventDefault();
      activate(t.dataset.tabTarget);
      history.replaceState({}, '', '#'+t.dataset.tabTarget); // mantém âncora
    }));
    // ativa pela âncora ou a primeira
    const first = tabs[0].dataset.tabTarget;
    const hash = location.hash.replace('#','');
    activate(tabs.length && panes.length ? (hash || first) : null);
  })();
</script>
@endpush
