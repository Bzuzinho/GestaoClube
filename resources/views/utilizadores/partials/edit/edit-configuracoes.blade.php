@php
    $rolesDisponiveis = $rolesDisponiveis ?? [];
    $roleAtual = $roleAtual ?? (isset($utilizador) ? $utilizador->getRoleNames()->first() : null);
@endphp

{{-- ======== VISUALIZAÇÃO ======== --}}
<div id="blocoVisualizacao">
  <div class="row g-3">
    <div class="col-md-4">
      <div class="text-muted small">Perfil (Role)</div>
      <div class="form-control-plaintext">{{ $roleAtual ?? '—' }}</div>
    </div>
    <div class="col-md-4">
      <div class="text-muted small">RGPD – Consentimento</div>
      <div class="form-control-plaintext">{{ optional($configuracao)->consentimento ? 'Sim' : 'Não' }}</div>
    </div>
    <div class="col-md-4">
      <div class="text-muted small">Afiliação</div>
      <div class="form-control-plaintext">{{ optional($configuracao)->afiliacao ?? '—' }}</div>
    </div>
  </div>
</div>

{{-- ======== EDIÇÃO ======== --}}
<div id="blocoEdicao" class="d-none">
  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label small">Perfil (Role)</label>
      <select name="role" class="form-select">
        @foreach(($rolesDisponiveis ?? []) as $r)
          <option value="{{ $r }}" @selected(old('role',$roleAtual)===$r)>{{ ucfirst($r) }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label small">RGPD – Consentimento</label>
      <select name="consentimento" class="form-select">
        <option value="1" @selected(old('consentimento', optional($configuracao)->consentimento)==1)>Sim</option>
        <option value="0" @selected(old('consentimento', optional($configuracao)->consentimento)==0)>Não</option>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label small">Data Consentimento</label>
      <input type="date" name="data_consentimento" class="form-control"
             value="{{ old('data_consentimento', optional($configuracao->data_consentimento ?? null)->format('Y-m-d')) }}">
    </div>

    <div class="col-md-4">
      <label class="form-label small">Declaração Transporte</label>
      <select name="declaracao_transporte" class="form-select">
        <option value="1" @selected(old('declaracao_transporte', optional($configuracao)->declaracao_transporte)==1)>Sim</option>
        <option value="0" @selected(old('declaracao_transporte', optional($configuracao)->declaracao_transporte)==0)>Não</option>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label small">Data Transporte</label>
      <input type="date" name="data_transporte" class="form-control"
             value="{{ old('data_transporte', optional($configuracao->data_transporte ?? null)->format('Y-m-d')) }}">
    </div>

    <div class="col-md-4">
      <label class="form-label small">Afiliação</label>
      <input type="text" name="afiliacao" class="form-control"
             value="{{ old('afiliacao', optional($configuracao)->afiliacao) }}">
    </div>
    <div class="col-md-4">
      <label class="form-label small">Data Afiliação</label>
      <input type="date" name="data_afiliacao" class="form-control"
             value="{{ old('data_afiliacao', optional($configuracao->data_afiliacao ?? null)->format('Y-m-d')) }}">
    </div>

    {{-- Ficheiros (se aplicável) --}}
    <div class="col-md-4">
      <label class="form-label small">Ficheiro Consentimento</label>
      <input type="file" name="ficheiro_consentimento" class="form-control">
    </div>
    <div class="col-md-4">
      <label class="form-label small">Ficheiro Transporte</label>
      <input type="file" name="ficheiro_transporte" class="form-control">
    </div>
    <div class="col-md-4">
      <label class="form-label small">Ficheiro Afiliação</label>
      <input type="file" name="ficheiro_afiliacao" class="form-control">
    </div>
  </div>
</div>
