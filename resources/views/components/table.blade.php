
@props(['headers' => []])
<div class="overflow-hidden rounded-xl shadow bg-white">
  <table class="table-compact">
    <thead>
      <tr>
        @foreach($headers as $h)
          <th>{{ $h }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      {{ $slot }}
    </tbody>
  </table>
</div>
