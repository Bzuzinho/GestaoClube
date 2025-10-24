<h6 class="mb-2">Presenças</h6>
<table class="table table-sm table-bordered align-middle" style="font-size:13px;">
  <thead>
    <tr>
      <th class="bg-light text-uppercase">Data</th>
      <th class="bg-light text-uppercase">Atividade</th>
      <th class="bg-light text-uppercase">Estado</th>
    </tr>
  </thead>
  <tbody>
    @forelse(($presencas ?? []) as $p)
      <tr>
        <td>{{ \Carbon\Carbon::parse($p->data)->format('d/m/Y') }}</td>
        <td>{{ $p->titulo ?? '-' }}</td>
        <td>
          <span class="badge {{ $p->estado === 'presente' ? 'text-bg-success' : 'text-bg-secondary' }}">
            {{ ucfirst($p->estado ?? '—') }}
          </span>
        </td>
      </tr>
    @empty
      <tr><td colspan="3" class="text-center text-muted">Sem presenças.</td></tr>
    @endforelse
  </tbody>
</table>
