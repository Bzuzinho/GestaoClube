@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard de Administração</h1>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total de Atletas</h5>
                    <p class="display-6">{{ $totalAtletas }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Ativos</h5>
                    <p class="display-6">{{ $ativos }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Inativos</h5>
                    <p class="display-6">{{ $inativos }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Mensalidades Pendentes</h5>
                    <p class="display-6">{{ number_format($mensalidadesPendentes, 2, ',', '.') }} €</p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-3">Distribuição por Escalão</h4>
    <ul class="list-group">
        @forelse($escaloes as $esc)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $esc->escalao }}
                <span class="badge bg-secondary rounded-pill">{{ $esc->total }}</span>
            </li>
        @empty
            <li class="list-group-item">Nenhum escalão registado.</li>
        @endforelse
    </ul>
</div>
@endsection
