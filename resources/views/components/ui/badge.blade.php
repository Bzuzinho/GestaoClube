@props(['type' => 'neutral'])
<span {{ $attributes->merge(['class' => 'badge badge--'.$type]) }}>{{ $slot }}</span>