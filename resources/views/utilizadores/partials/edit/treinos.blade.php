<h6 class="mb-2">Treinos</h6>
<table class="table table-sm table-bordered align-middle" style="font-size:13px;">
  <thead>
    <tr>
      <th class="bg-light text-uppercase">Data</th>
      <th class="bg-light text-uppercase">Título</th>
      <th class="bg-light text-uppercase">Local</th>
      <th class="bg-light text-uppercase">Estado</th>
    </tr>
  </thead>
  <tbody>
    @forelse(($treinos ?? []) as $t)
      <tr>
        <td>{{ \Carbon\Carbon::parse($t->data)->format('d/m/Y') }}</td>
        <td>{{ $t->titulo ?? '-' }}</td>
        <td>{{ $t->local ?? '—' }}</td>
        <td>
          <span class="badge {{ ($t->estado ?? '') === 'realizado' ? 'text-bg-success' : 'text-bg-secondary' }}">
            {{ ucfirst($t->estado ?? '—') }}
          </span>
        </td>
      </tr>
    @empty
      <tr><td colspan="4" class="text-center text-muted">Sem treinos registados.</td></tr>
    @endforelse
  </tbody>
</table>
