{{-- Flash messages e erros globais --}}
@if (session('success'))
  <div class="alert alert--success">{{ session('success') }}</div>
@endif

@if (session('error'))
  <div class="alert alert--error">{{ session('error') }}</div>
@endif

@if (session('warning'))
  <div class="alert alert--warning">{{ session('warning') }}</div>
@endif

@if ($errors->any())
  <div class="alert alert--error">
    <strong>Ocorreram alguns erros:</strong>
    <ul style="margin:6px 0 0 16px">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
