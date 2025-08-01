<?php

namespace Tests\Feature;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PacienteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate a user for testing
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_can_view_pacientes_index()
    {
        // Create some test patients
        Paciente::factory()->count(3)->create();

        $response = $this->get('/pacientes');

        $response->assertStatus(200);
        // Just check that we get an Inertia response with the correct data
        $response->assertInertia(
            fn ($page) => $page->has('pacientes', 3)
        );
    }

    public function test_can_view_create_paciente_form()
    {
        $response = $this->get('/pacientes/create');

        $response->assertStatus(200);
        // Just check that we get a successful response
    }

    public function test_can_store_new_paciente()
    {
        $pacienteData = [
            'name' => 'João',
            'surname' => 'Silva',
            'birthdate' => '1990-01-01',
            'cpf' => '11144477735', // CPF válido
            'role' => 'Patient',
            'education' => 'Superior',
            'mother_name' => 'Maria Silva',
            'email' => 'joao@example.com',
        ];

        $response = $this->post('/pacientes', $pacienteData);

        $response->assertRedirect('/pacientes');

        $this->assertDatabaseHas('pacientes', [
            'name' => 'João',
            'surname' => 'Silva',
            'cpf' => '11144477735',
            'mother_name' => 'Maria Silva',
            'email' => 'joao@example.com',
        ]);
    }

    public function test_can_show_paciente()
    {
        $paciente = Paciente::factory()->create();

        $response = $this->get("/pacientes/{$paciente->id}");

        $response->assertStatus(200);
        $response->assertInertia(
            fn ($page) => $page->has('paciente')
                ->where('paciente.id', $paciente->id)
        );
    }

    public function test_can_view_edit_paciente_form()
    {
        $paciente = Paciente::factory()->create();

        $response = $this->get("/pacientes/{$paciente->id}/edit");

        $response->assertStatus(200);
        $response->assertInertia(
            fn ($page) => $page->has('paciente')
                ->where('paciente.id', $paciente->id)
        );
    }

    public function test_can_update_paciente()
    {
        $paciente = Paciente::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'surname' => 'Updated Surname',
            'birthdate' => '1985-05-05',
            'cpf' => $paciente->cpf, // Keep same CPF
            'role' => 'Updated Role',
            'education' => 'Updated Education',
            'mother_name' => 'Updated Mother',
            'email' => $paciente->email, // Keep same email
        ];

        $response = $this->put("/pacientes/{$paciente->id}", $updateData);

        $response->assertRedirect('/pacientes');

        $this->assertDatabaseHas('pacientes', [
            'id' => $paciente->id,
            'name' => 'Updated Name',
            'surname' => 'Updated Surname',
            'mother_name' => 'Updated Mother',
        ]);
    }

    public function test_can_delete_paciente()
    {
        $paciente = Paciente::factory()->create();

        $response = $this->delete("/pacientes/{$paciente->id}");

        $response->assertRedirect('/pacientes');

        $this->assertDatabaseMissing('pacientes', [
            'id' => $paciente->id,
        ]);
    }

    public function test_store_validates_required_fields()
    {
        $response = $this->post('/pacientes', []);

        $response->assertSessionHasErrors([
            'name',
            'surname',
            'birthdate',
            'cpf',
            'mother_name',
            'email',
        ]);
    }

    public function test_store_validates_unique_cpf()
    {
        $existingPaciente = Paciente::factory()->create();

        $pacienteData = [
            'name' => 'João',
            'surname' => 'Silva',
            'birthdate' => '1990-01-01',
            'cpf' => $existingPaciente->cpf, // Use existing CPF
            'role' => 'Patient',
            'education' => 'Superior',
            'mother_name' => 'Maria Silva',
            'email' => 'joao@example.com',
        ];

        $response = $this->post('/pacientes', $pacienteData);

        $response->assertSessionHasErrors(['cpf']);
    }

    public function test_store_validates_unique_email()
    {
        $existingPaciente = Paciente::factory()->create();

        $pacienteData = [
            'name' => 'João',
            'surname' => 'Silva',
            'birthdate' => '1990-01-01',
            'cpf' => '12345678901',
            'role' => 'Patient',
            'education' => 'Superior',
            'mother_name' => 'Maria Silva',
            'email' => $existingPaciente->email, // Use existing email
        ];

        $response = $this->post('/pacientes', $pacienteData);

        $response->assertSessionHasErrors(['email']);
    }
}
