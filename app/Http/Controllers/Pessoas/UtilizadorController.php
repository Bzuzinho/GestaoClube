<?php

namespace App\Http\Controllers\Pessoas;

use App\Http\Controllers\Controller; // <—
use App\Models\User;
use App\Models\DadosDesportivos;
use App\Models\DadosFinanceiros;
use App\Models\DadosConfiguracao;
use App\Models\Mensalidade;
use App\Models\TipoUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use App\Models\Escalao;


class UtilizadorController extends Controller
{

    public function create(Request $request)
        {
        $escaloes = Escalao::all();
        $tiposUtilizador = TipoUser::all();
        $perfis = Role::all();
        // Apenas modal (AJAX)
        return view('utilizadores.modal-create', compact('escaloes','tiposUtilizador','perfis'));
        }

    public function store(Request $request)
    {
     $request->validate([
        'name'               => 'required|string|max:255',
        'email'              => 'required|email|unique:users',
        'password'           => 'nullable|string|min:6',
        'estado_utilizador'  => 'required|in:0,1',
        'escalao_ids'        => 'array',
        'escalao_ids.*'      => 'integer',
        'tipo_user_ids'      => 'array',
        'tipo_user_ids.*'    => 'integer',
        'profile_photo_path' => 'nullable|image|max:2048',
    ]);

    $ultimoNumero    = User::max('numero_socio');
    $novoNumeroSocio = $ultimoNumero ? $ultimoNumero + 1 : 1;

    // base de dados a gravar
    $dados = [
        'numero_socio'      => $novoNumeroSocio,
        'name'              => $request->name,
        'email'             => $request->email,
        'nif'               => $request->nif,
        'contacto'          => $request->contacto,
        'data_nascimento'   => $request->data_nascimento,
        'sexo'              => $request->sexo,
        'estado_utilizador' => $request->estado_utilizador,     // manténs se quiseres
        'estado'            => (int) $request->estado_utilizador // ← POPULA “estado”
    ];

    // só define password se vier preenchida
    if ($request->filled('password')) {
        $dados['password'] = Hash::make($request->password);
    }

    $utilizador = User::create($dados);

    // foto de perfil
    if ($request->hasFile('profile_photo_path')) {
        $path = $request->file('profile_photo_path')->store('profile_photos', 'public');
        $utilizador->update(['profile_photo_path' => $path]);
    }

    // escalões
    $utilizador->escaloes()->sync($request->input('escalao_ids', []));

    if ($primeiro = collect($request->input('escalao_ids', []))->first()) {
        $utilizador->update(['escalao_id' => $primeiro]); // compat. com schema atual
    }

    // tipos de utilizador
    if ($request->filled('tipo_user_ids')) {
        $utilizador->tipoUsers()->sync($request->tipo_user_ids);
    }

    // perfil (Spatie Role)
    $role = $request->filled('perfil') ? $request->perfil : 'utilizador';
    $utilizador->syncRoles([$role]);

    $utilizador->update(['role' => $role]);

    return redirect()
        ->route('utilizadores.index')
        ->with('success', 'Utilizador criado com sucesso.');
}


    public function index()
    {
        $utilizadores   = User::with(['escaloes', 'tipoUsers'])->paginate(10);
        $escaloes       = \App\Models\Escalao::all();
        $tiposUtilizador = TipoUser::all();
        $perfis         = Role::pluck('name')->toArray();

        return view('utilizadores.index', compact('utilizadores', 'escaloes', 'tiposUtilizador', 'perfis'));
    }

// UtilizadorController.php
    public function edit(\App\Models\User $utilizador)
    {
        $this->authorize('update', $utilizador);

        $escaloes = \App\Models\Escalao::orderBy('nome')->get(['id','nome']);
        $tipos    = \App\Models\TipoUser::orderBy('nome')->get(['id','nome']);
        $perfis   = \Spatie\Permission\Models\Role::orderBy('name')->get(['id','name']);

        return view('utilizadores.edit', [
            'utilizador' => $utilizador->load(['escaloes:id','tipoUsers:id','roles:id']),
            'escaloes'   => $escaloes,
            'tipos'      => $tipos,
            'perfis'     => $perfis,
            'podeDefinirRole' => auth()->user()->can('setRole', $utilizador),
        ]);
    }



    public function update(Request $request, User $utilizador)
    {
        if (app()->isLocal()) {
            \Log::info('📥 Dados recebidos', ['payload' => $request->all()]);
            \Log::info('📦 dados_financeiros', ['dados_financeiros' => $request->input('dados_financeiros')]);
        }

        $request->validate([
            'role'          => 'required|exists:roles,name',
            'name'          => 'required|string|max:255',
            'email'         => 'required|email',
            'mensalidade_id'=> ['nullable','exists:mensalidades,id'],
        ]);

        $utilizador->escaloes()->sync($request->input('escaloes', []));
        $utilizador->tipoUsers()->sync($request->input('tipos', []));
        $utilizador->syncRoles([$request->input('role','utilizador')]);
        $utilizador->save();

        $data = $request->only([
            'numero_socio','nif','cartao_cidadao','name','data_nascimento','morada','codigo_postal',
            'localidade','empresa','escola','estado_civil','ocupacao','nacionalidade','sexo',
            'numero_irmaos','contacto','email','mensalidade_id',
        ]);

        $data['menor']  = (int) $request->input('menor');
        $data['estado'] = $request->estado;

        if (!$request->exists('mensalidade_id')) unset($data['mensalidade_id']);

        $utilizador->update($data);

            // ===== Dados Desportivos =====
        $dd = DadosDesportivos::firstOrNew(['user_id' => $utilizador->id]);
        $dd->fill([
            'altura' => $request->input('altura'),
            'peso' => $request->input('peso'),
            'batimento' => $request->input('batimento'),
            'observacoes' => $request->input('observacoes'),
            'patologias' => $request->input('patologias'),
            'medicamentos' => $request->input('medicamentos'),
            'numero_federacao' => $request->input('numero_federacao'),
            'pmb' => $request->input('pmb'),
            'data_inscricao' => $request->input('data_inscricao') ? \Carbon\Carbon::parse($request->input('data_inscricao'))->format('Y-m-d') : null,
            'atestado_medico' => $request->input('atestado_medico'),
            'data_atestado' => $request->input('data_atestado') ? \Carbon\Carbon::parse($request->input('data_atestado'))->format('Y-m-d') : null,
            'informacoes_medicas' => $request->input('informacoes_medicas'),
        ]);
        $dd->user_id = $utilizador->id; // garantir FK
        $dd->save();

        if ($request->hasFile('arquivo_am')) {
            $path = $request->file('arquivo_am')->store('atestados_medicos', 'public');
            $dd->update(['arquivo_am_path' => $path]);
        }

        // ===== Dados Financeiros =====
        if ($request->has('dados_financeiros')) {
            $dados = collect($request->input('dados_financeiros'))->except('user_id')->toArray();
            $utilizador->dadosFinanceiros()->updateOrCreate(['user_id' => $utilizador->id], $dados);
        }

        // ===== Configuração =====
        $cfg = DadosConfiguracao::firstOrNew(['user_id' => $utilizador->id]);
        $cfg->fill($request->only([
            'consentimento','data_consentimento','ficheiro_consentimento',
            'declaracao_transporte','data_transporte','ficheiro_transporte',
            'afiliacao','data_afiliacao','ficheiro_afiliacao',
        ]));
        $cfg->user_id = $utilizador->id;
        $cfg->save();


        return redirect()
            ->route('utilizadores.show', [$utilizador, 'tab' => $request->input('tab','pessoais')])
            ->with('success','Dados atualizados.');
    }

    public function destroy(\Illuminate\Http\Request $request, $id = null)
    {
        // apanhar o id da rota OU do body (hidden input)
        $finalId = $id ?? $request->route('id') ?? $request->route('utilizador')
                ?? $request->route('user') ?? $request->input('id');

        \Log::info('🗑️ destroy utilizador - debug', [
            'url'        => $request->fullUrl(),
            'route'      => optional($request->route())->uri(),
            'params'     => optional($request->route())->parameters(),
            'id_param'   => $id,
            'route_id'   => $request->route('id'),
            'body_id'    => $request->input('id'),
            'final_id'   => $finalId,
        ]);

        if (!$finalId) {
            return back()->with('error', 'ID do utilizador não recebido.');
        }

        $utilizador = \App\Models\User::find($finalId);
        if (!$utilizador) {
            return back()->with('error', 'Utilizador não encontrado.');
        }

        \DB::transaction(function () use ($utilizador) {
            if (method_exists($utilizador, 'escaloes'))     $utilizador->escaloes()->detach();
            if (method_exists($utilizador, 'tipoUsers'))    $utilizador->tipoUsers()->detach();
            if (method_exists($utilizador, 'roles'))        $utilizador->roles()->detach();
            if (method_exists($utilizador, 'permissions'))  $utilizador->permissions()->detach();
            if (method_exists($utilizador, 'encarregados')) $utilizador->encarregados()->detach();
            if (method_exists($utilizador, 'educandos'))    $utilizador->educandos()->detach();

            if ($utilizador->profile_photo_path && \Storage::disk('public')->exists($utilizador->profile_photo_path)) {
                \Storage::disk('public')->delete($utilizador->profile_photo_path);
            }

            $utilizador->delete();
        });

        return redirect()->route('utilizadores.index')
            ->with('success', 'Utilizador removido com sucesso.');
    }

    public function show(User $utilizador)
    {
        $this->authorize('view', $utilizador);

        return view('utilizadores.show', [
            'utilizador' => $utilizador->load(['escaloes','tipoUsers']),
            'escaloes'   => Escalao::orderBy('nome')->get(),
            'tipos'      => TipoUser::orderBy('nome')->get(),
            'perfis'     => Role::orderBy('name')->get(),
        ]);
    }
}
