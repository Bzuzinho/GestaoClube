@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Editar Utilizador</h4>

    <form method="POST" action="{{ route('utilizadores.update', $utilizador->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <ul class="nav nav-tabs mb-3" id="utilizadorTab" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#dados-pessoais">Dados Pessoais</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#dados-desportivos">Dados Desportivos</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#financeiro">Financeiro</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#config">Configuração</a></li>
        </ul>

        <div class="tab-content">
            <!-- Dados Pessoais -->
            <div class="tab-pane fade show active" id="dados-pessoais">
                @include('utilizadores.partials.edit.edit-dados-pessoais', [
                    'utilizador' => $utilizador,
                    'encarregadosDisponiveis' => $encarregadosDisponiveis ?? [],
                    'encarregadosSelecionados' => $encarregadosSelecionados ?? [],
                    'educandosDisponiveis' => $educandosDisponiveis ?? [],
                    'educandosSelecionados' => $educandosSelecionados ?? [],
                ])
            </div>

            <!-- Dados Desportivos -->
            <div class="tab-pane fade" id="dados-desportivos">
                @include('utilizadores.partials.edit.edit-dados-pessoais', [
                    'encarregadosDisponiveis' => $encarregadosDisponiveis,
                    'encarregadosSelecionados' => $encarregadosSelecionados,
                    'educandosDisponiveis' => $educandosDisponiveis,
                    'educandosSelecionados' => $educandosSelecionados,
                    
                ])
            </div>

            <!-- Financeiro -->
            <div class="tab-pane fade" id="financeiro">
                @include('utilizadores.partials.edit.edit-dados-financeiros')
            </div>

            <!-- Configuração -->
            <div class="tab-pane fade" id="config">
                @include('utilizadores.partials.edit.edit-configuracoes')
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Guardar Alterações</button>
        </div>
    </form>
</div>
@endsection
