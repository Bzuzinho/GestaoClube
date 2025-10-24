@props(['utilizador','readonly' => false, 'escaloes' => [], 'tipos' => [], 'perfis' => [], 'podeDefinirRole' => null])
@php
  // Permite controlar via prop OU via hidden da view edit
  $podeDefinirRole = $podeDefinirRole ?? (request('_can_set_role') ?? (old('_can_set_role') ?? null));
  $bloqueiaRole = $readonly || ! (bool) $podeDefinirRole;
@endphp

<div class="form-grid">
  <x-ui.form-group label="Nome">
    <input name="name" value="{{ old('name',$utilizador->name) }}" @disabled($readonly) required>
  </x-ui.form-group>

  <x-ui.form-group label="Email">
    <input type="email" name="email" value="{{ old('email',$utilizador->email) }}" @disabled($readonly) required>
  </x-ui.form-group>

  <x-ui.form-group label="Password" hint="Mín. 8 caracteres">
    <input type="password" name="password" @disabled($readonly)>
  </x-ui.form-group>

  <x-ui.form-group label="Contacto">
    <input name="contacto" value="{{ old('contacto',$utilizador->contacto) }}" @disabled($readonly)>
  </x-ui.form-group>

  <x-ui.form-group label="Data de Nascimento">
    <input type="date" name="data_nascimento" value="{{ old('data_nascimento',$utilizador->data_nascimento) }}" @disabled($readonly)>
  </x-ui.form-group>

  <x-ui.form-group label="Sexo">
    <select name="sexo" @disabled($readonly)>
      <option value="">Selecionar</option>
      <option value="M" @selected(old('sexo',$utilizador->sexo)==='M')>Masculino</option>
      <option value="F" @selected(old('sexo',$utilizador->sexo)==='F')>Feminino</option>
    </select>
  </x-ui.form-group>

  <x-ui.form-group label="Estado">
    <select name="estado" @disabled($readonly)>
      <option value="1" @selected(old('estado',$utilizador->estado)==1)>Ativo</option>
      <option value="0" @selected(old('estado',$utilizador->estado)==0)>Inativo</option>
    </select>
  </x-ui.form-group>

  {{-- Escalões (N:N) --}}
  <x-ui.form-group label="Escalão(s)" hint="Podes selecionar vários">
    @php $selEsc = old('escaloes', $utilizador->escaloes->pluck('id')->all()); @endphp
    <select name="escaloes[]" multiple size="6" @disabled($readonly)>
      @foreach($escaloes as $e)
        <option value="{{ $e->id }}" @selected(in_array($e->id,$selEsc))>{{ $e->nome }}</option>
      @endforeach
    </select>
  </x-ui.form-group>

  {{-- Tipos (N:N) --}}
  <x-ui.form-group label="Tipo de Utilizador" hint="Podes selecionar vários">
    @php $selTipos = old('tipoUsers', $utilizador->tipoUsers->pluck('id')->all()); @endphp
    <select name="tipoUsers[]" multiple size="6" @disabled($readonly)>
      @foreach($tipos as $t)
        <option value="{{ $t->id }}" @selected(in_array($t->id,$selTipos))>{{ $t->nome }}</option>
      @endforeach
    </select>
  </x-ui.form-group>

  {{-- Perfil / Role --}}
    <x-ui.form-group label="Perfil de Permissões">
    <select name="role_id" @disabled($bloqueiaRole)>
        <option value="">Utilizador</option>
        @foreach($perfis as $p)
        <option value="{{ $p->id }}" @selected(optional($utilizador->roles->first())->id === $p->id)>
            {{ $p->name }}
        </option>
        @endforeach
    </select>
    </x-ui.form-group>

  <div class="fg fg--full">
    <x-ui.form-group label="Foto de Perfil">
      <input type="file" name="profile_photo" accept="image/*" @disabled($readonly)>
      @if($utilizador->profile_photo_path)
        <div style="margin-top:6px">
          <img src="{{ Str::startsWith($utilizador->profile_photo_path,['http','//'])
                        ? $utilizador->profile_photo_path
                        : asset('storage/'.$utilizador->profile_photo_path) }}"
               alt="" class="avatar-img">
        </div>
      @endif
    </x-ui.form-group>
  </div>
</div>
