<div class="grid grid-cols-12 gap-4 p-4">

    <div class="col-span-6">
        <strong>Nº Federação</strong>: {{ $dadosDesportivos->numero_federacao ?? '-' }}
    </div>

    <div class="col-span-6">
        <strong>PMB</strong>: {{ $dadosDesportivos->pmb ?? '-' }}
    </div>

    <div class="col-span-6">
        <strong>Data de Inscrição</strong>: {{ $dadosDesportivos->data_inscricao ?? '-' }}
    </div>

    <div class="col-span-6">
        <strong>Escalão(s)</strong>:
        @if(!empty($utilizador->escaloes) && $utilizador->escaloes->count())
            {{ $utilizador->escaloes->pluck('nome')->implode(', ') }}
        @else
            Nenhum
        @endif
    </div>

    <div class="col-span-6">
        <strong>Atestado Médico</strong>:
        @if(!empty($dadosDesportivos))
            {{ $dadosDesportivos->atestado_medico ? 'Sim' : 'Não' }}
        @else
            -
        @endif
    </div>

    <div class="col-span-6">
        <strong>Data do Atestado</strong>: {{ $dadosDesportivos->data_atestado ?? '-' }}
    </div>

    <div class="col-span-12">
        <strong>Informações Médicas</strong>: {{ $dadosDesportivos->info_medica ?? '-' }}
    </div>

    <div class="col-span-12">
        @if(!empty($dadosDesportivos) && !empty($dadosDesportivos->arquivo_am))
            <a href="{{ asset('storage/' . $dadosDesportivos->arquivo_am) }}" target="_blank" class="text-blue-600 underline">Ver Atestado Médico</a>
        @endif
    </div>

</div>
