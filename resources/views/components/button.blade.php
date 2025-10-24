
@props(['variant' => 'primary', 'type' => 'button'])
@php
  $map = [
    'primary' => 'btn-primary',
    'secondary' => 'btn-secondary',
    'danger' => 'btn-danger',
    'ghost' => 'btn-ghost',
  ];
  $classes = $map[$variant] ?? $map['primary'];
@endphp
<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
  {{ $slot }}
</button>
