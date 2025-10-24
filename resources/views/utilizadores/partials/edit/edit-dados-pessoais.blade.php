{{-- ======== VISUALIZAÇÃO ======== --}}
<div id="blocoVisualizacao">
  <div class="row g-3">
    <div class="col-md-3">
      <div class="text-muted small mb-1">Foto</div>
      <div class="rounded-circle border bg-light d-flex align-items-center justify-content-center"
           style="width:96px;height:96px;">
        @if($utilizador->profile_photo_path)
          <img src="{{ asset('storage/'.$utilizador->profile_photo_path) }}"
               class="rounded-circle" style="width:96px;height:96px;object-fit:cover;" alt="Foto">
        @else
          <span class="text-secondary small">Sem Foto</span>
        @endif
      </div>
    </div>

    @php
      $dados = [
        'NIF' => $utilizador->nif,
        'Cartão Cidadão' => $utilizador->cartao_cidadao,
        'Menor' => ($utilizador->menor ? 'Sim' : 'Não'),
        'Estado' => ($utilizador->estado == 1 ? 'Ativo' : ($utilizador->estado == 0 ? 'Inativo' : 'Suspenso')),
        'Nome' => $utilizador->name,
        'Data Nascimento' => optional($utilizador->data_nascimento)->format('d-m-Y'),
        'Morada' => $utilizador->morada,
        'C.P.' => $utilizador->codigo_postal,
        'Localidade' => $utilizador->localidade,
        'Empresa' => $utilizador->empresa,
        'Escola' => $utilizador->escola,
        'Estado Civil' => $utilizador->estado_civil,
        'Ocupação' => $utilizador->ocupacao,
        'Nacionalidade' => $utilizador->nacionalidade,
        'Sexo' => [0=>'Feminino',1=>'Masculino',2=>'Outro'][$utilizador->sexo ?? 0] ?? '-',
        'Nº Irmãos' => $utilizador->numero_irmaos,
        'Contacto' => $utilizador->contacto,
        'Email' => $utilizador->email,
      ];
    @endphp

    @foreach($dados as $label => $valor)
      <div class="col-md-4">
        <div class="text-muted small">{{ $label }}</div>
        <div class="form-control-plaintext">{{ $valor ?? '—' }}</div>
      </div>
    @endforeach

    {{-- Escalões --}}
    <div class="col-12">
      <div class="text-muted small">Escalões</div>
      <div class="d-flex flex-wrap gap-2">
        @forelse($utilizador->escaloes ?? [] as $esc)
          <span class="badge text-bg-light border">{{ $esc->nome }}</span>
        @empty
          <span class="text-muted">—</span>
        @endforelse
      </div>
    </div>

    {{-- Tipos de Utilizador --}}
    <div class="col-12">
      <div class="text-muted small">Tipos de Utilizador</div>
      <div class="d-flex flex-wrap gap-2">
        @forelse($utilizador->tipoUsers ?? [] as $t)
          <span class="badge text-bg-light border">{{ $t->nome }}</span>
        @empty
          <span class="text-muted">—</span>
        @endforelse
      </div>
    </div>
  </div>
</div>

{{-- ======== EDIÇÃO ======== --}}
<div id="blocoEdicao" class="d-none">
  <div class="row g-3">
    <div class="col-md-3">
      <label class="form-label small">Foto de Perfil</label>
      <input type="file" name="profile_photo_path" class="form-control form-control-sm">
    </div>

    <div class="col-md-2">
      <label class="form-label small">NIF</label>
      <input type="text" name="nif" class="form-control" value="{{ old('nif',$utilizador->nif) }}">
    </div>
    <div class="col-md-3">
      <label class="form-label small">Cartão Cidadão</label>
      <input type="text" name="cartao_cidadao" class="form-control" value="{{ old('cartao_cidadao',$utilizador->cartao_cidadao) }}">
    </div>
    <div class="col-md-2">
      <label class="form-label small">Menor</label>
      <select name="menor" class="form-select">
        <option value="0" @selected(old('menor',$utilizador->menor)==0)>Não</option>
        <option value="1" @selected(old('menor',$utilizador->menor)==1)>Sim</option>
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label small">Estado</label>
      <select name="estado" class="form-select">
        <option value="1" @selected(old('estado',$utilizador->estado)==1)>Ativo</option>
        <option value="0" @selected(old('estado',$utilizador->estado)==0)>Inativo</option>
        <option value="2" @selected(old('estado',$utilizador->estado)==2)>Suspenso</option>
      </select>
    </div>

    <div class="col-md-8">
      <label class="form-label small">Nome</label>
      <input type="text" name="name" class="form-control" value="{{ old('name',$utilizador->name) }}">
    </div>
    <div class="col-md-4">
      <label class="form-label small">Data Nascimento</label>
      <input type="text" name="data_nascimento" class="form-control"
             value="{{ old('data_nascimento', optional($utilizador->data_nascimento)->format('d-m-Y')) }}">
    </div>

    <div class="col-md-6">
      <label class="form-label small">Morada</label>
      <input type="text" name="morada" class="form-control" value="{{ old('morada',$utilizador->morada) }}">
    </div>
    <div class="col-md-2">
      <label class="form-label small">C.P.</label>
      <input type="text" name="codigo_postal" class="form-control" value="{{ old('codigo_postal',$utilizador->codigo_postal) }}">
    </div>
    <div class="col-md-4">
      <label class="form-label small">Localidade</label>
      <input type="text" name="localidade" class="form-control" value="{{ old('localidade',$utilizador->localidade) }}">
    </div>

    <div class="col-md-3">
      <label class="form-label small">Empresa</label>
      <input type="text" name="empresa" class="form-control" value="{{ old('empresa',$utilizador->empresa) }}">
    </div>
    <div class="col-md-4">
      <label class="form-label small">Escola</label>
      <input type="text" name="escola" class="form-control" value="{{ old('escola',$utilizador->escola) }}">
    </div>
    <div class="col-md-3">
      <label class="form-label small">Estado Civil</label>
      <select name="estado_civil" class="form-select">
        @foreach(['Solteiro','Casado','Divorciado'] as $opt)
          <option value="{{ $opt }}" @selected(old('estado_civil',$utilizador->estado_civil)==$opt)>{{ $opt }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-3">
      <label class="form-label small">Ocupação</label>
      <input type="text" name="ocupacao" class="form-control" value="{{ old('ocupacao',$utilizador->ocupacao) }}">
    </div>
    <div class="col-md-3">
      <label class="form-label small">Nacionalidade</label>
      <input type="text" name="nacionalidade" class="form-control" value="{{ old('nacionalidade',$utilizador->nacionalidade) }}">
    </div>
    <div class="col-md-3">
      <label class="form-label small">Sexo</label>
      <select name="sexo" class="form-select">
        <option value="0" @selected(old('sexo',$utilizador->sexo)==0)>Feminino</option>
        <option value="1" @selected(old('sexo',$utilizador->sexo)==1)>Masculino</option>
        <option value="2" @selected(old('sexo',$utilizador->sexo)==2)>Outro</option>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label small">Nº Irmãos</label>
      <input type="number" name="numero_irmaos" class="form-control" value="{{ old('numero_irmaos',$utilizador->numero_irmaos) }}">
    </div>

    <div class="col-md-3">
      <label class="form-label small">Contacto</label>
      <input type="text" name="contacto" class="form-control" value="{{ old('contacto',$utilizador->contacto) }}">
    </div>
    <div class="col-md-4">
      <label class="form-label small">Email</label>
      <input type="email" name="email" class="form-control" value="{{ old('email',$utilizador->email) }}">
    </div>

    {{-- Escalões (sync escalas) --}}
    <div class="col-md-5">
      <label class="form-label small">Escalões</label>
      <select name="escalao_ids[]" class="form-select" multiple>
        @foreach($escaloes as $esc)
          <option value="{{ $esc->id }}" @selected(collect(old('escalao_ids', $utilizador->escaloes->pluck('id')->all()))->contains($esc->id))>
            {{ $esc->nome }}
          </option>
        @endforeach
      </select>
      <small class="text-muted">Ctrl/Cmd + clique para múltiplos.</small>
    </div>

    {{-- Tipos de Utilizador (sync tipos) --}}
    <div class="col-md-7">
      <label class="form-label small">Tipos de Utilizador</label>
      <select name="tipo_user[]" class="form-select" multiple>
        @foreach(($tiposDisponiveis ?? []) as $t)
          <option value="{{ $t->id }}" @selected(collect(old('tipo_user', $utilizador->tipoUsers->pluck('id')->all()))->contains($t->id))>
            {{ $t->nome }}
          </option>
        @endforeach
      </select>
    </div>

    {{-- Encarregados/Educandos --}}
    <div class="col-md-6">
      <label class="form-label small">Encarregados</label>
      <select name="encarregados[]" class="form-select" multiple>
        @foreach(($encarregadosDisponiveis ?? []) as $p)
          <option value="{{ $p->id }}" @selected(in_array($p->id, $encarregadosSelecionados ?? []))>
            {{ $p->name }} ({{ $p->email }})
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label small">Educandos</label>
      <select name="educandos[]" class="form-select" multiple>
        @foreach(($educandosDisponiveis ?? []) as $p)
          <option value="{{ $p->id }}" @selected(in_array($p->id, $educandosSelecionados ?? []))>
            {{ $p->name }} ({{ $p->email }})
          </option>
        @endforeach
      </select>
    </div>
  </div>
</div>
