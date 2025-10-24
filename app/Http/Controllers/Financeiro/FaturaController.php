<?php

namespace App\Http\Controllers;

use App\Models\Fatura;
use App\Models\FaturaItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\CatalogoFaturaItem;
use App\Models\Mensalidade;

class FaturaController extends Controller
{
    /* ======================== LISTAGEM ======================== */
    public function index()
    {
        $faturas = Fatura::with('user')
            ->orderBy('id', 'desc')
            ->paginate(20);

        $utilizadores = User::with(['tipoUsers', 'escaloes'])
            ->where('estado', 1)
            ->get();

        // Catálogo de descrições base a partir de itens já usados (se tiveres tabela catálogo, troca aqui)
        $itensFatura = DB::table('catalogo_fatura_itens')
            ->select('id', 'descricao', 'valor_unitario', 'imposto_percentual')
            ->orderBy('id', 'asc')
            ->get();

        $itensCatalogo = DB::table('catalogo_fatura_itens')
            ->select('id','descricao', DB::raw('valor_unitario as valor_unitario'), 'imposto_percentual')
            ->orderBy('id')
            ->get();

        $mensalidades = DB::table('mensalidades')
            ->select('id', 'designacao as descricao', 'valor')
            ->orderBy('id')
            ->get()
            ->map(fn($m) => [
                'id'        => $m->id,
                'descricao' => $m->descricao,
                'valor'     => (float) $m->valor,
            ]);

        return view('faturas.index', compact('faturas', 'utilizadores', 'itensFatura','itensCatalogo','mensalidades'));
    }

    /* ======================== CRIAÇÃO (FORM SIMPLES) ======================== */
    public function create()
    {
        $utilizadores = User::with(['tipoUsers', 'escaloes'])->get();
        return view('faturas.create', compact('utilizadores'));
    }

    /* ======================== GUARDAR (FORM SIMPLES) ======================== */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'      => ['required','exists:users,id'],
            'mes'          => ['required','regex:/^\d{4}-(0[1-9]|1[0-2])$/'], // YYYY-MM
            'valor'        => ['required','numeric','min:0'],
            'data_emissao' => ['nullable','date'],
        ], [], ['mes' => 'mês']);

        // Anti-duplicado por (user,mes)
        $exists = Fatura::where('user_id', $data['user_id'])
            ->where('mes', $data['mes'])
            ->exists();
        if ($exists) {
            return back()->with('error', 'Já existe uma fatura para este utilizador nesse mês.')->withInput();
        }

        Fatura::create([
            'user_id'          => (int) $data['user_id'],
            'mes'              => $data['mes'],
            'estado_pagamento' => 0,
            'valor'            => round((float) $data['valor'], 2),
            'data_emissao'     => !empty($data['data_emissao'])
                ? Carbon::parse($data['data_emissao'])
                : Carbon::createFromFormat('Y-m', $data['mes'])->startOfMonth(),
        ]);

        return back()->with('success', 'Fatura criada com sucesso.');
    }

    /* ======================== EDITAR ======================== */
    public function edit(Fatura $fatura)
    {
        $tab = request('tab'); // opcional
        $fatura->load('items', 'user');
        return view('faturas.edit', compact('fatura', 'tab'));
    }

    /* ======================== ATUALIZAR (GUARDAR EDIÇÃO) ======================== */
    public function update(Request $request, Fatura $fatura)
    {
        $data = $request->validate([
            'estado_pagamento'            => ['required','in:0,1'],
            'numero_recibo'               => ['nullable','string','max:255'],
            'data_emissao'                => ['nullable','date'],
            'mes'                         => ['required','regex:/^\d{4}-(0[1-9]|1[0-2])$/'], // YYYY-MM
            'itens'                       => ['required','array','min:1'],
            'itens.*.id'                  => ['nullable','integer','exists:fatura_itens,id'],
            'itens.*.descricao'           => ['required','string','max:255'],
            'itens.*.valor_unitario'      => ['required','numeric','min:0'],
            'itens.*.quantidade'          => ['required','numeric','min:0.01'],
            'itens.*.imposto_percentual'  => ['nullable','numeric','min:0'],
        ], [], ['mes' => 'mês']);

        
        // compat: se alguém ainda enviar 'referencia_pagamento', dá prioridade ao número_recibo
        if ($request->filled('referencia_pagamento') && !$request->filled('numero_recibo')) {
            $data['numero_recibo'] = $request->input('referencia_pagamento');
        }
        

        // Anti-duplicado por (user_id, mes) quando se altera o mês
        if (!empty($data['mes'])) {
            $dup = Fatura::where('user_id', $fatura->user_id)
                ->where('mes', $data['mes'])
                ->where('id', '!=', $fatura->id)
                ->exists();
            if ($dup) {
                return back()->withErrors(['mes' => 'Já existe uma fatura para este utilizador e mês.'])->withInput();
            }
        }

        $alteradas = 0; $criadas = 0; $apagadas = 0;

        DB::transaction(function () use ($fatura, $data, &$alteradas, &$criadas, &$apagadas) {
            // 1) Cabeçalho
            $fatura->estado_pagamento     = (int) $data['estado_pagamento'];
            $fatura->numero_recibo        = $data['numero_recibo'] ?? null;

            // Manter compatibilidade com a coluna antiga: espelhar o mesmo valor
            $fatura->referencia_pagamento = $fatura->numero_recibo;

            $fatura->mes = $data['mes'];
            if (!empty($data['data_emissao'])) {
                $fatura->data_emissao = Carbon::parse($data['data_emissao']);
            } elseif (!$fatura->data_emissao) {
                $fatura->data_emissao = Carbon::createFromFormat('Y-m', $data['mes'])->startOfMonth();
            }
            if ($fatura->valor === null) { $fatura->valor = 0; }

            $nr = $data['numero_recibo'] ?? $data['referencia_pagamento'] ?? null;
            $fatura->numero_recibo        = $nr;
            $fatura->referencia_pagamento = $nr; // mantém a coluna antiga coerente (se existir)
            $fatura->save();

            // 2) Linhas
            $idsMantidos = [];
            foreach ($data['itens'] as $linha) {
                $valor = (float) $linha['valor_unitario'];
                $qtd   = (float) $linha['quantidade'];
                $iva   = (float) ($linha['imposto_percentual'] ?? 0);

                $base       = round($valor * $qtd, 2);
                $ivaValor   = round($base * ($iva/100), 2);
                $totalLinha = round($base + $ivaValor, 2);

                $payload = [
                    'descricao'          => $linha['descricao'],
                    'valor_unitario'     => $valor,
                    'quantidade'         => $qtd,
                    'imposto_percentual' => $iva,
                    'total_linha'        => $totalLinha,
                ];

                if (!empty($linha['id'])) {
                    $item = FaturaItem::where('id', $linha['id'])
                        ->where('fatura_id', $fatura->id)
                        ->firstOrFail();
                    $item->fill($payload);
                    $alteradas += $item->isDirty() ? 1 : 0;
                    $item->save();
                    $idsMantidos[] = $item->id;
                } else {
                    $novo = new FaturaItem($payload);
                    $novo->fatura_id = $fatura->id;
                    $novo->save();
                    $idsMantidos[] = $novo->id;
                    $criadas++;
                }
            }

            // 3) Apagar linhas removidas
            $apagadas = FaturaItem::where('fatura_id', $fatura->id)
                ->whereNotIn('id', $idsMantidos)
                ->delete();

            // 4) Recalcular total
            $fatura->recalcularTotal();
        });

        return back()->with('success', "Fatura atualizada. Linhas: +{$criadas} / ~{$alteradas} / -{$apagadas}.");
    }

    /* ======================== CRIAÇÃO MANUAL COMPLETA ======================== */
    public function storeManual(Request $request)
    {
        $data = $request->validate([
            'user_id'      => ['required','exists:users,id'],
            'mes'          => ['required','regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            'data_emissao' => ['nullable','date'],
            'linhas'  => ['required','array','min:1'],
            'linhas.*.descricao'          => ['required','string','max:255'],
            'linhas.*.valor_unitario'     => ['required','numeric','min:0'],
            'linhas.*.quantidade'         => ['required','numeric','min:0.01'],
            'linhas.*.imposto_percentual' => ['nullable','numeric','min:0'],
        ], [], ['mes' => 'mês']);

        $userId = (int) $data['user_id'];
        $mesStr = $data['mes'];

        // Duplicado (user,mes)
        if (Fatura::where('user_id', $userId)->where('mes', $mesStr)->exists()) {
            return back()->with('error', 'Já existe uma fatura para este utilizador neste mês.')->withInput();
        }

        $criadas = 0;

        DB::transaction(function () use ($data, $userId, $mesStr) {
            $fatura = Fatura::create([
                'user_id'          => $userId,
                'mes'              => $mesStr,
                'estado_pagamento' => 0,
                'valor'            => 0,
                'data_emissao'     => !empty($data['data_emissao'])
                    ? Carbon::parse($data['data_emissao'])
                    : Carbon::createFromFormat('Y-m', $mesStr)->startOfMonth(),
            ]);

            foreach ($data['linhas'] as $linha) {
                $valor = (float) $linha['valor_unitario'];
                $qtd   = (float) $linha['quantidade'];
                $iva   = (float) ($linha['imposto_percentual'] ?? 0);

                $base       = round($valor * $qtd, 2);
                $ivaValor   = round($base * ($iva/100), 2);
                $totalLinha = round($base + $ivaValor, 2);

                FaturaItem::create([
                    'fatura_id'          => $fatura->id,
                    'descricao'          => $linha['descricao'],
                    'valor_unitario'     => $valor,
                    'quantidade'         => $qtd,
                    'imposto_percentual' => $iva,
                    'total_linha'        => $totalLinha,
                ]);

                $criadas++;
            }

            $fatura->recalcularTotal();
        });

        return redirect()
            ->route('faturas.index')
            ->with('success', "Fatura atualizada. Linhas: {$criadas} Criadas / {$alteradas} Alteradas / {$apagadas} Apagadas.");
            }


    public function destroy(Fatura $fatura)
    {
        $id = $fatura->id;
        $fatura->delete(); // fatura_itens apagam em cascata (FK ON DELETE CASCADE)
        return redirect()
            ->route('faturas.index')
            ->with('success', "Fatura #{$id} apagada com sucesso.");
    }


    /* ======================== GERAÇÃO AUTOMÁTICA (LOTE, SEM EDITAR EXISTENTES) ======================== */
    public function gerarPorIntervalo(Request $request)
    {
        $request->validate([
            'mes_inicio' => ['required','regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            'user_id'    => ['nullable', Rule::in(['all']), 'sometimes'], // vai ser “all” ou vem vazio; se vier id, tratamos abaixo
        ], [
            'mes_inicio.regex' => 'O mês inicial deve estar no formato YYYY-MM.',
        ]);

        $inicio = Carbon::createFromFormat('Y-m', $request->mes_inicio)->startOfMonth();

        // Gerar até Julho (inclusive) do mesmo ano ou do próximo, conforme regras combinadas
        $julhoMesmoAno = Carbon::create($inicio->year, 7, 1)->startOfMonth();
        $fim = $inicio->lessThanOrEqualTo($julhoMesmoAno)
            ? $julhoMesmoAno
            : Carbon::create($inicio->year + 1, 7, 1)->startOfMonth();

        $periodo = CarbonPeriod::create($inicio, '1 month', $fim);

        // Base: utilizadores ativos com mensalidade definida (users.mensalidade_id)
        $q = User::query()
            ->leftJoin('mensalidades as m_user', 'm_user.id', '=', 'users.mensalidade_id')
            ->where('users.estado', 1)
            ->whereNotNull('users.mensalidade_id');

        // Filtrar 1 utilizador específico (numeric) — quando o select não foi "all"
        $userIdReq = $request->input('user_id');
        if ($userIdReq && $userIdReq !== 'all') {
            // Validar que existe
            $request->validate([
                'user_id' => ['required','integer','exists:users,id']
            ]);
            $q->where('users.id', (int) $userIdReq);
        }

        $utilizadores = $q->get([
            'users.id',
            'users.name',
            DB::raw('m_user.valor as valor_mensalidade'),
        ]);

        if ($utilizadores->isEmpty()) {
            return back()->with('warning', 'Nenhum utilizador com mensalidade definida para gerar faturas.');
        }

        $criadas = 0;
        $ignoradas = 0;

        DB::transaction(function () use ($utilizadores, $periodo, &$criadas, &$ignoradas) {
            foreach ($utilizadores as $u) {
                $valorMensal = (float) ($u->valor_mensalidade ?? 0);
                if ($valorMensal <= 0) {
                    // sem valor definido → ignora silenciosamente
                    continue;
                }

                foreach ($periodo as $mes) {
                    $mesStr = $mes->format('Y-m'); // YYYY-MM

                    // Se já existe fatura (user, mes) → IGNORAR (NÃO editar)
                    $jaExiste = Fatura::where('user_id', $u->id)
                        ->where('mes', $mesStr)
                        ->exists();

                    if ($jaExiste) {
                        $ignoradas++;
                        continue;
                    }

                    // Criar cabeçalho com data completa (1º dia do mês)
                    $fatura = Fatura::create([
                        'user_id'          => $u->id,
                        'mes'              => $mesStr,
                        'estado_pagamento' => 0,
                        'valor'            => 0, // será recalculado pelas linhas
                        'data_emissao'     => $mes->copy()->startOfMonth(),
                    ]);

                    // Linha de mensalidade (qtd=1, IVA=0% por omissão)
                    $descricao = "Mensalidade do mês {$mesStr}";
                    $qtd = 1.00;
                    $iva = 0.00;

                    $base       = round($valorMensal * $qtd, 2);
                    $ivaValor   = round($base * ($iva/100), 2);
                    $totalLinha = round($base + $ivaValor, 2);

                    FaturaItem::create([
                        'fatura_id'          => $fatura->id,
                        'descricao'          => $descricao,
                        'valor_unitario'     => $valorMensal,
                        'quantidade'         => $qtd,
                        'imposto_percentual' => $iva,
                        'total_linha'        => $totalLinha,
                    ]);

                    // Total
                    $fatura->recalcularTotal();

                    $criadas++;
                }
            }
        });

        $alvo = ($userIdReq && $userIdReq !== 'all') ? '1 utilizador' : ($utilizadores->count().' utilizadores');
        return back()->with('success', "Geração concluída para {$alvo}. Criadas: {$criadas}, Ignoradas (já existiam): {$ignoradas}.");
    }
}
