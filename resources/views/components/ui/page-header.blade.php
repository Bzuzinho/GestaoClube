@props(['title' => '', 'subtitle' => null])
<div class="page-header">
<div>
<h2 class="page-title">{{ $title }}</h2>
@if($subtitle)<p class="page-subtitle">{{ $subtitle }}</p>@endif
</div>
<div class="page-actions">{{ $slot }}</div>
</div>