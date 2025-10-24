<!-- Modal: Gerar Faturas -->
<div class="modal fade" id="modalGerarFaturas" tabindex="-1" aria-labelledby="modalGerarFaturasLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('faturas.gerar') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalGerarFaturasLabel">Gerar Faturas Mensais</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Utilizador</label>
            <select name="user_id" class="form-select form-select-sm">
                <option value="">Todos</option>   {{-- valor vazio, não "todos" --}}
                @foreach($utilizadores as $u)
                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->numero_socio }})</option>
                @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="mes_inicio" class="form-label">Mês de Início</label>
            <input type="month" name="mes_inicio" id="mes_inicio" class="form-control" required>
          </div>

           <div class="col-md-6">
            <label class="form-label">Mês fim (opcional)</label>
            <input type="month" name="mes_fim" class="form-control form-control-sm" value="{{ now()->format('Y-m') }}">
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Gerar Faturas</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>
