<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Request para atualização de paciente
 * Resolve o CODE SMELL de validação inline para updates
 */
class UpdatePacienteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        $pacienteId = $this->route('paciente')?->id;

        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'cpf' => [
                'required',
                'string',
                'size:11',
                Rule::unique('pacientes', 'cpf')->ignore($pacienteId),
            ],
            'role' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'mother_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('pacientes', 'email')->ignore($pacienteId),
            ],
        ];
    }

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
            'mother_name.required' => 'Nome da mãe é obrigatório',
        ];
    }
}
