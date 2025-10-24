
@props(['type' => 'soft'])
@php
  $classes = match($type) {
    'soft-success' => 'badge badge-soft-success',
    'soft-warning' => 'badge badge-soft-warning',
    'soft-danger'  => 'badge badge-soft-danger',
    'soft-info'    => 'badge badge-soft-info',
    default        => 'badge badge-soft',
  };
@endphp
<span {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</span>
