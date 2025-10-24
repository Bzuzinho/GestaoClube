{{-- resources/views/utilizadores/tabs/pessoais.blade.php --}}
@php
  $foto = $utilizador->profile_photo_path ?? null;
  if($foto){
    $isUrl = \Illuminate\Support\Str::startsWith($foto, ['http://','https://']);
    $foto = $isUrl ? $foto : asset('storage/'.$foto);
  }
@endphp

<div class="card">
  <h3 class="card-title">Dados Pessoais</h3>

  <div class="avatar-row">
    <img id="foto-preview" class="avatar" src="{{ $foto ?? asset('/img/avatar-placeholder.svg') }}" alt="">
    <div>
      <label class="btn btn-compact always-enabled" data-upload for="foto-input">Carregar Foto</label>
      <input id="foto-input" type="file" name="profile_photo" class="d-none" accept="image/*">
      <div class="dropzone">Drop files here or browse files</div>
    </div>
  </div>

  <div class="grid-2">
    <div class="ui-group">
      <label>Nome Completo *</label>
      <input class="ui-input" name="name" value="{{ old('name',$utilizador->name) }}">
    </div>

    <div class="ui-group">
      <label>Nº Sócio</label>
      <input class="ui-input" name="numero_socio" value="{{ old('numero_socio',$utilizador->numero_socio) }}">
    </div>

    <div class="ui-group">
      <label>Cartão Cidadão</label>
      <input class="ui-input" name="cartao_cidadao" value="{{ old('cartao_cidadao',$utilizador->cartao_cidadao) }}">
    </div>

    <div class="ui-group">
      <label>NIF</label>
      <input class="ui-input" name="nif" value="{{ old('nif',$utilizador->nif) }}">
    </div>

    <div class="ui-group">
      <label>Email</label>
      <input type="email" class="ui-input" name="email" value="{{ old('email',$utilizador->email) }}">
    </div>

    <div class="ui-group">
      <label>Contacto</label>
      <input class="ui-input" name="contacto" value="{{ old('contacto',$utilizador->contacto) }}">
    </div>

    <div class="ui-group">
      <label>Data de Nascimento</label>
      <input type="date" class="ui-input" name="data_nascimento" value="{{ old('data_nascimento',$utilizador->data_nascimento) }}">
    </div>

    <div class="ui-group">
      <label>Sexo</label>
      <select class="ui-select" name="sexo">
        <option value="">Nenhum</option>
        <option value="M" @selected(old('sexo',$utilizador->sexo) === 'M')>Masculino</option>
        <option value="F" @selected(old('sexo',$utilizador->sexo) === 'F')>Feminino</option>
      </select>
    </div>

    <div class="ui-group">
      <label>Estado</label>
      <select class="ui-select" name="estado">
        <option value="1" @selected((old('estado',$utilizador->estado ?? 1))==1)>Ativo</option>
        <option value="0" @selected((old('estado',$utilizador->estado ?? 1))==0)>Inativo</option>
      </select>
    </div>

    <div class="ui-group">
      <label>Escalão(s)</label>
      <select class="ui-select" name="escalao_id[]" multiple size="8">
        @foreach($escaloes as $e)
          <option value="{{ $e->id }}"
            @selected(collect(old('escalao_id', $utilizador->escaloes?->pluck('id') ?? []))->contains($e->id))>
            {{ $e->nome }}
          </option>
        @endforeach
      </select>
      <small>Podes selecionar vários</small>
    </div>

    <div class="ui-group">
      <label>Tipo de Utilizador</label>
      <select class="ui-select" name="tipo_user_id[]" multiple size="8">
        @foreach($tipos as $t)
          <option value="{{ $t->id }}"
            @selected(collect(old('tipo_user_id',$utilizador->tipoUsers?->pluck('id') ?? []))->contains($t->id))>
            {{ $t->nome }}
          </option>
        @endforeach
      </select>
      <small>Podes selecionar vários</small>
    </div>

    <div class="ui-group">
      <label>Perfil de Permissões</label>
      <select class="ui-select" name="role">
        @foreach($perfis as $r)
          <option value="{{ $r->name }}" @selected(old('role',$utilizador->role ?? '') === $r->name)>{{ $r->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="ui-group">
      <label>Password (opcional)</label>
      <input type="password" class="ui-input" name="password" placeholder="Min. 8 caracteres">
    </div>
  </div>
</div>
