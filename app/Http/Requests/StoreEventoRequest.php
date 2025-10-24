<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ou gate/policy se necessário
    }

    public function rules(): array
    {
       return [
        'titulo'               => 'required|string|max:255',
        'descricao'            => 'nullable|string',
        'local'                => 'nullable|string|max:255',
        'data_inicio'          => 'required|date',
        'data_fim'             => 'required|date|after_or_equal:data_inicio',
        'tipo_evento_id'       => 'required|exists:eventos_tipos,id',
        'visibilidade'         => 'required|in:privado,restrito,publico',

        // NOMES DA BD
        'tem_transporte'    => 'sometimes|boolean',
        'transporte_descricao'  => 'nullable|string',
        'local_partida'        => 'nullable|string|max:255',
        'hora_partida'         => 'nullable|date_format:H:i',

        'convocatoria_id'      => 'nullable|exists:convocatorias,id',
        'convocatoria_path'    => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        'regulamento_path'     => 'nullable|file|mimes:pdf,doc,docx|max:10240',

        'observacoes'       => 'nullable|string',

        'escaloes'             => 'nullable|array',
        'escaloes.*'           => 'exists:escaloes,id',

        'observacoes' => 'nullable|string',
    ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tipo_evento_id' => $this->input('tipo_evento_id', $this->input('tipo')),
        ]);
    }
}
