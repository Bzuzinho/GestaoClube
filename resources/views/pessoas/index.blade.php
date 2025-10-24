@extends('layouts.app')

@section('title','Gestão de Utilizadores')
@section('page_title','Gestão de Utilizadores')
@section('page_subtitle','Visão geral dos membros')

@section('page_actions')
  <a href="{{ route('utilizadores.create') }}" data-modal="{{ route('utilizadores.create') }}"
     class="btn btn-primary btn-compact">+ Novo Utilizador</a>
@endsection

@section('content')
  <x-ui.card>
    <x-slot name="title">Lista de Utilizadores</x-slot>

    <x-ui.table :headers="['Nome','Email','Estado','Escalão','Tipo(s) de Membro','Ações']">
      @forelse($utilizadores as $u)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:8px">
              <div class="avatar" aria-hidden="true">{{ strtoupper(mb_substr($u->name,0,1)) }}</div>
              <div>
                <div style="font-weight:600">{{ $u->name }}</div>
                @if($u->username)
                  <div class="u-muted" style="font-size:12px">{{ '@'.$u->username }}</div>
                @endif
              </div>
            </div>
          </td>

          <td>{{ $u->email }}</td>

          <td>
            @if($u->ativo ?? true)
              <x-ui.badge type="success">Ativo</x-ui.badge>
            @else
              <x-ui.badge type="danger">Inativo</x-ui.badge>
            @endif
          </td>

          <td>{{ $u->escalao?->nome ?? '—' }}</td>

          <td>
            @php $tipos = collect($u->tipos ?? [])->pluck('nome')->all(); @endphp
            @if(!empty($tipos))
              <div class="tags">
                @foreach($tipos as $t)
                  <span class="tag">{{ $t }}</span>
                @endforeach
              </div>
            @else
              —
            @endif
          </td>

          <td class="tbl-actions">
            <a class="btn btn-compact" href="{{ route('utilizadores.edit',$u->id) }}">Editar</a>
            <a class="btn btn-compact btn-danger"
               href="{{ route('utilizadores.destroy',$u->id) }}"
               onclick="event.preventDefault(); if(confirm('Eliminar este utilizador?')) document.getElementById('del-{{ $u->id }}').submit();">
              Eliminar
            </a>
            <form id="del-{{ $u->id }}" action="{{ route('utilizadores.destroy',$u->id) }}" method="POST" class="d-none">
              @csrf @method('DELETE')
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6">
            <x-empty-state>Sem utilizadores. Clique em “Novo Utilizador”.</x-empty-state>
          </td>
        </tr>
      @endforelse
    </x-ui.table>

    @if(method_exists($utilizadores,'links'))
      <div style="margin-top:12px">{{ $utilizadores->links() }}</div>
    @endif
  </x-ui.card>
@endsection
