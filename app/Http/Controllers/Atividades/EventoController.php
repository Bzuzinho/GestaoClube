<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\EventoTipo;
use App\Models\Escalao;
use App\Models\Convocatoria;
use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventoController extends Controller
{
   public function index(Request $request)
    {
        $user = auth()->user();

        $query = Evento::with(['tipo','escaloes','convocatoria']) // <-- aqui
            ->orderByDesc('data_inicio')
            ->titulo($request->get('titulo'))
            ->tipo($request->get('tipo'))
            ->local($request->get('local'))
            ->dataDe($request->get('data_de'))
            ->dataAte($request->get('data_ate'))
            ->when($request->filled('escalao'), function ($q) use ($request) {
                $q->whereHas('escaloes', fn($qq) => $qq->where('escaloes.id', (int) $request->escalao));
            });

        // públicos se não for admin/treinador
        if (!($user && $user->hasRole(['administrador', 'treinador']))) {
            $query->where('visibilidade', 'publico');
        }

        $eventos = $query->paginate(15)->withQueryString();
        $tipos = EventoTipo::orderBy('nome')->get();
        $escaloes  = Escalao::orderBy('nome')->get();

        return view('eventos.index', compact('eventos', 'tipos', 'escaloes'));
    }

    public function publicos(Request $request)
    {
        $user = auth()->user();

        $query = Evento::with(['tipo','escaloes','convocatoria'])
            ->when($request->filled('titulo'), fn($q) =>
                $q->where('titulo', 'like', '%'.$request->get('titulo').'%'))
            // tipo de evento
            ->when($request->filled('tipo') && $request->get('tipo') !== 'todos', function ($q) use ($request) {
                $q->where('tipo_evento_id', (int) $request->get('tipo'));
                })
            ->when($request->filled('local'), fn($q) =>
                $q->where('local', 'like', '%'.$request->get('local').'%'))
            ->when($request->filled('data_de'), fn($q) =>
                $q->whereDate('data_inicio', '>=', $request->date('data_de')))
            ->when($request->filled('data_ate'), fn($q) =>
                $q->whereDate('data_fim', '<=', $request->date('data_ate')))
            ->when($request->filled('escalao') && $request->get('escalao') !== 'todos', function ($q) use ($request) {
                $q->whereHas('escaloes', function ($qq) use ($request) {
                    $qq->where('escaloes.id', (int) $request->get('escalao'));
                    });
                });

        // Evita erro se não houver user autenticado
        if (!($user && $user->hasRole(['administrador', 'treinador']))) {
            $query->where('visibilidade', 'publico');
        }

        $eventos = $query->orderByDesc('data_inicio')->paginate(15)->withQueryString();

        $eventosFormatados = $eventos->map(fn($e) => [
            'title' => $e->titulo,
            'start' => $e->data_inicio,
            'end'   => $e->data_fim,
            'url'   => route('eventos.show', $e),
            'color' => optional($e->tipo)->cor,
        ])->values();

        $tiposEvento = EventoTipo::orderBy('nome')->get();
        $escaloes    = Escalao::orderBy('nome')->get();

        return view('eventos.publicos', compact('eventos', 'eventosFormatados', 'tiposEvento', 'escaloes'));
    }

    public function create()
    {
        $tipos = EventoTipo::orderBy('nome')->get();
        $escaloes = Escalao::orderBy('nome')->get();
        $convocatorias = Convocatoria::orderBy('titulo')->get();
        
        return view('eventos.create', compact('tipos', 'escaloes', 'convocatorias'));
    }

    public function store(StoreEventoRequest $request)
    {
        $dados = $request->validate([
            'titulo'               => ['required','string','max:255'],
            'descricao'            => ['nullable','string'],
            'local'                => ['nullable','string','max:255'],
            'data_inicio'          => ['required','date'],
            'data_fim'             => ['required','date','after_or_equal:data_inicio'],

            // ❗ OBRIGATÓRIOS para a BD
            'tipo_evento_id'       => ['required','exists:eventos_tipos,id'],
            'visibilidade'         => ['required','in:publico,restrito,privado'],

            'convocatoria_id'      => ['nullable','exists:convocatorias,id'],
            'tem_transporte'       => ['sometimes','boolean'],
            'transporte_descricao' => ['nullable','string'],
            'local_partida'        => ['nullable','string','max:255'],
            'hora_partida'         => ['nullable','date_format:H:i'],
            'convocatoria_path'    => ['nullable','file','mimes:pdf,doc,docx','max:10240'],
            'regulamento_path'     => ['nullable','file','mimes:pdf,doc,docx','max:10240'],
            'observacoes'          => ['nullable','string'],
            'escaloes'             => ['nullable','array'],
            'escaloes.*'           => ['exists:escaloes,id'],
        ]);

        // normalizar checkbox
        $dados['tem_transporte'] = $request->boolean('tem_transporte');

        // uploads
        if ($request->hasFile('convocatoria_path')) {
            $dados['convocatoria_path'] = $request->file('convocatoria_path')
                ->store('eventos/convocatorias', 'public');
        }
        if ($request->hasFile('regulamento_path')) {
            $dados['regulamento_path'] = $request->file('regulamento_path')
                ->store('eventos/regulamentos', 'public');
        }

        // criar com $dados (já contém tipo_evento_id e visibilidade)
        $evento = Evento::create($dados);

        // pivot escalões
        $evento->escaloes()->sync($request->input('escaloes', []));

        return redirect()->route('eventos.index')->with('success', 'Evento criado com sucesso.');
    }

    public function show(Evento $evento)
    {
        $this->authorize('view', $evento);

        $evento->load(['tipo','escaloes','convocatoria']); // tipo, pivot escalões e registo de convocatória

        return view('eventos.show', compact('evento'));
    }

    public function edit(Evento $evento)
    {
        $tipos = EventoTipo::orderBy('nome')->get();
        $escaloes = Escalao::orderBy('nome')->get(); // <-- FALTAVA
        $convocatorias = Convocatoria::orderBy('titulo')->get();

        return view('eventos.edit', compact('evento', 'tipos', 'escaloes', 'convocatorias'));
    }

    public function update(UpdateEventoRequest $request, Evento $evento)
    {
        $dados = $request->validated();
        $dados['tem_transporte'] = $request->boolean('tem_transporte');

        if ($request->hasFile('convocatoria_path')) {
            // (opcional) apagar antigo
            if ($evento->convocatoria_path) Storage::disk('public')->delete($evento->convocatoria_path);
            $dados['convocatoria_path'] = $request->file('convocatoria_path')
                ->store('eventos/convocatorias', 'public');
        }
        if ($request->hasFile('regulamento_path')) {
            if ($evento->regulamento_path) Storage::disk('public')->delete($evento->regulamento_path);
            $dados['regulamento_path'] = $request->file('regulamento_path')
                ->store('eventos/regulamentos', 'public');
        }

        $evento->update($dados);
        $evento->escaloes()->sync($request->input('escaloes', []));

        return redirect()->route('eventos.index')->with('success','Evento atualizado com sucesso.');
    }

    public function destroy(Evento $evento)
    {
        if ($evento->convocatoria_path) Storage::disk('public')->delete($evento->convocatoria_path);
        if ($evento->regulamento_path)  Storage::disk('public')->delete($evento->regulamento_path);

        $evento->delete();
        return back()->with('success', 'Evento apagado com sucesso.');
    }
}
