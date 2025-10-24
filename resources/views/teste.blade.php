<!doctype html>
<html lang="pt">
<head>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 p-8">
  <div class="card max-w-xl mx-auto">
    <h1 class="text-xl font-semibold mb-3">Teste Tailwind</h1>
    <p>Se este cartão tem cantos arredondados e sombra, Tailwind está a funcionar.</p>
    <div class="mt-4 flex gap-2">
      <button class="btn-primary">Botão Primário</button>
      <button class="btn-ghost">Ghost</button>
    </div>
  </div>
</body>
</html>
