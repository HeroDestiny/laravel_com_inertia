<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para criação de paciente
 * Resolve o CODE SMELL de validação inline
 */
class CreatePacienteRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $pacienteId = $this->route('paciente')?->id;

    return [
      'name' => ['required', 'string', 'max:255'],
      'surname' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255', 'unique:pacientes,email' . ($pacienteId ? ',' . $pacienteId : '')],
      'cpf' => ['required', 'string', 'size:11', 'unique:pacientes,cpf' . ($pacienteId ? ',' . $pacienteId : '')],
      'birthdate' => ['required', 'date'],
      'motherName' => ['required', 'string', 'max:255'],
      'role' => ['nullable', 'string', 'max:100'],
      'education' => ['nullable', 'string', 'max:100'],
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
      'motherName.required' => 'Nome da mãe é obrigatório',
    ];
  }
}
