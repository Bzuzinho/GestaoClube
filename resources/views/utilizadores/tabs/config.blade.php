{{-- resources/views/utilizadores/tabs/config.blade.php --}}
<div class="card">
  <h3 class="card-title">Configuração</h3>

  <div class="grid-2">
    <div class="ui-group">
      <label>Username (público)</label>
      <input class="ui-input" name="username" value="{{ old('username',$utilizador->username) }}">
    </div>

    <div class="ui-group">
      <label>Receber notificações por email</label>
      <select class="ui-select" name="notifica_email">
        <option value="1" @selected(old('notifica_email',$utilizador->notifica_email)==1)>Sim</option>
        <option value="0" @selected(old('notifica_email',$utilizador->notifica_email)==0)>Não</option>
      </select>
    </div>

    <div class="ui-group">
      <label>Idioma</label>
      <select class="ui-select" name="idioma">
        <option value="pt" @selected(old('idioma',$utilizador->idioma)=='pt')>Português</option>
        <option value="en" @selected(old('idioma',$utilizador->idioma)=='en')>English</option>
      </select>
    </div>
  </div>
</div>
