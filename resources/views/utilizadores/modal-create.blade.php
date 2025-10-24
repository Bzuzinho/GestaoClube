<div class="modal">
  <div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="mdl-title">
    <div class="modal__header">
      <h3 id="mdl-title">Novo Utilizador</h3>
      <button class="icon-btn" data-close aria-label="Fechar">
        <svg viewBox="0 0 24 24" width="18" height="18"><path d="M6 6l12 12M6 18L18 6" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg>
      </button>
    </div>

    <form action="{{ route('utilizadores.store') }}" method="POST" enctype="multipart/form-data" class="modal__body">
      @csrf

      <div class="form-grid">
        <div class="fg">
          <x-ui.form-group label="Nome" for="name">
            <input id="name" name="name" type="text" required>
          </x-ui.form-group>
        </div>

        <div class="fg">
          <x-ui.form-group label="Email" for="email">
            <input id="email" name="email" type="email" required>
          </x-ui.form-group>
        </div>

        <div class="fg">
          <x-ui.form-group label="Password" for="password" hint="Mín. 8 caracteres">
            <input id="password" name="password" type="password" required>
          </x-ui.form-group>
        </div>

        <div class="fg">
          <x-ui.form-group label="NIF" for="nif">
            <input id="nif" name="nif" type="text">
          </x-ui.form-group>
        </div>

        <div class="fg">
          <x-ui.form-group label="Contacto" for="contacto">
            <input id="contacto" name="contacto" type="text">
          </x-ui.form-group>
        </div>

        <div class="fg">
          <x-ui.form-group label="Data de Nascimento" for="data_nascimento">
            <input id="data_nascimento" name="data_nascimento" type="date">
          </x-ui.form-group>
        </div>

        <div class="fg">
          <x-ui.form-group label="Sexo" for="sexo">
            <select id="sexo" name="sexo">
              <option value="">Selecionar</option>
              <option value="M">Masculino</option>
              <option value="F">Feminino</option>
            </select>
          </x-ui.form-group>
        </div>

        <div class="fg">
          <x-ui.form-group label="Estado" for="estado_utilizador">
            <select id="estado_utilizador" name="estado_utilizador">
              <option value="1" selected>Ativo</option>
              <option value="0">Inativo</option>
            </select>
          </x-ui.form-group>
        </div>

        <div class="fg">
          <<x-ui.form-group label="Escalão(s)" for="escalao_ids" hint="Podes selecionar vários">
            <select id="escalao_ids" name="escalao_ids[]" multiple size="4">
              @foreach($escaloes as $e)
                <option value="{{ $e->id }}">{{ $e->nome }}</option>
              @endforeach
            </select>
          </x-ui.form-group>
        </div>

        <div class="fg">
          <x-ui.form-group label="Tipo de Utilizador" for="tipo_user_ids" hint="Podes selecionar vários">
            <select id="tipo_user_ids" name="tipo_user_ids[]" multiple size="4">
              @foreach($tiposUtilizador as $t)
                <option value="{{ $t->id }}">{{ $t->nome }}</option>
              @endforeach
            </select>
          </x-ui.form-group>
        </div>

        <div class="fg">
          <x-ui.form-group label="Perfil de Permissões" for="perfil">
            <select id="perfil" name="perfil">
              <option value="">Selecionar</option>
              @foreach($perfis as $p)
                <option value="{{ $p->name }}">{{ $p->name }}</option>
              @endforeach
            </select>
          </x-ui.form-group>
        </div>

        <div class="fg fg--full">
          <x-ui.form-group label="Foto de Perfil" for="profile_photo_path">
            <input id="profile_photo_path" name="profile_photo_path" type="file" accept="image/*">
          </x-ui.form-group>
        </div>
      </div>

      <div class="modal__footer">
        <button type="button" class="btn btn-compact" data-close>Cancelar</button>
        <button type="submit" class="btn btn-primary btn-compact">Guardar</button>
      </div>
    </form>
  </div>
</div>
