@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Criar Novo Evento</h4>

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

    <form action="{{ route('eventos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Título</label>
                <input type="text" name="titulo" class="form-control" value="{{ old('titulo') }}" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Data Início</label>
                <input type="datetime-local" name="data_inicio" class="form-control" value="{{ old('data_inicio') }}" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Data Fim</label>
                <input type="datetime-local" name="data_fim" class="form-control" value="{{ old('data_fim') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Local</label>
                <input type="text" name="local" class="form-control" value="{{ old('local') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Tipo de Evento</label>
                <select name="tipo_evento_id" class="form-select" required>
                    <option value="">-- Selecione --</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id }}" @selected(old('tipo_evento_id') == $tipo->id)>{{ $tipo->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Visibilidade</label>
                <select name="visibilidade" class="form-select">
                    <option value="privado"  @selected(old('visibilidade','restrito')==='privado')>Privado</option>
                    <option value="restrito" @selected(old('visibilidade','restrito')==='restrito')>Restrito</option>
                    <option value="publico"  @selected(old('visibilidade')==='publico')>Público</option>
                </select>
            </div>

            <div class="col-12">
                <label for="escaloes" class="form-label">Escalões</label>
                <select name="escaloes[]" id="escaloes" class="form-select" multiple>
                    @foreach($escaloes as $escalao)
                        <option value="{{ $escalao->id }}" @selected(collect(old('escaloes',[]))->contains($escalao->id))>
                            {{ $escalao->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label">Descrição</label>
                <textarea name="descricao" class="form-control" rows="3">{{ old('descricao') }}</textarea>
            </div>

            <div class="col-md-3">
                <label class="form-label d-block">Transporte disponível</label>
                <input type="hidden" name="tem_transporte" value="0">
                <input type="checkbox" name="tem_transporte" value="1" @checked(old('tem_transporte'))>
            </div>

            <div class="col-md-9">
                <label class="form-label">Descrição do Transporte</label>
                <input type="text" name="transporte_descricao" class="form-control" value="{{ old('transporte_descricao') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Convocatória</label>
                <select name="convocatoria_id" class="form-select">
                    <option value="">— Sem convocatória —</option>
                    @foreach($convocatorias as $c)
                        <option value="{{ $c->id }}" @selected(old('convocatoria_id') == $c->id)>{{ $c->titulo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Local de Partida</label>
                <input type="text" name="local_partida" class="form-control" value="{{ old('local_partida') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Hora de Partida</label>
                <input type="time" name="hora_partida" class="form-control" value="{{ old('hora_partida') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Ficheiro da Convocatória (PDF/DOC)</label>
                <input type="file" name="convocatoria_path" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Ficheiro Regulamento (PDF/DOC)</label>
                <input type="file" name="regulamento_path" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label">Observações</label>
                <textarea name="observacoes" class="form-control" rows="3">{{ old('observacoes') }}</textarea>
            </div>
        </div> {{-- <-- fechar row g-3 --}}

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('eventos.index') }}" class="btn btn-outline-secondary btn-compact">
                Cancelar
            </a>
            <button type="submit" class="btn btn-success btn-compact">
                Criar Evento
            </button>
        </div>
    </form>
</div>
@endsection
