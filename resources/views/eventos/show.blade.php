@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h4 class="mb-4">Detalhe do Evento</h4>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="row g-3">

        {{-- Título / Datas --}}
        <div class="col-md-6">
          <label class="form-label">Título</label>
          <input type="text" class="form-control" value="{{ $evento->titulo }}" readonly>
        </div>

        <div class="col-md-3">
          <label class="form-label">Data Início</label>
          <input type="text" class="form-control"
                 value="{{ optional($evento->data_inicio)->format('d/m/Y H:i') }}" readonly>
        </div>

        <div class="col-md-3">
          <label class="form-label">Data Fim</label>
          <input type="text" class="form-control"
                 value="{{ optional($evento->data_fim)->format('d/m/Y H:i') }}" readonly>
        </div>

        {{-- Local / Tipo / Visibilidade --}}
        <div class="col-md-6">
          <label class="form-label">Local</label>
          <input type="text" class="form-control" value="{{ $evento->local ?? '—' }}" readonly>
        </div>

        <div class="col-md-3">
          <label class="form-label">Tipo de Evento</label>
          <input type="text" class="form-control" value="{{ $evento->tipo->nome ?? '—' }}" readonly>
        </div>

        <div class="col-md-3">
          <label class="form-label">Visibilidade</label>
          <input type="text" class="form-control" value="{{ ucfirst($evento->visibilidade ?? '—') }}" readonly>
        </div>

        {{-- Escalões --}}
        <div class="mb-3">
          <label class="form-label">Escalões</label>
          <input type="text" class="form-control"
                 value="{{ $evento->escaloes->isNotEmpty() ? $evento->escaloes->pluck('nome')->join(', ') : '—' }}"
                 readonly>
        </div>

        {{-- Descrição --}}
        <div class="col-md-12">
          <label class="form-label">Descrição</label>
          <textarea class="form-control" rows="3" readonly>{{ $evento->descricao ?? '—' }}</textarea>
        </div>

        {{-- Transporte --}}
        <div class="col-md-3">
          <label class="form-label">Transporte disponível</label>
          <input type="text" class="form-control"
                 value="{{ $evento->tem_transporte ? 'Sim' : 'Não' }}" readonly>
        </div>

        <div class="col-md-9">
          <label class="form-label">Descrição do Transporte</label>
          <input type="text" class="form-control"
                 value="{{ $evento->transporte_descricao ?? '—' }}" readonly>
        </div>

        {{-- Convocatória (registo) --}}
        <div class="col-md-6">
          <label class="form-label">Convocatória</label>
          <input type="text" class="form-control"
                 value="{{ $evento->convocatoria->titulo ?? '—' }}" readonly>
        </div>

        {{-- Local/Hora de partida --}}
        <div class="col-md-6">
          <label class="form-label">Local de Partida</label>
          <input type="text" class="form-control"
                 value="{{ $evento->local_partida ?? '—' }}" readonly>
        </div>

        <div class="col-md-3">
          <label class="form-label">Hora de Partida</label>
          @php
            $hora = $evento->hora_partida;
            if ($hora && strlen($hora) >= 5) $hora = substr($hora, 0, 5); // HH:MM
          @endphp
          <input type="text" class="form-control" value="{{ $hora ?? '—' }}" readonly>
        </div>

        {{-- Ficheiros (mostrar sempre os campos) --}}
        <div class="col-md-6">
          <label class="form-label">Ficheiro da Convocatória (PDF/DOC)</label>
          @if($evento->convocatoria_path)
            <div class="d-flex align-items-center gap-2">
              <a class="btn btn-outline-secondary btn-compact"
                 href="{{ asset('storage/'.$evento->convocatoria_path) }}" target="_blank" rel="noopener">
                <i class="bi bi-paperclip"></i> Abrir ficheiro
              </a>
              <span class="text-muted small">{{ basename($evento->convocatoria_path) }}</span>
            </div>
          @else
            <input type="text" class="form-control" value="— Sem ficheiro —" readonly>
          @endif
        </div>

        <div class="col-md-6">
          <label class="form-label">Ficheiro Regulamento (PDF/DOC)</label>
          @if($evento->regulamento_path)
            <div class="d-flex align-items-center gap-2">
              <a class="btn btn-outline-secondary btn-compact"
                 href="{{ asset('storage/'.$evento->regulamento_path) }}" target="_blank" rel="noopener">
                <i class="bi bi-paperclip"></i> Abrir ficheiro
              </a>
              <span class="text-muted small">{{ basename($evento->regulamento_path) }}</span>
            </div>
          @else
            <input type="text" class="form-control" value="— Sem ficheiro —" readonly>
          @endif
        </div>

        {{-- Observações --}}
        <div class="col-12">
          <label class="form-label">Observações</label>
          <textarea class="form-control" rows="3" readonly>{{ $evento->observacoes ?? '—' }}</textarea>
        </div>

      </div> {{-- /row --}}

      <div class="d-flex justify-content-end gap-2 mt-4">
        @can('editar_eventos')
          <a href="{{ route('eventos.edit', $evento) }}" class="btn btn-primary btn-compact">
            Editar
          </a>
        @endcan

        @php
            $prev    = url()->previous();
            $current = url()->current();

            // se houver referer válido, usa esse; senão decide por permissão
            $backUrl = ($prev && $prev !== $current)
                ? $prev
                : (auth()->check() && auth()->user()->can('ver_eventos')
                    ? route('eventos.index')                 // lista interna
                    : route('eventos.publicos'));            // calendário público
        @endphp
        <a href="{{ $backUrl }}" class="btn btn-outline-secondary btn-compact">
            Voltar
        </a>
      </div>

    </div>
  </div>
</div>
@endsection
