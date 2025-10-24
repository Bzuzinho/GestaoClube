@extends('layouts.app')

@section('content')
<div class="row g-4">

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted">Utilizadores Ativos</h6>
                <h2 class="fw-bold">{{ $totalUtilizadores }}</h2>
                <a href="{{ route('admin.membros.index') }}" class="btn btn-sm btn-primary mt-3">Ver Utilizadores</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted">Atletas Ativos</h6>
                <h2 class="fw-bold">{{ $totalAtletas }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted">Mensalidades Pendentes</h6>
                <h2 class="fw-bold text-success">€{{ number_format($mensalidadesPendentes, 2, ',', '.') }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-3">Distribuição por Escalões</h6>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Escalão</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($escaloes as $escalao)
                            <tr>
                                <td>{{ $escalao->escalao }}</td>
                                <td>{{ $escalao->total }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-muted">Sem dados</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-3">Aniversários do Mês</h6>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aniversariosDia as $aniversario)
                            @php
                                $data = \Carbon\Carbon::parse($aniversario->data_nascimento);
                                $ehHoje = $data->format('d-m') === now()->format('d-m');
                            @endphp
                            <tr @if($ehHoje) class="bg-warning-subtle fw-bold" @endif>
                                <td>{{ $aniversario->name }} {{ $aniversario->apelido ?? '' }}</td>
                                <td>{{ $data->format('d/m/Y') }} @if($ehHoje) 🎉 @endif</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-muted">Sem aniversários</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted mb-3">Atividades - Próximos 120 Dias - EM DESENVOLVIMENTO</h6>
                <ul class="list-unstyled">
                    @forelse($atividades as $atividade)
                        <li class="border-bottom py-1">
                            {{ $atividade->nome }} - {{ \Carbon\Carbon::parse($atividade->data)->format('d/m/Y') }}
                        </li>
                    @empty
                        <li class="text-muted">Sem atividades</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body text-center">
                <h6 class="text-muted">Nº de Atividades - EM DESENVOLVIMENTO</h6>
                <h3 class="fw-bold">{{ $totalAtividades }}</h3>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted">Encomendas Pendentes - EM DESENVOLVIMENTO</h6>
                <h3 class="fw-bold">{{ $totalEncomendas }}</h3>
            </div>
        </div>
    </div>

</div>
@endsection

<form>
  <div class="form-grid">
    <div class="form-col-6">
      <label class="label">Nome</label>
      <input class="input" type="text">
    </div>
    <div class="form-col-6">
      <label class="label">Email</label>
      <input class="input" type="email">
    </div>
    <div class="form-col-12">
      <label class="label">Observações</label>
      <textarea class="textarea" rows="4"></textarea>
    </div>
  </div>
  <div style="margin-top:14px">
    <button class="btn btn-primary">Guardar</button>
  </div>
</form>