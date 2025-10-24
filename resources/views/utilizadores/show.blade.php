{{-- resources/views/utilizadores/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Perfil do Atleta')
@section('page_title', 'Perfil do Atleta')
@section('page_subtitle', '')

@section('content')
@php
  $backUrl = route('utilizadores.index');    // último nível: Gestão de Pessoas
  $ativo   = (int)($utilizador->estado ?? 1) === 1;
@endphp

<style>
  /* ---------- CABEÇALHO ---------- */
  .pf-head{display:flex;align-items:flex-start;gap:16px;margin:2px 0 10px}
  .pf-title{font-size:22px;font-weight:800;line-height:1.1;margin:0}
  .pf-sub{display:flex;align-items:center;gap:10px;margin-top:4px}
  .badge{display:inline-flex;align-items:center;gap:6px;padding:2px 10px;border-radius:999px;
         font-size:12px;font-weight:800}
  .badge--ok{background:#eafaf1;color:#1a7f37}
  .badge--no{background:#fdecec;color:#b42318}

  .pf-actions{margin-left:auto;display:flex;gap:8px}
  .btn{border:1px solid #d1d5db;background:#fff;color:#1f2937;border-radius:10px;padding:8px 12px;font-weight:700}
  .btn-primary{background:#2962ff;border-color:#2962ff;color:#fff}
  .btn-light{background:#eef2f7}

  /* ---------- TABS ---------- */
  .tabs-line{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin:8px 0 0}
  .tab-chip{border:1px solid #e5e7eb;background:#f3f6fb;color:#1f2a44;border-radius:12px 12px 0 0;
            padding:10px 14px;font-weight:800;text-align:center;display:flex;gap:8px;justify-content:center}
  .tab-chip:not(.is-active){box-shadow:inset 0 -2px 0 0 #e5e7eb}
  .tab-chip.is-active{background:#fff;border-bottom-color:transparent;position:relative;z-index:2}

  /* Conteúdo fundido com tab ativa  */
  .tab-wrap{border:1px solid #e5e7eb;border-top:none;border-radius:0 12px 12px 12px;background:#fff;margin-top:-1px}
  .tab-pane{display:none;padding:16px}
  .tab-pane.is-active{display:block}

  /* ---------- FORM CONTROLS ---------- */
  .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
  .grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px}
  .ui{display:flex;flex-direction:column;gap:6px}
  .ui>label{font-weight:700;color:#374151}
  .inpt,.sel,.area{border:1px solid #e5e7eb;border-radius:8px;background:#fff;padding:10px 12px;width:100%}
  .area{min-height:110px}

  .avatar-row{display:flex;align-items:center;gap:16px;margin-bottom:10px}
  .avatar{width:72px;height:72px;border-radius:50%;object-fit:cover;background:#eef2f6}
  .drop{border:2px dashed #cbd5e1;border-radius:12px;padding:14px;width:280px;color:#64748b;text-align:center}

  /* ---------- FOOTER DE EDIÇÃO ---------- */
  .editor-footer{position:fixed;left:0;right:0;bottom:0;background:#fff;border-top:1px solid #e5e7eb;
                 padding:10px 16px;display:none;z-index:60}
  .editor-footer .inner{max-width:1200px;margin:0 auto;display:flex;justify-content:flex-end;gap:8px}
  #edit-spacer{display:none;height:62px} /* para não tapar os últimos campos */

  /* auxiliares */
  .always-enabled{pointer-events:auto}
</style>

{{-- ===== Cabeçalho ===== --}}
<div class="pf-head">
  <div>
    <h1 class="pf-title">Perfil do Atleta</h1>
    <div class="pf-sub">
      <div style="font-weight:800">{{ $utilizador->name ?? '—' }}</div>
      <span class="badge {{ $ativo ? 'badge--ok':'badge--no' }}">{{ $ativo ? 'Ativo' : 'Inativo' }}</span>
    </div>
  </div>

  <div class="pf-actions">
    <a href="{{ $backUrl }}" class="btn btn-light" title="Voltar">Voltar</a>
    @can('update', $utilizador)
      <button id="btn-edit" type="button" class="btn btn-primary">Editar</button>
    @endcan
  </div>
</div>

{{-- ===== Tabs + Conteúdo ===== --}}
<nav class="tabs-line" id="tabs">
  <button class="tab-chip is-active" data-tab="pessoais">👤 Dados Pessoais</button>
  <button class="tab-chip"           data-tab="desportivos">🏊 Dados Desportivos</button>
  <button class="tab-chip"           data-tab="financeiros">€ Dados Financeiros</button>
  <button class="tab-chip"           data-tab="config">⚙ Configuração</button>
</nav>

<div class="tab-wrap">
  <form id="user-form" action="{{ route('utilizadores.update',$utilizador) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <section class="tab-pane is-active" data-pane="pessoais">
      @include('utilizadores.tabs.pessoais', [
        'utilizador' => $utilizador,
        'escaloes'   => $escaloes ?? \App\Models\Escalao::orderBy('nome')->get(),
        'tipos'      => $tipos    ?? \App\Models\TipoUser::orderBy('nome')->get(),
        'perfis'     => $perfis   ?? \Spatie\Permission\Models\Role::orderBy('name')->get(),
      ])
    </section>

    <section class="tab-pane" data-pane="desportivos">
      @include('utilizadores.tabs.desportivos', ['utilizador'=>$utilizador])
    </section>

    <section class="tab-pane" data-pane="financeiros">
      @include('utilizadores.tabs.financeiros', ['utilizador'=>$utilizador])
    </section>

    <section class="tab-pane" data-pane="config">
      @include('utilizadores.tabs.config', ['utilizador'=>$utilizador])
    </section>

  </form>
</div>

{{-- Espaço para a barra fixa não tapar o fim do conteúdo --}}
<div id="edit-spacer"></div>

{{-- ===== Barra fixa de ações (apenas em edição) ===== --}}
<div class="editor-footer" id="editor-footer">
  <div class="inner">
    <button type="button" class="btn" id="btn-cancel">Cancelar</button>
    <button class="btn btn-primary" form="user-form">Guardar</button>
  </div>
</div>

<script>
  // ----- Tabs
  document.querySelectorAll('.tabs-line .tab-chip').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      document.querySelectorAll('.tabs-line .tab-chip').forEach(b=>b.classList.remove('is-active'));
      document.querySelectorAll('.tab-pane').forEach(p=>p.classList.remove('is-active'));
      btn.classList.add('is-active');
      document.querySelector(`[data-pane="${btn.dataset.tab}"]`).classList.add('is-active');
    });
  });

  // ----- Edição
  const form        = document.getElementById('user-form');
  const footer      = document.getElementById('editor-footer');
  const spacer      = document.getElementById('edit-spacer');

  function toggleReadonly(lock){
    form.querySelectorAll('input, select, textarea').forEach(el=>{
      if (el.closest('.always-enabled')) return;
      if (el.type === 'file') el.disabled = lock;
      else if (el.tagName === 'SELECT') el.disabled = lock;
      else el.readOnly = lock;
    });
  }
  toggleReadonly(true);

  document.getElementById('btn-edit')?.addEventListener('click', ()=>{
    toggleReadonly(false);
    footer.style.display = 'block';
    spacer.style.display = 'block';
  });

  document.getElementById('btn-cancel')?.addEventListener('click', ()=>{
    toggleReadonly(true);
    footer.style.display = 'none';
    spacer.style.display = 'none';
  });

  // preview simples da foto (se existir campo nos parciais)
  const up = document.getElementById('foto-input');
  const img = document.getElementById('foto-preview');
  up?.addEventListener('change', e=>{
    const f = e.target.files?.[0]; if(!f) return;
    const r = new FileReader();
    r.onload = ev => { if(img) img.src = ev.target.result; };
    r.readAsDataURL(f);
  });
</script>
@endsection
