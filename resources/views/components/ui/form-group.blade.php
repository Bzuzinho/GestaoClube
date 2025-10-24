@props(['label' => '', 'for' => null, 'hint' => null])
<label for="{{ $for }}" class="form-label">{{ $label }}</label>
<div class="form-control-wrap">
{{ $slot }}
@if($hint)<small class="hint">{{ $hint }}</small>@endif
</div>