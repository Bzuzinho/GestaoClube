{{-- resources/views/faturas/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Header + breadcrumbs --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Editar Fatura #{{ $fatura->id }}</h4>

        <button type="button"
                class="btn btn-sm btn-secondary"
                onclick="window.location.href='{{ route('faturas.index') }}'">
            ← Voltar
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-sm py-2">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-sm py-2">{{ session('warning') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger py-2">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li class="small">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('faturas.update', $fatura->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-3 align-items-center text-sm">
                    <div class="col-md-3">
                        <label class="form-label form-label-sm mb-1">Atleta</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $fatura->user?->name ?? '—' }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label form-label-sm mb-1">Data da Fatura</label>
                        <input type="datetime-local" name="data_emissao"
                              class="form-control form-control-sm"
                              value="{{ old('data_emissao', optional($fatura->data_emissao ?? $fatura->created_at)->format('Y-m-d\TH:i')) }}">
                        <small class="text-muted">Data completa (pode ajustar)</small>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label form-label-sm mb-1">Mês de Referência</label>
                        <input type="month" name="mes" class="form-control form-control-sm"
                              value="{{ old('mes', $fatura->mes) }}">
                        <small class="text-muted">Deixe vazio para faturas manuais</small>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label form-label-sm mb-1">Estado</label>
                        <select class="form-select form-select-sm" name="estado_pagamento">
                            <option value="0" @selected((int)old('estado_pagamento',$fatura->estado_pagamento)===0)>Pendente</option>
                            <option value="1" @selected((int)old('estado_pagamento',$fatura->estado_pagamento)===1)>Pago</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label form-label-sm mb-1">Total Fatura</label>
                        <input type="text" id="total_fatura" class="form-control form-control-sm" value="€{{ number_format($fatura->valor ?? 0, 2, ',', '.') }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label form-label-sm mb-1">Referência/Recibo (opcional)</label>
                        <input type="text" name="referencia_pagamento" class="form-control form-control-sm"
                               value="{{ old('referencia_pagamento', $fatura->referencia_pagamento) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabela de Itens --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Linhas da Fatura</h6>
                    <button type="button" id="btnAddLinha" class="btn btn-sm btn-primary">+ Adicionar linha</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle" id="tabelaItens">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 38%">Descrição</th>
                                <th style="width: 14%">Valor Unitário (€)</th>
                                <th style="width: 14%">Quantidade</th>
                                <th style="width: 14%">Imposto %</th>
                                <th style="width: 14%">Total Linha (€)</th>
                                <th style="width: 6%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i=0; @endphp
                            @forelse($fatura->items as $item)
                                <tr>
                                    <td>
                                        <input type="hidden" name="itens[{{ $i }}][id]" value="{{ $item->id }}">
                                        <input type="text" name="itens[{{ $i }}][descricao]" class="form-control form-control-sm"
                                               value="{{ old("itens.$i.descricao", $item->descricao) }}" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" name="itens[{{ $i }}][valor_unitario]"
                                               class="form-control form-control-sm input-valor"
                                               value="{{ old("itens.$i.valor_unitario", $item->valor_unitario) }}" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" name="itens[{{ $i }}][quantidade]"
                                               class="form-control form-control-sm input-qtd"
                                               value="{{ old("itens.$i.quantidade", $item->quantidade) }}" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" name="itens[{{ $i }}][imposto_percentual]"
                                               class="form-control form-control-sm input-iva"
                                               value="{{ old("itens.$i.imposto_percentual", $item->imposto_percentual ?? 0) }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm input-total" value="{{ number_format($item->total_linha ?? 0, 2, ',', '.') }}" readonly>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger btnDelLinha">&minus;</button>
                                    </td>
                                </tr>
                                @php $i++; @endphp
                            @empty
                                {{-- Linha vazia inicial --}}
                                <tr>
                                    <td>
                                        <input type="text" name="itens[0][descricao]" class="form-control form-control-sm" value="" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" name="itens[0][valor_unitario]"
                                               class="form-control form-control-sm input-valor" value="0.00" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" name="itens[0][quantidade]"
                                               class="form-control form-control-sm input-qtd" value="1.00" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" name="itens[0][imposto_percentual]"
                                               class="form-control form-control-sm input-iva" value="0.00">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm input-total" value="0,00" readonly>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger btnDelLinha">&minus;</button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Total:</th>
                                <th><input type="text" id="sum_total" class="form-control form-control-sm" value="€{{ number_format($fatura->valor ?? 0, 2, ',', '.') }}" readonly></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Template para nova linha --}}
<template id="tplLinha">
<tr>
    <td>
        <input type="text" name="__NAME__[descricao]" class="form-control form-control-sm" required>
    </td>
    <td>
        <input type="number" step="0.01" min="0" name="__NAME__[valor_unitario]"
               class="form-control form-control-sm input-valor" value="0.00" required>
    </td>
    <td>
        <input type="number" step="0.01" min="0.01" name="__NAME__[quantidade]"
               class="form-control form-control-sm input-qtd" value="1.00" required>
    </td>
    <td>
        <input type="number" step="0.01" min="0" name="__NAME__[imposto_percentual]"
               class="form-control form-control-sm input-iva" value="0.00">
    </td>
    <td>
        <input type="text" class="form-control form-control-sm input-total" value="0,00" readonly>
    </td>
    <td class="text-center">
        <button type="button" class="btn btn-sm btn-outline-danger btnDelLinha">&minus;</button>
    </td>
</tr>
</template>

@endsection

@push('scripts')
<script>
(function() {
    const tabela = document.getElementById('tabelaItens');
    const tbody  = tabela.querySelector('tbody');
    const btnAdd = document.getElementById('btnAddLinha');
    const tpl    = document.getElementById('tplLinha');
    const fmt    = new Intl.NumberFormat('pt-PT', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    let idx = {{ max( count($fatura->items)-1, 0 ) }} + 1;

    function recalcLinha(tr) {
        const v  = parseFloat(tr.querySelector('.input-valor')?.value || '0');
        const q  = parseFloat(tr.querySelector('.input-qtd')?.value   || '0');
        const iva= parseFloat(tr.querySelector('.input-iva')?.value   || '0');
        const total = v * q * (1 + (iva/100));
        tr.querySelector('.input-total').value = fmt.format(isFinite(total) ? total : 0);
    }

    function recalcTotal() {
        let soma = 0;
        tbody.querySelectorAll('tr').forEach(tr => {
            const t = tr.querySelector('.input-total')?.value || '0';
            soma += parseFloat(t.replace('.', '').replace(',', '.')) || 0;
        });
        document.getElementById('sum_total').value = '€' + fmt.format(soma);
        document.getElementById('total_fatura').value = '€' + fmt.format(soma);
    }

    function wire(tr) {
        tr.querySelectorAll('.input-valor, .input-qtd, .input-iva').forEach(inp => {
            inp.addEventListener('input', () => { recalcLinha(tr); recalcTotal(); });
        });
        tr.querySelector('.btnDelLinha')?.addEventListener('click', () => {
            tr.remove();
            recalcTotal();
        });
        recalcLinha(tr);
    }

    btnAdd?.addEventListener('click', () => {
        const html = tpl.innerHTML.replaceAll('__NAME__', `itens[${idx++}]`);
        const tmp = document.createElement('tbody');
        tmp.innerHTML = html.trim();
        const tr = tmp.firstElementChild;
        tbody.appendChild(tr);
        wire(tr);
        recalcTotal();
    });

    // ligar linhas existentes
    tbody.querySelectorAll('tr').forEach(wire);
    recalcTotal();
})();
</script>
@endpush
