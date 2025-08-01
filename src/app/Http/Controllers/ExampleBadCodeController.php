<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Exemplo com vários code smells para demonstração
 * NÃO USAR EM PRODUÇÃO - criado apenas para fins educativos
 */
class ExampleBadCodeController extends Controller
{
  /**
   * CODE SMELL 1: Método muito longo (Long Method)
   * Este método faz muitas coisas diferentes
   */
  public function badExample(Request $request)
  {
    // Validação inline (deveria estar separada)
    if (!$request->has('name')) {
      return response()->json(['error' => 'Name is required'], 400);
    }
    if (!$request->has('email')) {
      return response()->json(['error' => 'Email is required'], 400);
    }
    if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
      return response()->json(['error' => 'Invalid email'], 400);
    }

    // Lógica de negócio misturada com apresentação
    $paciente = new Paciente();
    $paciente->name = $request->name;
    $paciente->email = $request->email;
    $paciente->surname = $request->surname ?? '';
    $paciente->birthdate = $request->birthdate ?? '';
    $paciente->cpf = $request->cpf ?? '';
    $paciente->mother_name = $request->mother_name ?? '';

    // Lógica de validação de CPF inline
    $cpf = preg_replace('/[^0-9]/', '', $paciente->cpf);
    if (strlen($cpf) != 11) {
      return response()->json(['error' => 'Invalid CPF'], 400);
    }

    // Mais validações...
    $sum = 0;
    for ($i = 0; $i < 9; $i++) {
      $sum += intval($cpf[$i]) * (10 - $i);
    }
    $remainder = $sum % 11;
    $digit1 = ($remainder < 2) ? 0 : 11 - $remainder;

    if (intval($cpf[9]) != $digit1) {
      return response()->json(['error' => 'Invalid CPF'], 400);
    }

    // Segunda parte da validação do CPF
    $sum = 0;
    for ($i = 0; $i < 10; $i++) {
      $sum += intval($cpf[$i]) * (11 - $i);
    }
    $remainder = $sum % 11;
    $digit2 = ($remainder < 2) ? 0 : 11 - $remainder;

    if (intval($cpf[10]) != $digit2) {
      return response()->json(['error' => 'Invalid CPF'], 400);
    }

    $paciente->save();

    // Log inline (deveria estar em um serviço)
    file_put_contents(
      storage_path('logs/pacientes.log'),
      date('Y-m-d H:i:s') . ' - Paciente criado: ' . $paciente->name . "\n",
      FILE_APPEND
    );

    // Retorno com lógica de formatação inline
    return response()->json([
      'message' => 'Paciente criado com sucesso',
      'data' => [
        'id' => $paciente->id,
        'name' => $paciente->name,
        'display_name' => $paciente->name . ' ' . $paciente->surname,
        'email' => $paciente->email,
        'formatted_cpf' => substr($cpf, 0, 3) . '.' .
          substr($cpf, 3, 3) . '.' .
          substr($cpf, 6, 3) . '-' .
          substr($cpf, 9, 2)
      ]
    ]);
  }

  /**
   * CODE SMELL 2: Duplicação de código (Duplicate Code)
   * Método similar ao anterior com código duplicado
   */
  public function anotherBadExample(Request $request)
  {
    // Mesma validação duplicada
    if (!$request->has('name')) {
      return response()->json(['error' => 'Name is required'], 400);
    }
    if (!$request->has('email')) {
      return response()->json(['error' => 'Email is required'], 400);
    }
    if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
      return response()->json(['error' => 'Invalid email'], 400);
    }

    // Mesma lógica de validação de CPF duplicada
    $cpf = preg_replace('/[^0-9]/', '', $request->cpf);
    if (strlen($cpf) != 11) {
      return response()->json(['error' => 'Invalid CPF'], 400);
    }

    $sum = 0;
    for ($i = 0; $i < 9; $i++) {
      $sum += intval($cpf[$i]) * (10 - $i);
    }
    $remainder = $sum % 11;
    $digit1 = ($remainder < 2) ? 0 : 11 - $remainder;

    if (intval($cpf[9]) != $digit1) {
      return response()->json(['error' => 'Invalid CPF'], 400);
    }

    // Update ao invés de create, mas com lógica similar
    $paciente = Paciente::find($request->id);
    $paciente->update($request->only(['name', 'email', 'surname']));

    return response()->json(['message' => 'Updated']);
  }

  /**
   * CODE SMELL 3: Números mágicos (Magic Numbers)
   * Uso de valores hardcoded sem explicação
   */
  public function magicNumbersExample()
  {
    $pacientes = Paciente::take(42)->get(); // Por que 42?

    foreach ($pacientes as $paciente) {
      if (strlen($paciente->name) > 15) { // Por que 15?
        $paciente->short_name = substr($paciente->name, 0, 12) . '...'; // Por que 12?
      }

      // Cálculo de idade com números mágicos
      $age = (time() - strtotime($paciente->birthdate)) / 31536000; // Por que 31536000?

      if ($age > 65) { // Por que 65?
        $paciente->category = 'senior';
      } elseif ($age < 18) { // Por que 18?
        $paciente->category = 'minor';
      }
    }

    return $pacientes;
  }

  /**
   * CODE SMELL 4: Vulnerabilidade de segurança - SQL Injection
   * NUNCA FAZER ISSO EM PRODUÇÃO!
   */
  public function securityVulnerability($id)
  {
    // VULNERABILIDADE: SQL Injection
    $result = DB::select("SELECT * FROM pacientes WHERE id = $id");

    return $result;
  }

  /**
   * CODE SMELL 5: Variável não usada
   */
  public function unusedVariable()
  {
    $unusedVar = "Esta variável nunca é usada";
    $pacientes = Paciente::all();

    return $pacientes;
  }
}
