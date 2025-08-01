<?php

namespace App\Services;

use App\Models\Paciente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Serviço para operações com pacientes
 * Resolve CODE SMELL de método muito longo
 */
class PacienteService
{
    // Constantes para resolver números mágicos
    private const SENIOR_AGE_THRESHOLD = 65;

    private const MINOR_AGE_THRESHOLD = 18;

    public function __construct(
        private readonly CpfValidatorService $cpfValidator
    ) {}

    /**
     * Cria um novo paciente
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Paciente
    {
        return DB::transaction(function () use ($data) {
            $this->validateCpf($data['cpf']);

            $paciente = Paciente::create($data);

            $this->logPacienteCreated($paciente);

            return $paciente;
        });
    }

    /**
     * Atualiza dados de um paciente
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): Paciente
    {
        return DB::transaction(function () use ($id, $data) {
            if (isset($data['cpf'])) {
                $this->validateCpf($data['cpf']);
            }

            $paciente = Paciente::findOrFail($id);
            $paciente->update($data);

            return $paciente;
        });
    }

    /**
     * Valida CPF
     */
    private function validateCpf(string $cpf): void
    {
        if (! $this->cpfValidator->isValid($cpf)) {
            throw new \InvalidArgumentException('CPF inválido');
        }
    }

    /**
     * Registra log da criação do paciente
     */
    private function logPacienteCreated(Paciente $paciente): void
    {
        Log::info('Paciente criado', [
            'id' => $paciente->id,
            'name' => $paciente->name,
            'created_at' => $paciente->created_at,
        ]);
    }

    /**
     * Formata dados do paciente para resposta
     *
     * @return array<string, mixed>
     */
    public function formatForResponse(Paciente $paciente): array
    {
        return [
            'id' => $paciente->id,
            'name' => $paciente->name,
            'display_name' => $this->getDisplayName($paciente),
            'email' => $paciente->email,
            'formatted_cpf' => $this->cpfValidator->format($paciente->cpf),
            'age' => $this->calculateAge($paciente->birthdate),
            'category' => $this->getAgeCategory($paciente->birthdate),
        ];
    }

    /**
     * Gera nome de exibição
     */
    private function getDisplayName(Paciente $paciente): string
    {
        return trim($paciente->name.' '.$paciente->surname);
    }

    /**
     * Calcula idade baseada na data de nascimento
     */
    private function calculateAge(string $birthdate): int
    {
        return (int) now()->diffInYears($birthdate);
    }

    /**
     * Determina categoria baseada na idade
     * Resolve CODE SMELL de números mágicos
     */
    private function getAgeCategory(string $birthdate): string
    {
        $age = $this->calculateAge($birthdate);

        return match (true) {
            $age >= self::SENIOR_AGE_THRESHOLD => 'senior',
            $age < self::MINOR_AGE_THRESHOLD => 'minor',
            default => 'adult'
        };
    }
}
