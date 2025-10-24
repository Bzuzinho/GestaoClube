<div class="grid grid-cols-12 gap-4 p-4">

    <div class="col-span-6">
        <strong>Tipo de Mensalidade</strong>: {{ $dadosFinanceiros->tipo_mensalidade ?? '-' }}
    </div>

    <div class="col-span-6">
        <strong>Valor da Mensalidade (€)</strong>: {{ $dadosFinanceiros->mensalidade ?? '-' }}
    </div>

    <div class="col-span-6">
        <strong>Conta Corrente (€)</strong>: {{ $dadosFinanceiros->conta_corrente ?? '-' }}
    </div>

    <div class="col-span-12">
        <strong>Relação de Quotas</strong>: <span class="italic text-gray-500">Gestão de faturas será implementada numa página própria.</span>
    </div>

</div>
