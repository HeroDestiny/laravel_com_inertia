<?php

namespace Tests\Unit;

use App\Models\Paciente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PacienteTest extends TestCase
{
  use RefreshDatabase;

  public function test_paciente_has_fillable_attributes()
  {
    $fillable = [
      'name',
      'surname',
      'birthdate',
      'cpf',
      'role',
      'education',
      'mother_name',
      'email',
    ];

    $paciente = new Paciente();

    $this->assertEquals($fillable, $paciente->getFillable());
  }

  public function test_paciente_casts_birthdate_to_date()
  {
    $paciente = new Paciente();

    $this->assertEquals('date', $paciente->getCasts()['birthdate']);
  }

  public function test_validation_rules_are_correct()
  {
    $rules = Paciente::rules();

    $this->assertArrayHasKey('name', $rules);
    $this->assertArrayHasKey('surname', $rules);
    $this->assertArrayHasKey('birthdate', $rules);
    $this->assertArrayHasKey('cpf', $rules);
    $this->assertArrayHasKey('role', $rules);
    $this->assertArrayHasKey('education', $rules);
    $this->assertArrayHasKey('mother_name', $rules);
    $this->assertArrayHasKey('email', $rules);

    // Check specific rule patterns
    $this->assertStringContainsString('required', $rules['name']);
    $this->assertStringContainsString('required', $rules['surname']);
    $this->assertStringContainsString('required', $rules['birthdate']);
    $this->assertStringContainsString('required', $rules['cpf']);
    $this->assertStringContainsString('nullable', $rules['role']);
    $this->assertStringContainsString('nullable', $rules['education']);
    $this->assertStringContainsString('required', $rules['mother_name']);
    $this->assertStringContainsString('required', $rules['email']);
  }

  public function test_get_full_name_attribute()
  {
    $paciente = new Paciente([
      'name' => 'João',
      'surname' => 'Silva',
    ]);

    $this->assertEquals('João Silva', $paciente->getFullNameAttribute());
  }

  public function test_can_create_paciente_with_factory()
  {
    $paciente = Paciente::factory()->create();

    $this->assertInstanceOf(Paciente::class, $paciente);
    $this->assertNotNull($paciente->name);
    $this->assertNotNull($paciente->surname);
    $this->assertNotNull($paciente->birthdate);
    $this->assertNotNull($paciente->cpf);
    $this->assertNotNull($paciente->mother_name);
    $this->assertNotNull($paciente->email);
  }

  public function test_can_create_paciente_with_specific_data()
  {
    $data = [
      'name' => 'Maria',
      'surname' => 'Santos',
      'birthdate' => '1985-03-15',
      'cpf' => '12345678901',
      'role' => 'Patient',
      'education' => 'Superior',
      'mother_name' => 'Ana Santos',
      'email' => 'maria@example.com',
    ];

    $paciente = Paciente::create($data);

    $this->assertEquals('Maria', $paciente->name);
    $this->assertEquals('Santos', $paciente->surname);
    $this->assertEquals('1985-03-15', $paciente->birthdate->format('Y-m-d'));
    $this->assertEquals('12345678901', $paciente->cpf);
    $this->assertEquals('Patient', $paciente->role);
    $this->assertEquals('Superior', $paciente->education);
    $this->assertEquals('Ana Santos', $paciente->mother_name);
    $this->assertEquals('maria@example.com', $paciente->email);
  }

  public function test_birthdate_is_cast_to_carbon_date()
  {
    $paciente = Paciente::factory()->create([
      'birthdate' => '1990-01-01',
    ]);

    $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $paciente->birthdate);
    $this->assertEquals('1990-01-01', $paciente->birthdate->format('Y-m-d'));
  }

  public function test_can_update_paciente()
  {
    $paciente = Paciente::factory()->create();

    $originalName = $paciente->name;

    $paciente->update(['name' => 'Updated Name']);

    $this->assertNotEquals($originalName, $paciente->name);
    $this->assertEquals('Updated Name', $paciente->name);
  }

  public function test_can_delete_paciente()
  {
    $paciente = Paciente::factory()->create();
    $pacienteId = $paciente->id;

    $paciente->delete();

    $this->assertDatabaseMissing('pacientes', ['id' => $pacienteId]);
  }
}
