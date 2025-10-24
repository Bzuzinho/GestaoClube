<h6 class="mb-2">Resultados</h6>
<table class="table table-sm table-bordered align-middle" style="font-size:13px;">
  <thead>
    <tr>
      <th class="bg-light text-uppercase">Data</th>
      <th class="bg-light text-uppercase">Prova</th>
      <th class="bg-light text-uppercase">Tempo</th>
      <th class="bg-light text-uppercase">Observações</th>
    </tr>
  </thead>
  <tbody>
    @forelse(($resultados ?? []) as $r)
      <tr>
        <td>{{ \Carbon\Carbon::parse($r->data)->format('d/m/Y') }}</td>
        <td>{{ $r->prova ?? '-' }}</td>
        <td>{{ $r->tempo ?? '-' }}</td>
        <td>{{ $r->observacoes ?? '—' }}</td>
      </tr>
    @empty
      <tr><td colspan="4" class="text-center text-muted">Sem resultados registados.</td></tr>
    @endforelse
  </tbody>
</table>
