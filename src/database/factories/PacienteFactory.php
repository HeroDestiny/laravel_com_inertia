<?php

namespace Database\Factories;

use App\Models\Paciente;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paciente>
 *
 * @psalm-suppress UnusedClass
 */
class PacienteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Paciente::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'birthdate' => $this->faker->date('Y-m-d', '2000-01-01'),
            'cpf' => $this->generateValidCpf(),
            'role' => $this->faker->optional()->jobTitle,
            'education' => $this->faker->optional()->randomElement(['Fundamental', 'Médio', 'Superior', 'Pós-graduação']),
            'mother_name' => $this->faker->name('female'),
            'email' => $this->faker->unique()->safeEmail,
        ];
    }

    /**
     * Gera um CPF válido para testes
     */
    private function generateValidCpf(): string
    {
        // Gera os 9 primeiros dígitos
        $cpf = '';
        for ($i = 0; $i < 9; $i++) {
            $cpf .= random_int(0, 9);
        }

        // Calcula o primeiro dígito verificador
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += ((int) $cpf[$i]) * (10 - $i);
        }
        $remainder = $sum % 11;
        $digit1 = ($remainder < 2) ? 0 : (11 - $remainder);
        $cpf .= $digit1;

        // Calcula o segundo dígito verificador
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += ((int) $cpf[$i]) * (11 - $i);
        }
        $remainder = $sum % 11;
        $digit2 = ($remainder < 2) ? 0 : (11 - $remainder);
        $cpf .= $digit2;

        return $cpf;
    }
}
