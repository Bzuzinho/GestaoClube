{{-- ======== VISUALIZAÇÃO ======== --}}
<div id="blocoVisualizacao">
  <div class="row g-3">
    <div class="col-md-4">
      <div class="text-muted small">Mensalidade</div>
      <div class="form-control-plaintext">{{ optional($dadosFinanceiros->mensalidade)->nome ?? '—' }}</div>
    </div>
    <div class="col-md-4">
      <div class="text-muted small">IBAN</div>
      <div class="form-control-plaintext">{{ $dadosFinanceiros->iban ?? '—' }}</div>
    </div>
    <div class="col-md-4">
      <div class="text-muted small">NIF (Faturação)</div>
      <div class="form-control-plaintext">{{ $dadosFinanceiros->nif_faturacao ?? '—' }}</div>
    </div>
  </div>

  <hr class="my-3">

  <div class="d-flex align-items-center justify-content-between mb-2">
    <h6 class="mb-0">Faturas</h6>
    <a class="btn btn-light btn-sm" href="{{ route('faturas.index') }}">Abrir módulo</a>
  </div>

  <table class="table table-sm table-bordered align-middle" style="font-size:13px;">
    <thead>
      <tr>
        <th class="bg-light text-uppercase">#</th>
        <th class="bg-light text-uppercase">Emissão</th>
        <th class="bg-light text-uppercase">Estado</th>
        <th class="bg-light text-uppercase text-end">Total</th>
      </tr>
    </thead>
    <tbody>
      @forelse(($utilizador->faturas ?? []) as $f)
        <tr>
          <td>{{ $f->numero ?? $f->id }}</td>
          <td>{{ optional($f->data_emissao ?? $f->created_at)->format('d/m/Y') }}</td>
          <td>
            <span class="badge {{ ($f->estado_pagamento ?? '') == 'pago' || $f->estado_pagamento == 1 ? 'text-bg-success' : 'text-bg-warning' }}">
              {{ is_numeric($f->estado_pagamento) ? ($f->estado_pagamento ? 'Pago' : 'Pendente') : ucfirst($f->estado_pagamento ?? 'pendente') }}
            </span>
          </td>
          <td class="text-end">{{ number_format($f->total ?? 0, 2, ',', ' ') }} €</td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center text-muted">Sem faturas.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- ======== EDIÇÃO ======== --}}
<div id="blocoEdicao" class="d-none">
  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label small">Mensalidade</label>
      <select name="dados_financeiros[mensalidade_id]" class="form-select">
        <option value="">-- Sem mensalidade --</option>
        @foreach(\App\Models\Mensalidade::all() as $m)
          <option value="{{ $m->id }}" @selected(old('dados_financeiros.mensalidade_id', optional($dadosFinanceiros)->mensalidade_id)==$m->id)>
            {{ $m->nome }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label small">IBAN</label>
      <input type="text" name="dados_financeiros[iban]" class="form-control"
             value="{{ old('dados_financeiros.iban', $dadosFinanceiros->iban ?? '') }}">
    </div>
    <div class="col-md-4">
      <label class="form-label small">NIF (Faturação)</label>
      <input type="text" name="dados_financeiros[nif_faturacao]" class="form-control"
             value="{{ old('dados_financeiros.nif_faturacao', $dadosFinanceiros->nif_faturacao ?? '') }}">
    </div>
  </div>
</div>
