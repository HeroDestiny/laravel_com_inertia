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
            'cpf' => $this->faker->unique()->numerify('###########'), // 11 digits
            'role' => $this->faker->optional()->jobTitle,
            'education' => $this->faker->optional()->randomElement(['Fundamental', 'Médio', 'Superior', 'Pós-graduação']),
            'mother_name' => $this->faker->name('female'),
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
