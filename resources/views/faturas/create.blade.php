@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h4>Criar Nova Fatura</h4>

  <form method="POST" action="{{ route('faturas.store') }}">
    @csrf

    <div class="mb-3">
      <label for="user_id" class="form-label">Utilizador</label>
      <select name="user_id" id="user_id" class="form-select" required>
        <option value="">Selecionar</option>
        @foreach($utilizadores as $u)
          <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label for="mes" class="form-label">Mês</label>
      <input type="month" name="mes" id="mes" class="form-control" required>
    </div>

    <button class="btn btn-primary">Criar Fatura</button>
    <a href="{{ route('faturas.index') }}" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
@endsection
