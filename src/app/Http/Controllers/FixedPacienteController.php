<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePacienteRequest;
use App\Models\Paciente;
use App\Services\PacienteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controller corrigido - demonstra as boas práticas
 * Resolve todos os code smells identificados
 */
class FixedPacienteController extends Controller
{
  public function __construct(
    private PacienteService $pacienteService
  ) {}

  /**
   * CORREÇÃO DO CODE SMELL 1: Método muito longo
   * Método agora tem responsabilidade única e é conciso
   */
  public function store(CreatePacienteRequest $request): JsonResponse
  {
    try {
      $paciente = $this->pacienteService->create($request->validated());

      return response()->json([
        'message' => 'Paciente criado com sucesso',
        'data' => $this->pacienteService->formatForResponse($paciente)
      ], 201);
    } catch (\InvalidArgumentException $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 400);
    }
  }

  /**
   * CORREÇÃO DO CODE SMELL 2: Duplicação de código
   * Reutiliza o mesmo serviço, evitando duplicação
   */
  public function update(CreatePacienteRequest $request, int $id): JsonResponse
  {
    try {
      $paciente = $this->pacienteService->update($id, $request->validated());

      return response()->json([
        'message' => 'Paciente atualizado com sucesso',
        'data' => $this->pacienteService->formatForResponse($paciente)
      ]);
    } catch (\InvalidArgumentException $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 400);
    }
  }

  /**
   * CORREÇÃO DO CODE SMELL 3: Números mágicos
   * Usa constantes com nomes descritivos
   */
  public function index(): JsonResponse
  {
    $pacientes = Paciente::take(self::DEFAULT_PAGE_SIZE)->get();

    $formattedPacientes = $pacientes->map(function (Paciente $paciente) {
      $formatted = $this->pacienteService->formatForResponse($paciente);

      // Aplica truncamento se necessário
      if (strlen($paciente->name) > self::MAX_NAME_DISPLAY_LENGTH) {
        $formatted['short_name'] = $this->truncateName($paciente->name);
      }

      return $formatted;
    });

    return response()->json([
      'data' => $formattedPacientes,
      'total' => $formattedPacientes->count()
    ]);
  }

  /**
   * CORREÇÃO DO CODE SMELL 4: Vulnerabilidade de segurança
   * Usa Eloquent com prepared statements, evitando SQL injection
   */
  public function show(int $id): JsonResponse
  {
    $paciente = Paciente::findOrFail($id);

    return response()->json([
      'data' => $this->pacienteService->formatForResponse($paciente)
    ]);
  }

  /**
   * CORREÇÃO DO CODE SMELL 5: Variável não usada
   * Remove variáveis desnecessárias e usa código limpo
   */
  public function list(): JsonResponse
  {
    $pacientes = Paciente::all();

    return response()->json([
      'data' => $pacientes->map(fn($p) => $this->pacienteService->formatForResponse($p))
    ]);
  }

  /**
   * Trunca nome para exibição
   */
  private function truncateName(string $name): string
  {
    return strlen($name) > self::MAX_NAME_DISPLAY_LENGTH
      ? substr($name, 0, self::NAME_TRUNCATE_LENGTH) . self::TRUNCATE_SUFFIX
      : $name;
  }

  // Constantes para resolver números mágicos
  private const DEFAULT_PAGE_SIZE = 50; // Tamanho padrão da página (era 42)
  private const MAX_NAME_DISPLAY_LENGTH = 30; // Tamanho máximo do nome (era 15)
  private const NAME_TRUNCATE_LENGTH = 27; // Tamanho para truncar (era 12)
  private const TRUNCATE_SUFFIX = '...'; // Sufixo para indicar truncamento
}
