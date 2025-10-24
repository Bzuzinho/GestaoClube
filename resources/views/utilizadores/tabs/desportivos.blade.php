{{-- resources/views/utilizadores/tabs/desportivos.blade.php --}}
<div class="card">
  <h3 class="card-title">Dados Desportivos</h3>

  <div class="grid-2">
    <div class="ui-group">
      <label>Nº Licença</label>
      <input class="ui-input" name="licenca" value="{{ old('licenca',$utilizador->licenca) }}">
    </div>
    <div class="ui-group">
      <label>Categoria Principal</label>
      <input class="ui-input" name="categoria" value="{{ old('categoria',$utilizador->categoria) }}">
    </div>

    <div class="ui-group">
      <label>Observações</label>
      <textarea class="ui-textarea" name="observacoes">{{ old('observacoes',$utilizador->observacoes) }}</textarea>
    </div>
  </div>
</div>
