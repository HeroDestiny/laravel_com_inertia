<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Reqfinal final uest para criação de paciente
 * Resolve o CODE SMELL de validação inline
 */
class CreatePacienteRequest extends FormRequest
{
    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nome é obrigatório',
            'surname.required' => 'Sobrenome é obrigatório',
            'email.required' => 'Email é obrigatório',
            'email.email' => 'Email deve ter formato válido',
            'email.unique' => 'Este email já está em uso',
            'cpf.required' => 'CPF é obrigatório',
            'cpf.size' => 'CPF deve ter exatamente 11 dígitos',
            'cpf.unique' => 'Este CPF já está cadastrado',
            'birthdate.required' => 'Data de nascimento é obrigatória',
            'birthdate.date' => 'Data de nascimento deve ser uma data válida',
            'motherName.required' => 'Nome da mãe é obrigatório',
        ];
    }
}
