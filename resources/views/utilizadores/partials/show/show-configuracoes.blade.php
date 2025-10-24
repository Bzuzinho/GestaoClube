<div class="grid grid-cols-12 gap-4 p-4">

    <div class="col-span-6">
        <strong>Tipo de Utilizador (Roles)</strong>:
        @if(!empty($utilizador->tipoMembros) && $utilizador->tipoMembros->count())
            {{ $utilizador->tipoMembros->pluck('nome')->implode(', ') }}
        @else
            -
        @endif
    </div>

    <div class="col-span-6">
        <strong>Estado da Conta</strong>: {{ $utilizador->estado ?? '-' }}
    </div>

    <div class="col-span-6">
        <strong>RGPD Aceite?</strong>: {{ $utilizador->rgpd ? 'Sim' : 'Não' }}
    </div>

    <div class="col-span-6">
        <strong>Consentimento de Imagem</strong>: {{ $utilizador->consentimento_imagem ? 'Sim' : 'Não' }}
    </div>

    <div class="col-span-6">
        <strong>Declaração de Transporte</strong>: {{ $utilizador->declaracao_transporte ? 'Sim' : 'Não' }}
    </div>

    <div class="col-span-6">
        <strong>Afiliação</strong>: {{ $utilizador->afiliacao ? 'Sim' : 'Não' }}
    </div>

    <div class="col-span-6">
        @if(!empty($utilizador->arquivo_rgpd))
            <a href="{{ asset('storage/' . $utilizador->arquivo_rgpd) }}" target="_blank" class="text-blue-600 underline">Ver RGPD</a>
        @endif
    </div>

    <div class="col-span-6">
        @if(!empty($utilizador->arquivo_consentimento))
            <a href="{{ asset('storage/' . $utilizador->arquivo_consentimento) }}" target="_blank" class="text-blue-600 underline">Ver Consentimento</a>
        @endif
    </div>

    <div class="col-span-6">
        @if(!empty($utilizador->arquivo_transporte))
            <a href="{{ asset('storage/' . $utilizador->arquivo_transporte) }}" target="_blank" class="text-blue-600 underline">Ver Transporte</a>
        @endif
    </div>

    <div class="col-span-6">
        @if(!empty($utilizador->arquivo_afil))
            <a href="{{ asset('storage/' . $utilizador->arquivo_afil) }}" target="_blank" class="text-blue-600 underline">Ver Afiliação</a>
        @endif
    </div>

</div>
