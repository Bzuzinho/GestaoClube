@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Lista de Eventos</h4>

    {{-- Botão Novo Evento --}}
    @can('criar_eventos')
        <div class="mb-3">
            <a href="{{ route('eventos.create') }}" class="btn btn-success btn-compact">
                <i class="bi bi-plus-circle"></i> Novo Evento
            </a>
        </div>
    @endcan

    {{-- Filtros --}}
    <form method="GET" action="{{ route('eventos.index') }}" class="row g-3 align-items-end mb-4">
        <div class="col-md-4 col-lg-3">
            <label class="form-label small mb-1">Título</label>
            <input type="text" name="titulo" value="{{ request('titulo') }}" class="form-control form-control-sm" placeholder="Pesquisar por título...">
        </div>

        <div class="col-md-3 col-lg-2">
            <label class="form-label small mb-1">Tipo</label>
            <select name="tipo" class="form-select form-select-sm">
                <option value="">Todos os Tipos</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}" @selected((string)request('tipo')===(string)$tipo->id)>{{ $tipo->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 col-lg-2">
            <label class="form-label small mb-1">Escalão</label>
            <select name="escalao" class="form-select form-select-sm">
                <option value="">Todos os Escalões</option>
                @foreach($escaloes as $e)
                    <option value="{{ $e->id }}" @selected((string)request('escalao')===(string)$e->id)>{{ $e->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2 col-lg-2">
            <label class="form-label small mb-1">De</label>
            <input type="date" name="data_de" value="{{ request('data_de') }}" class="form-control form-control-sm">
        </div>

        <div class="col-md-2 col-lg-2">
            <label class="form-label small mb-1">Até</label>
            <input type="date" name="data_ate" value="{{ request('data_ate') }}" class="form-control form-control-sm">
        </div>

        <div class="col-md-1 col-lg-1 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-compact w-100" title="Filtrar">
                <i class="bi bi-search"></i>
            </button>
        </div>

        <div class="col-md-2 col-lg-1">
            <a href="{{ route('eventos.index') }}" class="btn btn-secondary btn-compact w-100">Limpar</a>
        </div>
    </form>

    {{-- Tabela de Eventos --}}
    @if($eventos->count())
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-compact align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Tipo</th>
                        <th>Início</th>
                        <th>Fim</th>
                        <th>Local</th>
                        <th>Escalões</th>
                        <th>Visibilidade</th>
                        <th>Convocatória</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eventos as $evento)
                        <tr>
                            <td>{{ $evento->titulo }}</td>
                            <td>{{ $evento->descricao ?? '—' }}</td>
                            <td>{{ $evento->tipo->nome ?? '—' }}</td>
                            <td>{{ \Carbon\Carbon::parse($evento->data_inicio)->format('d/m/Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($evento->data_fim)->format('d/m/Y H:i') }}</td>
                            <td>{{ $evento->local ?? '—' }}</td>

                            <td>
                                @if($evento->relationLoaded('escaloes') && $evento->escaloes->isNotEmpty())
                                    {{ $evento->escaloes->pluck('nome')->join(', ') }}
                                @else
                                    —
                                @endif
                            </td>

                            <td>{{ ucfirst($evento->visibilidade) }}</td>

                            <td>
                                @if($evento->convocatoria)
                                    @if(Route::has('convocatorias.show'))
                                        <a href="{{ route('convocatorias.show', $evento->convocatoria) }}">
                                            {{ $evento->convocatoria->titulo }}
                                        </a>
                                    @else
                                        {{ $evento->convocatoria->titulo }}
                                    @endif
                                @elseif($evento->convocatoria_path)
                                    <a href="{{ asset('storage/'.$evento->convocatoria_path) }}" target="_blank">Ficheiro</a>
                                @else
                                    —
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">

                                <a href="{{ route('eventos.show', $evento) }}" class="btn btn-light btn-compact">
                                    <i class="bi bi-eye"></i>
                                    </a>
                                   @can('apagar_eventos')
                                        <form action="{{ route('eventos.destroy', $evento) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Apagar o evento &quot;{{ $evento->titulo }}&quot;? Esta ação é irreversível.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-compact">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        <div class="mt-3">
            {{ $eventos->withQueryString()->links() }}
        </div>
    @else
        <div class="alert alert-info mb-0">Sem resultados para os filtros aplicados.</div>
    @endif
</div>
@endsection
