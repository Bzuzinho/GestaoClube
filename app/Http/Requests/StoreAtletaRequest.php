<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAtletaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // já controlado pela policy
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:100',
            'apelido' => 'nullable|string|max:100',
            'data_nascimento' => 'nullable|date',
            'nif' => 'nullable|string|max:20',
            'contacto' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'morada' => 'nullable|string|max:255',
            'codigo_postal' => 'nullable|string|max:20',
            'localidade' => 'nullable|string|max:100',
            'sexo' => 'nullable|string|max:20',
            'escalao' => 'nullable|string|max:100',
            'menor' => 'nullable|boolean',
            'estado' => 'nullable|string|max:50',
            'user_id' => 'nullable|integer|exists:users,id',
            'tipo_membro' => 'nullable|array',
        ];
    }
}
