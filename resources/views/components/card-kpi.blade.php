
@props(['title' => '—', 'value' => '—', 'subtitle' => null])
<div class="card-kpi">
  <h3>{{ $title }}</h3>
  <p>{{ $value }}</p>
  @if($subtitle)
    <div class="sub">{{ $subtitle }}</div>
  @endif
</div>
