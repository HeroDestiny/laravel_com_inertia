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
    return [
      'name' => ['required', 'string', 'max:255'],
      'surname' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'max:255'],
      'cpf' => ['required', 'string', 'size:11'],
      'birthdate' => ['required', 'date'],
      'mother_name' => ['required', 'string', 'max:255'],
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
      'email.required' => 'Email é obrigatório',
      'email.email' => 'Email deve ter formato válido',
      'cpf.required' => 'CPF é obrigatório',
      'cpf.size' => 'CPF deve ter 11 dígitos',
    ];
  }
}
