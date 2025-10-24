@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Calendário de Eventos</h4>

    <div class="row mb-3">
        <div class="col-md-4">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle w-100" type="button" id="filtroView" data-bs-toggle="dropdown" aria-expanded="false">
                    Visualização
                </button>
                <ul class="dropdown-menu w-100" aria-labelledby="filtroView" id="viewSelector">
                    <li><a class="dropdown-item" href="#" data-view="dayGridMonth">Mês</a></li>
                    <li><a class="dropdown-item" href="#" data-view="timeGridWeek">Semana</a></li>
                    <li><a class="dropdown-item" href="#" data-view="dayGridDay">Dia</a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle w-100" type="button" id="filtroTipoEvento" data-bs-toggle="dropdown" aria-expanded="false">
                    Tipo de Evento
                </button>
                <!-- ALTERAÇÃO 1: id para podermos ligar o JS -->
                <ul class="dropdown-menu w-100" aria-labelledby="filtroTipoEvento" id="tipoSelector">
                    <li><a class="dropdown-item" href="#" data-tipo="todos">Todos</a></li>
                    @foreach ($tiposEvento as $tipo)
                        <li><a class="dropdown-item" href="#" data-tipo="{{ $tipo->id }}">{{ $tipo->nome }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle w-100" type="button" id="filtroEscalao" data-bs-toggle="dropdown" aria-expanded="false">
                    Escalão
                </button>
                <!-- ALTERAÇÃO 2: id para podermos ligar o JS -->
                <ul class="dropdown-menu w-100" aria-labelledby="filtroEscalao" id="escalaoSelector">
                    <li><a class="dropdown-item" href="#" data-escalao="todos">Todos</a></li>
                    @foreach ($escaloes as $escalao)
                        <li><a class="dropdown-item" href="#" data-escalao="{{ $escalao->id }}">{{ $escalao->nome }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="border rounded shadow-sm p-2 bg-white">
        <div id='calendar'></div>
    </div>
</div>
@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<style>
    #calendar {
        max-width: 100%;
        max-height: 75vh;
        font-size: 0.85rem;
        overflow-y: auto;
    }
    .fc-toolbar-title { font-size: 1rem; font-weight: 600; }
    .fc-header-toolbar { margin-bottom: 1rem; }
    .fc-daygrid-day-number { font-size: 0.75rem; }
    .fc .fc-button { padding: 0.25rem 0.5rem; font-size: 0.75rem; border-radius: 0.375rem; }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        // ALTERAÇÃO 3: fallback se a variável não vier (evita crash e “calendário desaparecido”)
        const eventos = @json($eventosFormatados ?? []);

        // Mapa de nomes para hidratar labels a partir da query string (se usado)
        const tiposMap    = @json($tiposEvento->pluck('nome','id'));
        const escaloesMap = @json($escaloes->pluck('nome','id'));

        const params = new URLSearchParams(location.search);
        const initialView = params.get('view') || 'dayGridMonth';

        const calendar = new FullCalendar.Calendar(calendarEl, {
            // ALTERAÇÃO 4: usar a view pedida na URL (ou Mês por defeito)
            initialView: initialView,
            locale: 'pt',
            height: 'auto',
            contentHeight: 'auto',
            aspectRatio: 1.2,
            headerToolbar: { left: 'prev,next today', center: 'title', right: '' },
            buttonText: { today: 'Hoje' },
            views: {
                dayGridMonth: { buttonText: 'Mês' },
                timeGridWeek: { buttonText: 'Semana' },
                dayGridDay: { buttonText: 'Dia' },
            },
            events: eventos,
            noEventsContent: 'Sem eventos para mostrar',
        });

        // Atualizar label do botão de view ao mudar
        const viewNames = { dayGridMonth: 'Mês', timeGridWeek: 'Semana', dayGridDay: 'Dia' };
        document.getElementById('filtroView').textContent = viewNames[initialView] || 'Mês';

        document.querySelectorAll('#viewSelector a').forEach(item => {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                calendar.changeView(this.dataset.view);
                document.getElementById('filtroView').textContent = this.textContent.trim();
                // (opcional) atualizar a URL sem recarregar:
                const url = new URL(location.href);
                url.searchParams.set('view', this.dataset.view);
                history.replaceState({}, '', url.toString());
            });
        });

        // --- ADICIONAR: escrever filtros na URL e recarregar ---
        function setParamAndReload(key, value) {
            const url = new URL(window.location.href);
            if (!value || value === 'todos') url.searchParams.delete(key);
            else url.searchParams.set(key, value);
            window.location.assign(url.toString());
        }

        // Tipo de evento → ?tipo=ID | remove se "todos"
        document.querySelectorAll('#tipoSelector a').forEach(a => {
            a.addEventListener('click', function(e){
            e.preventDefault();
            setParamAndReload('tipo', this.dataset.tipo);
            });
        });

        // Escalão → ?escalao=ID | remove se "todos"
        document.querySelectorAll('#escalaoSelector a').forEach(a => {
            a.addEventListener('click', function(e){
            e.preventDefault();
            setParamAndReload('escalao', this.dataset.escalao);
            });
        });

        // (Opcional) manter a view na URL sem recarregar
        document.querySelectorAll('#viewSelector a').forEach(a => {
            a.addEventListener('click', function(e){
            e.preventDefault();
            calendar.changeView(this.dataset.view);
            const url = new URL(location.href);
            url.searchParams.set('view', this.dataset.view);
            history.replaceState({}, '', url.toString());
            document.getElementById('filtroView').textContent = this.textContent.trim();
            });
        });

        // Apenas UI: ao clicar numa opção, o texto do botão passa a mostrar a escolha
        function wireDropdown(menuSelector, buttonId, datasetKey) {
            const btn = document.getElementById(buttonId);
            document.querySelectorAll(menuSelector + ' a').forEach(a => {
                a.addEventListener('click', function (e) {
                    e.preventDefault();
                    btn.textContent = this.textContent.trim();
                    document.querySelectorAll(menuSelector + ' a').forEach(x => x.classList.remove('active'));
                    this.classList.add('active');
                    // Nota: aqui não faço reload nem mexo na tua lógica de filtro — só UI.
                });
            });
        }
        wireDropdown('#tipoSelector', 'filtroTipoEvento', 'tipo');
        wireDropdown('#escalaoSelector', 'filtroEscalao', 'escalao');

        // Hidratar labels a partir da query string (se estiveres a filtrar por URL)
        (function hydrateFromQuery(){
            const tipoParam    = params.get('tipo');    // ex.: "todos" ou "12"
            const escalaoParam = params.get('escalao'); // ex.: "todos" ou "8"

            if (tipoParam) {
                const btn = document.getElementById('filtroTipoEvento');
                if (tipoParam === 'todos') btn.textContent = 'Todos';
                else if (tiposMap[tipoParam]) btn.textContent = tiposMap[tipoParam];
                document.querySelectorAll('#tipoSelector a').forEach(a => {
                    if (a.dataset.tipo === tipoParam) a.classList.add('active');
                });
            }

            if (escalaoParam) {
                const btn = document.getElementById('filtroEscalao');
                if (escalaoParam === 'todos') btn.textContent = 'Todos';
                else if (escaloesMap[escalaoParam]) btn.textContent = escaloesMap[escalaoParam];
                document.querySelectorAll('#escalaoSelector a').forEach(a => {
                    if (a.dataset.escalao === escalaoParam) a.classList.add('active');
                });
            }
        })();

        calendar.render();
    });
</script>
@endpush
