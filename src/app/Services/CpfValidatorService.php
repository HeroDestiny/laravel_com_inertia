<?php

namespace App\Services;

/**
 * Serviço para validação de CPF
 * Resolve o CODE SMELL de duplicação de código
 */
class CpfValidatorService
{
  private const CPF_LENGTH = 11;

  /**
   * Valida se um CPF é válido
   */
  public function isValid(string $cpf): bool
  {
    $cpf = $this->sanitize($cpf);

    if (strlen($cpf) !== self::CPF_LENGTH) {
      return false;
    }

    return $this->validateDigits($cpf);
  }

  /**
   * Remove caracteres não numéricos do CPF
   */
  private function sanitize(string $cpf): string
  {
    return preg_replace('/[^0-9]/', '', $cpf);
  }

  /**
   * Valida os dígitos verificadores do CPF
   */
  private function validateDigits(string $cpf): bool
  {
    // Primeiro dígito verificador
    $sum = 0;
    for ($i = 0; $i < 9; $i++) {
      $sum += intval($cpf[$i]) * (10 - $i);
    }
    $remainder = $sum % 11;
    $digit1 = ($remainder < 2) ? 0 : 11 - $remainder;

    if (intval($cpf[9]) !== $digit1) {
      return false;
    }

    // Segundo dígito verificador
    $sum = 0;
    for ($i = 0; $i < 10; $i++) {
      $sum += intval($cpf[$i]) * (11 - $i);
    }
    $remainder = $sum % 11;
    $digit2 = ($remainder < 2) ? 0 : 11 - $remainder;

    return intval($cpf[10]) === $digit2;
  }

  /**
   * Formata CPF para exibição
   */
  public function format(string $cpf): string
  {
    $cpf = $this->sanitize($cpf);

    if (strlen($cpf) !== self::CPF_LENGTH) {
      return $cpf;
    }

    return substr($cpf, 0, 3) . '.' .
      substr($cpf, 3, 3) . '.' .
      substr($cpf, 6, 3) . '-' .
      substr($cpf, 9, 2);
  }
}
