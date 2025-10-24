@component('mail::message')
# Recuperação de Palavra-passe

Recebemos um pedido para redefinir a tua palavra-passe no site do **Benedita Sport Club Natação**.

Clica no botão abaixo para definires uma nova palavra-passe:

@component('mail::button', ['url' => $url])
Redefinir Palavra-passe
@endcomponent

Se não fizeste este pedido, podes ignorar este email — nenhuma alteração será feita.

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
