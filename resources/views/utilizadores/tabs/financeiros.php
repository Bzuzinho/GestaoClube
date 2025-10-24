{{-- resources/views/utilizadores/tabs/financeiros.blade.php --}}
<div class="card">
  <h3 class="card-title">Dados Financeiros</h3>

  <div class="grid-3">
    <div class="ui-group">
      <label>IBAN</label>
      <input class="ui-input" name="iban" value="{{ old('iban',$utilizador->iban) }}">
    </div>
    <div class="ui-group">
      <label>Entidade</label>
      <input class="ui-input" name="entidade" value="{{ old('entidade',$utilizador->entidade) }}">
    </div>
    <div class="ui-group">
      <label>Referência</label>
      <input class="ui-input" name="referencia" value="{{ old('referencia',$utilizador->referencia) }}">
    </div>
  </div>

  <div class="ui-group" style="margin-top:12px">
    <label>Notas</label>
    <textarea class="ui-textarea" name="notas_financeiras">{{ old('notas_financeiras',$utilizador->notas_financeiras) }}</textarea>
  </div>
</div>
