@extends('layouts.app')

@if (session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if (session('warning'))
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
    {{ session('warning') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if (session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@if ($errors->any())
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Lista de Faturas</h4>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalGerarFaturas">
        <i class="bi bi-calendar-range"></i> Gerar Faturas
      </button>
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCriarFatura">
        <i class="bi bi-plus-circle"></i> Criar Fatura
      </button>
    </div>
  </div>
    @if(session('info')) <div class="alert alert-info">{{ session('info') }}</div> @endif
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

  <table class="table table-striped table-bordered align-middle">
    <thead class="bg-light">
    <tr>
        <th style="width: 90px;">Nº</th>
        <th style="width: 130px;">Data Emissão</th>
        <th style="width: 110px;">Mês Ref.</th>
        <th>Atleta</th>
        <th style="width: 120px;">Valor</th>
        <th style="width: 110px;">Estado</th>
        <th style="width: 140px;">Nº Recibo</th>
        <th style="width: 110px;">Ações</th>
    </tr>
    </thead>
    <tbody>
      @forelse($faturas as $fatura)
      <tr>
          {{-- Nº da fatura (id interno) --}}
          <td>#{{ $fatura->id }}</td>

          {{-- Data de emissão (dd/mm/yyyy), fallback para created_at --}}
          <td>
              @php
                  $dt = $fatura->data_emissao ?? $fatura->created_at;
              @endphp
              {{ $dt ? \Carbon\Carbon::parse($dt)->format('d/m/Y') : '—' }}
          </td>

          {{-- Mês de referência (pode ser vazio nas manuais) --}}
          <td>{{ $fatura->mes ?: '—' }}</td>

          {{-- Utilizador --}}
          <td>{{ $fatura->user?->name ?? '—' }}</td>

          {{-- Total --}}
          <td>€{{ number_format($fatura->valor ?? 0, 2, ',', '.') }}</td>

          {{-- Estado --}}
          <td>{{ (int) $fatura->estado_pagamento === 1 ? 'Pago' : 'Pendente' }}</td>
          <td>{{ $fatura->numero_recibo ?? '—' }}</td> {{-- NOVO --}}
          {{-- Ações --}}
          <td class="text-nowrap">
              {{-- Editar --}}
              <a href="{{ route('faturas.edit', $fatura->id) }}"
                class="btn btn-sm btn-outline-primary"
                title="Editar">
                  <i class="bi bi-pencil"></i>
              </a>

              {{-- Apagar --}}
              <form action="{{ route('faturas.destroy', $fatura->id) }}"
                    method="POST"
                    class="d-inline"
                    onsubmit="return confirm('Apagar a fatura #{{ $fatura->id }}? Esta ação é irreversível.');">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="btn btn-sm btn-outline-danger ms-1"
                          title="Apagar">
                      <i class="bi bi-trash"></i>
                  </button>
              </form>
          </td>
    </tr>
@empty
    <tr><td colspan="8" class="text-center">Sem faturas.</td></tr>
@endforelse
</tbody>
  </table>

  {{ $faturas->links() }}
</div>

@include('faturas.modal-gerar-faturas')
@include('faturas.modal-criar-fatura')

<script>
const itensBase = @json($itensFatura);

function preencherCampos(select) {
  const option = select.selectedOptions[0];
  const row = select.closest('tr');

  if (option && option.value) {
    row.querySelector('[name$="[descricao]"]').value = option.value;
    row.querySelector('[name$="[valor_unitario]"]').value = option.dataset.valor;
    row.querySelector('[name$="[imposto_percentual]"]').value = option.dataset.imposto;
    calcularTotalLinha(row);
  }
}

function adicionarLinha() {
  const idx = document.querySelectorAll('#linhasItensFatura tr').length;
  const tbody = document.getElementById('linhasItensFatura');
  if (!tbody) return;

  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>
      <select name="item_select_${idx}" class="form-select" onchange="preencherCampos(this)">
        <option value="">Selecionar</option>
        ${itensBase.map(i => `<option value="${i.descricao}" data-valor="${i.valor_unitario}" data-imposto="${i.imposto_percentual}">${i.descricao}</option>`).join('')}
      </select>
      <input type="text" name="itens[${idx}][descricao]" class="form-control mt-1" placeholder="Descrição personalizada">
    </td>
    <td><input type="number" step="0.01" name="itens[${idx}][valor_unitario]" class="form-control" onchange="calcularTotalLinha(this.closest('tr'))"></td>
    <td><input type="number" step="1" name="itens[${idx}][quantidade]" class="form-control" value="1" onchange="calcularTotalLinha(this.closest('tr'))"></td>
    <td><input type="number" step="0.01" name="itens[${idx}][imposto_percentual]" class="form-control" onchange="calcularTotalLinha(this.closest('tr'))"></td>
    <td><input type="text" class="form-control total-linha" readonly></td>
    <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); calcularTotalGeral();">-</button></td>
  `;
  tbody.appendChild(tr);
}

function calcularTotalLinha(row) {
  const valor = parseFloat(row.querySelector('[name$="[valor_unitario]"]').value) || 0;
  const qtd = parseFloat(row.querySelector('[name$="[quantidade]"]').value) || 1;
  const imposto = parseFloat(row.querySelector('[name$="[imposto_percentual]"]').value) || 0;
  const total = valor * qtd * (1 + imposto / 100);
  row.querySelector('.total-linha').value = total.toFixed(2);
  calcularTotalGeral();
}

function calcularTotalGeral() {
  let total = 0;
  document.querySelectorAll('.total-linha').forEach(input => {
    total += parseFloat(input.value || 0);
  });
  const totalGeralInput = document.getElementById('totalGeral');
  if (totalGeralInput) {
    totalGeralInput.value = total.toFixed(2);
  }
}
</script>
@endsection

<script>
  // Recarrega quando a página volta do histórico (bfcache)
  window.addEventListener('pageshow', function (e) {
    if (e.persisted || (performance && performance.getEntriesByType)) {
      try {
        const nav = performance.getEntriesByType('navigation')[0];
        if (nav && nav.type === 'back_forward') location.reload();
      } catch (_) {}
    }
  });
</script>
