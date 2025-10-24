<!-- Modal: Criar Fatura Manual -->
<div class="modal fade" id="modalCriarFatura" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form class="modal-content" action="{{ route('faturas.storeManual') }}" method="POST">
      @csrf

      <div class="modal-header">
        <h6 class="modal-title">Criar Fatura (manual)</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body">
        @if ($errors->any())
          <div class="alert alert-danger py-2 mb-3">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li class="small">{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        @if (session('success'))
          <div class="alert alert-success alert-sm py-2 mb-3">{{ session('success') }}</div>
        @endif

        <div class="row g-2 mb-3">
          <div class="col-md-4">
            <label class="form-label form-label-sm">Atleta / Utilizador</label>
            <select name="user_id" class="form-select form-select-sm" required>
              @foreach($utilizadores as $u)
                <option value="{{ $u->id }}">{{ $u->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label form-label-sm">Mês (YYYY-MM)</label>
            <input type="month" name="mes" class="form-control form-control-sm" required>
          </div>
          <div class="col-md-3">
            <label class="form-label form-label-sm">Data da Fatura</label>
            <input type="date" name="data_emissao" class="form-control form-control-sm">
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-sm align-middle mb-0" id="tblLinhas">
            <thead class="table-light">
              <tr>
                <th style="width:28%">Catálogo (opcional)</th>
                <th style="width:28%">Descrição</th>
                <th style="width:12%">Valor (€)</th>
                <th style="width:12%">Qtd</th>
                <th style="width:12%">IVA %</th>
                <th style="width:8%"></th>
              </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
              <tr>
                <td colspan="6" class="d-flex justify-content-between align-items-center">
                  <button type="button" class="btn btn-sm btn-outline-primary" id="btnAddLinha">+ Adicionar linha</button>
                  <div class="d-flex align-items-center gap-2">
                    <span class="fw-semibold">Total:</span>
                    <input type="text" id="total_global" class="form-control form-control-sm" value="€0,00" readonly style="width:120px">
                  </div>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-success btn-sm">Gravar fatura</button>
      </div>
    </form>
  </div>
</div>

{{-- Dados de catálogo expostos ao JS --}}
<script>
  window.BSCN_CATALOGO = {
    // Mantém exatamente estas chaves:
    mensalidades: @json($mensalidades ?? []),   // [{id,designacao,valor}]
    itens: @json($itensCatalogo ?? []),           // [{descricao,valor_unitario,imposto_percentual}]
  };
</script>

<template id="tplLinha">
<tr>
  <td>
    <select class="form-select form-select-sm sel-catalogo">
      <option value="">— Selecionar (opcional) —</option>
      <optgroup label="Mensalidades"></optgroup>
      <optgroup label="Itens Fatura"></optgroup>
    </select>
  </td>
  <td>
    <input type="text" class="form-control form-control-sm inp-desc" name="linhas[__i__][descricao]" required>
  </td>
  <td>
    <input type="number" step="0.01" min="0" class="form-control form-control-sm inp-valor" name="linhas[__i__][valor_unitario]" required>
  </td>
  <td>
    <input type="number" step="0.01" min="0.01" class="form-control form-control-sm inp-qtd" name="linhas[__i__][quantidade]" value="1" required>
  </td>
  <td>
    <input type="number" step="0.01" min="0" class="form-control form-control-sm inp-iva" name="linhas[__i__][imposto_percentual]" value="0">
  </td>
  <td class="text-center">
    <button type="button" class="btn btn-sm btn-outline-danger btn-del">&minus;</button>
  </td>
</tr>
</template>

<script>
(function () {
  const tpl   = document.getElementById('tplLinha');
  const tbody = document.querySelector('#tblLinhas tbody');
  const btnAdd= document.getElementById('btnAddLinha');
  const fmt   = new Intl.NumberFormat('pt-PT',{minimumFractionDigits:2, maximumFractionDigits:2});
  let i = 0;

  const esc = s => (s ?? '').toString().replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));

  function buildOptions(select){
    const data = window.BSCN_CATALOGO || {};
    const mensalidades = Array.isArray(data.mensalidades) ? data.mensalidades : [];
    const itens        = Array.isArray(data.itens) ? data.itens : [];

    const gMens  = select.querySelector('optgroup[label="Mensalidades"]');
    const gItens = select.querySelector('optgroup[label="Itens Fatura"]');
    if (!gMens || !gItens) return;

    gMens.innerHTML = mensalidades.map(m => {
      const desc = esc(m.designacao ?? m.descricao ?? '');
      const val  = Number(m.valor ?? m.valor_unitario ?? 0);
      return `<option data-descricao="${desc}" data-valor="${val}" data-iva="0">
                ${desc} — €${val.toFixed(2)}
              </option>`;
    }).join('');

    gItens.innerHTML = itens.map(t => {
      const desc = esc(t.descricao ?? '');
      const val  = Number(t.valor_unitario ?? 0);
      const iva  = Number(t.imposto_percentual ?? 0);
      return `<option data-descricao="${desc}" data-valor="${val}" data-iva="${iva}">
                ${desc} — €${val.toFixed(2)} (IVA ${iva.toFixed(2)}%)
              </option>`;
    }).join('');
  }

  function calcTotalGlobal(){
    let soma = 0;
    tbody.querySelectorAll('tr').forEach(tr => {
      const v   = parseFloat(tr.querySelector('.inp-valor')?.value || '0');
      const q   = parseFloat(tr.querySelector('.inp-qtd')?.value   || '0');
      const iva = parseFloat(tr.querySelector('.inp-iva')?.value   || '0');
      const t   = v * q * (1 + (iva/100));
      if (isFinite(t)) soma += t;
    });
    document.getElementById('total_global').value = '€' + fmt.format(soma);
  }

  function wireRow(tr){
    tr.querySelectorAll('.inp-valor,.inp-qtd,.inp-iva').forEach(inp => {
      inp.addEventListener('input', calcTotalGlobal);
    });
    tr.querySelector('.btn-del')?.addEventListener('click', () => { tr.remove(); calcTotalGlobal(); });

    const sel = tr.querySelector('.sel-catalogo');
    sel.addEventListener('focus', () => { if (!sel.dataset.built) { buildOptions(sel); sel.dataset.built = '1'; }});
    sel.addEventListener('click', () => { if (!sel.dataset.built) { buildOptions(sel); sel.dataset.built = '1'; }});
    sel.addEventListener('change', () => {
      const opt = sel.options[sel.selectedIndex];
      if (!opt || !opt.dataset.descricao) return;
      tr.querySelector('.inp-desc').value  = opt.dataset.descricao || '';
      tr.querySelector('.inp-valor').value = opt.dataset.valor || 0;
      tr.querySelector('.inp-iva').value   = opt.dataset.iva ?? 0;
      if (!tr.querySelector('.inp-qtd').value) tr.querySelector('.inp-qtd').value = 1;
      calcTotalGlobal();
    });
  }

  function addLinha(prefill){
    const html = tpl.innerHTML.replaceAll('__i__', i++);
    const tmp  = document.createElement('tbody');
    tmp.innerHTML = html.trim();
    const tr = tmp.firstElementChild;
    tbody.appendChild(tr);
    wireRow(tr);

    if (prefill){
      tr.querySelector('.inp-desc').value  = prefill.descricao ?? '';
      tr.querySelector('.inp-valor').value = prefill.valor ?? 0;
      tr.querySelector('.inp-iva').value   = prefill.iva ?? 0;
      tr.querySelector('.inp-qtd').value   = prefill.qtd ?? 1;
    }
    calcTotalGlobal();
  }

  btnAdd?.addEventListener('click', () => addLinha());
  // começa com uma linha manual
  addLinha();
})();
</script>
