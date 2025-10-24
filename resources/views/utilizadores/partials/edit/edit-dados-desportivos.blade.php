{{-- ======== VISUALIZAÇÃO ======== --}}
<div id="blocoVisualizacao">
  <div class="row g-3">
    <div class="col-md-3"><div class="text-muted small">Altura</div><div class="form-control-plaintext">{{ $dadosDesportivos->altura ?? '—' }}</div></div>
    <div class="col-md-3"><div class="text-muted small">Peso</div><div class="form-control-plaintext">{{ $dadosDesportivos->peso ?? '—' }}</div></div>
    <div class="col-md-3"><div class="text-muted small">Nº Federação</div><div class="form-control-plaintext">{{ $dadosDesportivos->numero_federacao ?? '—' }}</div></div>
    <div class="col-md-3"><div class="text-muted small">PMB</div><div class="form-control-plaintext">{{ $dadosDesportivos->pmb ?? '—' }}</div></div>
    <div class="col-md-3"><div class="text-muted small">Data Inscrição</div><div class="form-control-plaintext">{{ optional($dadosDesportivos->data_inscricao)->format('d-m-Y') ?? '—' }}</div></div>
    <div class="col-md-3"><div class="text-muted small">Atestado Médico</div><div class="form-control-plaintext">{{ $dadosDesportivos->atestado_medico ?? '—' }}</div></div>
    <div class="col-md-3"><div class="text-muted small">Data Atestado</div><div class="form-control-plaintext">{{ optional($dadosDesportivos->data_atestado)->format('d-m-Y') ?? '—' }}</div></div>
    <div class="col-12"><div class="text-muted small">Informações Médicas</div><div class="form-control-plaintext">{{ $dadosDesportivos->informacoes_medicas ?? '—' }}</div></div>
    <div class="col-12"><div class="text-muted small">Observações</div><div class="form-control-plaintext">{{ $dadosDesportivos->observacoes ?? '—' }}</div></div>
  </div>

  <hr class="my-3">

  {{-- Presenças / Resultados / Treinos (opcional) --}}
  @include('utilizadores.partials.edit.presencas', ['presencas' => $utilizador->presencas ?? []])
  @include('utilizadores.partials.edit.resultados', ['resultados' => $utilizador->resultados ?? []])
  @include('utilizadores.partials.edit.treinos', ['treinos' => $utilizador->treinos ?? []])
</div>

{{-- ======== EDIÇÃO ======== --}}
<div id="blocoEdicao" class="d-none">
  <div class="row g-3">
    <div class="col-md-3"><label class="form-label small">Altura</label>
      <input type="text" name="altura" class="form-control" value="{{ old('altura',$dadosDesportivos->altura ?? '') }}">
    </div>
    <div class="col-md-3"><label class="form-label small">Peso</label>
      <input type="text" name="peso" class="form-control" value="{{ old('peso',$dadosDesportivos->peso ?? '') }}">
    </div>
    <div class="col-md-3"><label class="form-label small">Nº Federação</label>
      <input type="text" name="numero_federacao" class="form-control" value="{{ old('numero_federacao',$dadosDesportivos->numero_federacao ?? '') }}">
    </div>
    <div class="col-md-3"><label class="form-label small">PMB</label>
      <input type="text" name="pmb" class="form-control" value="{{ old('pmb',$dadosDesportivos->pmb ?? '') }}">
    </div>

    <div class="col-md-3"><label class="form-label small">Data Inscrição</label>
      <input type="date" name="data_inscricao" class="form-control"
             value="{{ old('data_inscricao', optional($dadosDesportivos->data_inscricao)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3"><label class="form-label small">Atestado Médico</label>
      <input type="text" name="atestado_medico" class="form-control"
             value="{{ old('atestado_medico',$dadosDesportivos->atestado_medico ?? '') }}">
    </div>
    <div class="col-md-3"><label class="form-label small">Data Atestado</label>
      <input type="date" name="data_atestado" class="form-control"
             value="{{ old('data_atestado', optional($dadosDesportivos->data_atestado)->format('Y-m-d')) }}">
    </div>

    <div class="col-12"><label class="form-label small">Informações Médicas</label>
      <textarea name="informacoes_medicas" class="form-control" rows="3">{{ old('informacoes_medicas',$dadosDesportivos->informacoes_medicas ?? '') }}</textarea>
    </div>
    <div class="col-12"><label class="form-label small">Observações</label>
      <textarea name="observacoes" class="form-control" rows="3">{{ old('observacoes',$dadosDesportivos->observacoes ?? '') }}</textarea>
    </div>

    <div class="col-md-6"><label class="form-label small">Ficheiro Atestado (AM)</label>
      <input type="file" name="arquivo_am" class="form-control">
      @if(!empty($dadosDesportivos->arquivo_am_path))
        <small class="text-muted d-block mt-1">Ficheiro atual: {{ basename($dadosDesportivos->arquivo_am_path) }}</small>
      @endif
    </div>
  </div>
</div>
