@extends('layouts.app')

@section('title','Gestão de Utilizadores')
@section('page_title','Gestão de Utilizadores')
@section('page_subtitle','Visão geral dos membros')

@section('page_actions')
  <a href="{{ route('utilizadores.create') }}"
     data-modal="{{ route('utilizadores.create') }}"
     class="btn btn-primary btn-compact">+ Novo Utilizador</a>
@endsection

@section('content')
  <div class="table-wrap">
    <div class="table-head">
      <div class="table-title">Lista de Utilizadores</div>
    </div>

    <table class="table table-compact table-lines">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Email</th>
          <th>Estado</th>
          <th>Escalão</th>
          <th>Tipo(s) de Membro</th>
          <th class="th-actions">Ações</th>
        </tr>
      </thead>

      <tbody>
        @forelse ($utilizadores as $u)
          @php
            // Foto de perfil (storage -> URL pública)
            $foto = $u->profile_photo_path ?? null;
            if ($foto) {
              $isUrl = \Illuminate\Support\Str::startsWith($foto, ['http://','https://']);
              $foto  = $isUrl ? $foto : asset('storage/'.$foto);
            }

            // Escalões
            $listaEscaloes = $u->escaloes?->pluck('nome')->filter()->join(', ') ?: '—';
          @endphp

          <tr>
            <td>
              <div class="cell-user">
                @if ($foto)
                  <img src="{{ $foto }}" alt="" class="avatar-img" referrerpolicy="no-referrer">
                @else
                  <div class="avatar-badge">{{ mb_strtoupper(mb_substr($u->name,0,1)) }}</div>
                @endif
                <div class="user-meta">
                  <div class="user-name">{{ $u->name }}</div>
                  @if ($u->username)
                    <div class="user-sub">@{{ $u->username }}</div>
                  @endif
                </div>
              </div>
            </td>

            <td>{{ $u->email }}</td>

            <td>
              @if (($u->estado ?? 1) == 1)
                <span class="badge badge--success">{{ $u->estado_texto ?? 'Ativo' }}</span>
              @else
                <span class="badge badge--danger">{{ $u->estado_texto ?? 'Inativo' }}</span>
              @endif
            </td>

            <td>{{ $listaEscaloes }}</td>

            <td>
              @if ($u->tipoUsers && $u->tipoUsers->isNotEmpty())
                <div class="tags">
                  @foreach ($u->tipoUsers as $t)
                    <span class="tag">{{ $t->nome }}</span>
                  @endforeach
                </div>
              @else
                —
              @endif
            </td>

            <td class="td-actions">
              {{-- Ver ficha --}}
              <a class="btn-icon" title="Ver ficha"
                 href="{{ route('utilizadores.show', $u) }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
              </a>

              {{-- Eliminar --}}
              <a href="{{ route('utilizadores.destroy', $u) }}"
                 class="btn-icon btn-icon--danger"
                 title="Eliminar" aria-label="Eliminar"
                 onclick="event.preventDefault(); if(confirm('Eliminar este utilizador?')) document.getElementById('del-{{ $u->id }}').submit();">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
                  <path d="M6 7h12M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2M19 7l-1 13a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                  <path d="M10 11v6M14 11v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
              </a>
              <form id="del-{{ $u->id }}" action="{{ route('utilizadores.destroy', $u) }}" method="POST" class="d-none">
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
      </tbody>
    </table>

    @if (method_exists($utilizadores, 'links'))
      <div style="margin-top:12px">
        {{ $utilizadores->links() }}
      </div>
    @endif
  </div>
@endsection
