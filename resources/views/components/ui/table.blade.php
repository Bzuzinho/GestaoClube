@props(['headers' => []])
<div class="table-wrap">
<table class="table table-compact">
<thead>
<tr>
@foreach($headers as $h)
<th>{{ $h }}</th>
@endforeach
</tr>
</thead>
<tbody>{{ $slot }}</tbody>
</table>
</div>