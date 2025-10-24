<?php

namespace App\Http\Controllers\Pessoas;

use App\Http\Requests\StoreAtletaRequest;
use App\Http\Requests\UpdateAtletaRequest;
use App\Models\DadosPessoais;
use App\Models\DadosDesportivos;
use App\Models\SaudeAtleta;
use App\Models\DadosFinanceiros;
use App\Models\Resultado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AtletaController extends Controller
{
    public function store(StoreAtletaRequest $request)
    {
        $data = $request->validated();
        $data['numero_socio'] = Atleta::max('numero_socio') + 1;

        Atleta::create($data);
        return redirect()->route('atletas.index')->with('success', 'Atleta criado com sucesso.');
    }

    public function update(UpdateAtletaRequest $request, Atleta $atleta)
    {
        $data = $request->validated();

        $atleta->update($data);
        return redirect()->route('atletas.index')->with('success', 'Dados atualizados com sucesso.');
    }

    public function updateParcial(Request $request, $id, $secao)
    {
        $atleta = Atleta::findOrFail($id);

        if (Gate::denies('editar-ficha', $atleta)) {
            abort(403);
        }

        switch ($secao) {
            case 'dados_pessoais':
                $dados = $request->only([
                    'data_nascimento', 'nif', 'contacto', 'email', 'morada',
                    'codigo_postal', 'localidade', 'sexo', 'estado_civil',
                    'nacionalidade', 'ocupacao', 'empresa', 'escola',
                    'menor', 'estado_utilizador'
                ]);
                DadosPessoais::updateOrCreate(['atleta_id' => $atleta->id], $dados);
                break;

            case 'dados_desportivos':
                $dados = $request->only(['altura', 'peso', 'batimento', 'observacoes']);
                DadosDesportivos::updateOrCreate(['atleta_id' => $atleta->id], $dados);
                break;

            case 'saude':
                $dados = $request->only(['patologias', 'medicamentos']);
                SaudeAtleta::updateOrCreate(['atleta_id' => $atleta->id], $dados);
                break;

            case 'financeiro':
                $dados = $request->only([
                    'mensalidade', 'tipo_mensalidade', 'estado_pagamento',
                    'numero_recibo', 'referencia_pagamento'
                ]);
                DadosFinanceiros::updateOrCreate(['atleta_id' => $atleta->id], $dados);
                break;

            case 'resultados':
                Resultado::create([
                    'atleta_id' => $atleta->id,
                    'prova' => $request->input('prova'),
                    'tempo' => $request->input('tempo'),
                    'data' => $request->input('data'),
                    'epoca' => $request->input('epoca'),
                ]);
                break;

            default:
                return redirect()->back()->with('error', 'Secção inválida.');
        }

        return redirect()->back()->with('success', 'Dados atualizados com sucesso.');
    }
 }   