{{-- resources/views/components/ui/form/group.blade.php --}}
@props([
  'label' => null,
  'for'   => null,
  'help'  => null,
])

<div class="form-group" style="display:flex; flex-direction:column; gap:6px;">
  @if($label)
    <label @if($for) for="{{ $for }}" @endif class="form-label" style="font-weight:600;">
      {{ $label }}
    </label>
  @endif

  {{ $slot }}

  @if($help)
    <small class="form-help" style="color:#6b7280">{{ $help }}</small>
  @endif

  @error($for)
    <small class="form-error" style="color:#b91c1c">{{ $message }}</small>
  @enderror
</div>
