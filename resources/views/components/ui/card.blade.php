<div {{ $attributes->merge(['class' => 'card']) }}>
@isset($title)
<div class="card__header">
<h3>{{ $title }}</h3>
{{ $actions ?? '' }}
</div>
@endisset
<div class="card__body">{{ $slot }}</div>
</div>