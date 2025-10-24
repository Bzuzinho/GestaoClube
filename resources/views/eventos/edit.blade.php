@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Editar Evento</h4>

    {{-- Erros de validação --}}
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0 small">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @php
        // Re-hidratar escalões (mantém seleção após validação falhada)
        $selectedEscaloes = collect(old('escaloes', $evento->escaloes->pluck('id')->toArray()));

        // Re-hidratar hora_partida (BD TIME = 'HH:MM:SS' → input 'HH:MM')
        $horaPartidaValue = old('hora_partida');
        if (is_null($horaPartidaValue)) {
            $hp = $evento->hora_partida ?? null;
            if ($hp) {
                try {
                    $horaPartidaValue = (strlen($hp) === 5)
                        ? $hp
                        : \Carbon\Carbon::createFromFormat('H:i:s', $hp)->format('H:i');
                } catch (\Exception $e) {
                    $horaPartidaValue = $hp; // fallback
                }
            } else {
                $horaPartidaValue = '';
            }
        }
    @endphp

    <form action="{{ route('eventos.update', $evento) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Título</label>
                <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $evento->titulo) }}" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Data Início</label>
                <input type="datetime-local" name="data_inicio" class="form-control"
                    value="{{ old('data_inicio', \Carbon\Carbon::parse($evento->data_inicio)->format('Y-m-d\TH:i')) }}" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Data Fim</label>
                <input type="datetime-local" name="data_fim" class="form-control"
                    value="{{ old('data_fim', \Carbon\Carbon::parse($evento->data_fim)->format('Y-m-d\TH:i')) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Local</label>
                <input type="text" name="local" class="form-control" value="{{ old('local', $evento->local) }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Tipo de Evento</label>
                <select name="tipo_evento_id" class="form-select" required>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}"
                            @selected(old('tipo_evento_id', $evento->tipo_evento_id) == $tipo->id)>
                            {{ $tipo->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Visibilidade</label>
                <select name="visibilidade" class="form-select">
                    <option value="privado"  @selected(old('visibilidade', $evento->visibilidade) == 'privado')>Privado</option>
                    <option value="restrito" @selected(old('visibilidade', $evento->visibilidade) == 'restrito')>Restrito</option>
                    <option value="publico"  @selected(old('visibilidade', $evento->visibilidade) == 'publico')>Público</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="escaloes" class="form-label">Escalões</label>
                <select name="escaloes[]" id="escaloes" class="form-select" multiple>
                    @foreach($escaloes as $escalao)
                        <option value="{{ $escalao->id }}" @selected($selectedEscaloes->contains($escalao->id))>
                            {{ $escalao->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label">Descrição</label>
                <textarea name="descricao" class="form-control" rows="3">{{ old('descricao', $evento->descricao) }}</textarea>
            </div>

            <div class="col-md-3">
                <label class="form-label d-block">Transporte disponível</label>
                <input type="hidden" name="tem_transporte" value="0">
                <input type="checkbox" name="tem_transporte" value="1"
                    @checked(old('tem_transporte', $evento->tem_transporte ?? false))>
            </div>

            <div class="col-md-9">
                <label class="form-label">Descrição do Transporte</label>
                <input type="text" name="transporte_descricao" class="form-control"
                    value="{{ old('transporte_descricao', $evento->transporte_descricao ?? '') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Convocatória</label>
                <select name="convocatoria_id" class="form-select">
                    <option value="">— Sem convocatória —</option>
                    @foreach($convocatorias as $c)
                        <option value="{{ $c->id }}"
                            @selected(old('convocatoria_id', $evento->convocatoria_id ?? null) == $c->id)>
                            {{ $c->titulo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Local de Partida</label>
                <input type="text" name="local_partida" class="form-control"
                    value="{{ old('local_partida', $evento->local_partida ?? '') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Hora de Partida</label>
                <input type="time" name="hora_partida" class="form-control" value="{{ $horaPartidaValue }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Ficheiro da Convocatória (PDF/DOC)</label>
                <input type="file" name="convocatoria_path" class="form-control">
                @if(!empty($evento?->convocatoria_path))
                    <small><a href="{{ asset('storage/'.$evento->convocatoria_path) }}" target="_blank">Ver atual</a></small>
                @endif
            </div>

            <div class="col-md-6">
                <label class="form-label">Ficheiro Regulamento (PDF/DOC)</label>
                <input type="file" name="regulamento_path" class="form-control">
                @if(!empty($evento?->regulamento_path))
                    <small><a href="{{ asset('storage/'.$evento->regulamento_path) }}" target="_blank">Ver atual</a></small>
                @endif
            </div>

            <div class="col-12">
                <label class="form-label">Observações</label>
                <textarea name="observacoes" class="form-control" rows="3">{{ old('observacoes', $evento->observacoes ?? '') }}</textarea>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            {{-- voltar para a lista --}}
            <a href="{{ route('eventos.index') }}" class="btn btn-outline-secondary btn-compact">Cancelar</a>
            {{-- ou, se preferires, voltar para o show: --}}
            {{-- <a href="{{ route('eventos.show', $evento) }}" class="btn btn-outline-secondary btn-compact">Cancelar</a> --}}
            <button type="submit" class="btn btn-primary btn-compact">Gravar Alterações</button>
        </div>
    </form>
</div>
@endsection
