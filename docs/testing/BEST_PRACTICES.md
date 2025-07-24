# Guia de Boas Práticas para Testes

Este documento estabelece as diretrizes e padrões para criação e manutenção de testes no projeto.

## 🎯 Princípios Fundamentais

### 1. **AAA Pattern** - Arrange, Act, Assert

```php
public function test_can_create_paciente()
{
    // Arrange - Preparar dados
    $userData = User::factory()->create();
    $this->actingAs($userData);
    $pacienteData = [
        'name' => 'João',
        'surname' => 'Silva',
        'email' => 'joao@test.com',
        'cpf' => '12345678901'
    ];

    // Act - Executar ação
    $response = $this->post('/pacientes', $pacienteData);

    // Assert - Verificar resultado
    $response->assertRedirect('/pacientes');
    $this->assertDatabaseHas('pacientes', ['email' => 'joao@test.com']);
}
```

### 2. **F.I.R.S.T Principles**

-   **Fast** - Testes devem executar rapidamente
-   **Independent** - Testes não devem depender uns dos outros
-   **Repeatable** - Resultados consistentes em qualquer ambiente
-   **Self-Validating** - Clear pass/fail sem verificação manual
-   **Timely** - Escritos junto com o código de produção

## 📁 Organização e Nomenclatura

### Estrutura de Diretórios

```
tests/
├── Unit/                     # Testes unitários - Classes isoladas
│   ├── Models/              # Testes de Models
│   ├── Services/            # Testes de Services
│   └── Helpers/             # Testes de Helpers
├── Feature/                 # Testes de integração - HTTP/Database
│   ├── Auth/               # Autenticação
│   ├── Api/                # Endpoints API
│   ├── Console/            # Comandos Artisan
│   └── Controllers/        # Controllers específicos
└── Browser/                # Testes E2E (Dusk) - se necessário
```

### Nomenclatura de Métodos

```php
// ✅ Bom - Descritivo e claro
test_can_create_paciente_with_valid_data()
test_throws_exception_when_cpf_is_invalid()
test_returns_full_name_attribute()

// ❌ Ruim - Vago ou confuso
test_paciente()
test_create()
test_validation()
```

### Nomenclatura de Classes

```php
// ✅ Para Models (Unit)
class PacienteTest extends TestCase

// ✅ Para Controllers (Feature)
class PacienteControllerTest extends TestCase

// ✅ Para Services (Unit)
class UserServiceTest extends TestCase
```

## 🏗️ Padrões de Implementação

### Setup e Teardown

```php
class ControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup comum para todos os testes da classe
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    protected function tearDown(): void
    {
        // Limpeza se necessário
        parent::tearDown();
    }
}
```

### Uso de Factories

```php
// ✅ Bom - Usar factories para dados de teste
$user = User::factory()->create();
$paciente = Paciente::factory()->create(['user_id' => $user->id]);

// ✅ Override apenas campos necessários
$paciente = Paciente::factory()->create([
    'cpf' => '12345678901',
    'email' => 'specific@test.com'
]);

// ❌ Evitar - Arrays manuais extensos
$paciente = Paciente::create([
    'name' => 'Test',
    'surname' => 'User',
    'birthdate' => '1990-01-01',
    // ... muitos campos
]);
```

### Assertions Específicas

```php
// ✅ Inertia Assertions
$response->assertInertia(
    fn ($page) => $page
        ->component('Pacientes/Show')
        ->has('paciente')
        ->where('paciente.id', $paciente->id)
);

// ✅ Database Assertions
$this->assertDatabaseHas('pacientes', ['cpf' => '12345678901']);
$this->assertDatabaseMissing('users', ['email' => 'deleted@test.com']);

// ✅ Response Assertions
$response->assertStatus(200);
$response->assertRedirect('/pacientes');
$response->assertSessionHasErrors(['name', 'email']);
```

## 🔧 Testes por Tipo de Componente

### Models (Unit Tests)

```php
class PacienteTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_correct_fillable_attributes()
    {
        $expected = ['name', 'surname', 'email', 'cpf'];
        $actual = (new Paciente)->getFillable();

        $this->assertEquals($expected, $actual);
    }

    public function test_casts_birthdate_to_date()
    {
        $paciente = Paciente::factory()->create([
            'birthdate' => '1990-01-01'
        ]);

        $this->assertInstanceOf(Carbon::class, $paciente->birthdate);
    }

    public function test_full_name_accessor()
    {
        $paciente = Paciente::factory()->create([
            'name' => 'João',
            'surname' => 'Silva'
        ]);

        $this->assertEquals('João Silva', $paciente->full_name);
    }
}
```

### Controllers (Feature Tests)

```php
class PacienteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    public function test_index_displays_pacientes()
    {
        $pacientes = Paciente::factory()->count(3)->create();

        $response = $this->get('/pacientes');

        $response->assertStatus(200);
        $response->assertInertia(
            fn ($page) => $page
                ->component('Pacientes/Index')
                ->has('pacientes', 3)
        );
    }

    public function test_store_validates_required_fields()
    {
        $response = $this->post('/pacientes', []);

        $response->assertSessionHasErrors([
            'name', 'surname', 'email', 'cpf'
        ]);
    }
}
```

### Commands (Feature Tests)

```php
class GenerateReportCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_report_successfully()
    {
        // Arrange
        User::factory()->count(5)->create();

        // Act
        $this->artisan('report:generate')
             ->expectsOutput('Report generated successfully')
             ->assertSuccessful();

        // Assert
        $this->assertFileExists(storage_path('app/reports/users.pdf'));
    }
}
```

## 🎭 Mocking e Doubles

### Mocking Services Externos

```php
public function test_sends_email_notification()
{
    // Arrange
    Mail::fake();
    $user = User::factory()->create();

    // Act
    $this->post('/send-welcome-email', ['user_id' => $user->id]);

    // Assert
    Mail::assertSent(WelcomeEmail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
}
```

### HTTP Client Mocking

```php
public function test_external_api_integration()
{
    // Arrange
    Http::fake([
        'api.external.com/*' => Http::response(['status' => 'success'], 200)
    ]);

    // Act
    $result = $this->service->callExternalApi();

    // Assert
    $this->assertEquals('success', $result['status']);
    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.external.com/endpoint';
    });
}
```

## 📊 Performance e Otimização

### Otimizações de Banco

```php
// ✅ Use transações para testes rápidos
use DatabaseTransactions; // Em vez de RefreshDatabase quando possível

// ✅ Use memória para testes que não precisam persistir
// Em .env.testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### Fixtures vs Factories

```php
// ✅ Factories para maioria dos casos
$users = User::factory()->count(10)->create();

// ✅ Fixtures para dados específicos e complexos
// tests/fixtures/sample-users.json
$users = $this->loadFixture('sample-users.json');
```

## 🚨 Tratamento de Erros

### Testes de Validação

```php
public function test_validates_cpf_format()
{
    $response = $this->post('/pacientes', [
        'name' => 'João',
        'cpf' => 'invalid-cpf'
    ]);

    $response->assertSessionHasErrors(['cpf']);
    $this->assertDatabaseMissing('pacientes', ['name' => 'João']);
}
```

### Testes de Exception

```php
public function test_throws_exception_for_duplicate_cpf()
{
    // Arrange
    Paciente::factory()->create(['cpf' => '12345678901']);

    // Act & Assert
    $this->expectException(ValidationException::class);

    Paciente::create([
        'name' => 'Outro',
        'cpf' => '12345678901'
    ]);
}
```

## 🔍 Data Providers

### Testes Parametrizados

```php
/**
 * @dataProvider invalidCpfProvider
 */
public function test_rejects_invalid_cpf($invalidCpf)
{
    $response = $this->post('/pacientes', [
        'name' => 'Test',
        'cpf' => $invalidCpf
    ]);

    $response->assertSessionHasErrors(['cpf']);
}

public static function invalidCpfProvider(): array
{
    return [
        'too_short' => ['123456789'],
        'too_long' => ['123456789012'],
        'non_numeric' => ['abcdefghijk'],
        'empty' => [''],
    ];
}
```

## 🏷️ Tags e Grupos

### Organização com @group

```php
/**
 * @group slow
 * @group integration
 */
public function test_complex_report_generation()
{
    // Teste que demora mais para executar
}

/**
 * @group unit
 * @group fast
 */
public function test_simple_calculation()
{
    // Teste rápido
}
```

### Execução Seletiva

```bash
# Executar apenas testes rápidos
vendor/bin/phpunit --group fast

# Excluir testes lentos
vendor/bin/phpunit --exclude-group slow
```

## 📈 Métricas e Qualidade

### Coverage Annotations

```php
/**
 * @covers App\Models\Paciente::getFullNameAttribute
 */
public function test_full_name_attribute()
{
    // Teste específico para coverage
}
```

### Benchmarking

```php
public function test_bulk_insert_performance()
{
    $startTime = microtime(true);

    // Operação a ser testada
    Paciente::factory()->count(1000)->create();

    $endTime = microtime(true);
    $executionTime = $endTime - $startTime;

    $this->assertLessThan(5, $executionTime, 'Bulk insert should complete in under 5 seconds');
}
```

## 🔄 Continuous Integration

### Pre-commit Hooks

```bash
#!/bin/sh
# .git/hooks/pre-commit

echo "Running tests..."
vendor/bin/phpunit --stop-on-failure

if [ $? -ne 0 ]; then
    echo "Tests failed. Commit aborted."
    exit 1
fi
```

### Pipeline Stages

```yaml
# GitHub Actions example
test:
    strategy:
        matrix:
            php: [8.1, 8.2]
    steps:
        - name: Unit Tests
          run: vendor/bin/phpunit --testsuite=Unit

        - name: Feature Tests
          run: vendor/bin/phpunit --testsuite=Feature

        - name: Coverage Report
          run: vendor/bin/phpunit --coverage-clover coverage.xml
```

## 📝 Documentação de Testes

### Comentários Descritivos

```php
/**
 * Testa se o sistema consegue processar um paciente com dados válidos
 * incluindo validação de CPF único e envio de email de boas-vindas.
 *
 * @test
 * @group integration
 */
public function sistema_processa_paciente_valido_completamente()
{
    // Implementação
}
```

### README por Feature

````markdown
# Tests/Feature/Auth/README.md

## Testes de Autenticação

Esta pasta contém testes para todo o fluxo de autenticação:

-   Login/Logout
-   Registro de usuários
-   Reset de senha
-   Verificação de email

### Como executar apenas estes testes:

```bash
vendor/bin/phpunit tests/Feature/Auth/
```
````

## ⚡ Comandos Úteis

### Execução e Debug

```bash
# Teste específico com debug
vendor/bin/phpunit --filter test_method --debug

# Parar no primeiro erro
vendor/bin/phpunit --stop-on-failure

# Executar com seed específico para reproduzir falhas
vendor/bin/phpunit --random-order-seed=12345

# Verbose output
vendor/bin/phpunit --testdox-text
```

### Criação de Testes

```bash
# Unit test
php artisan make:test Models/PacienteTest --unit

# Feature test
php artisan make:test Controllers/PacienteControllerTest

# Com métodos pré-definidos
php artisan make:test PacienteTest --unit --methods=test_validation,test_creation
```

---

## 🎯 Checklist de Qualidade

Antes de fazer commit, verifique:

-   [ ] ✅ Todos os testes passando
-   [ ] ✅ Nomes descritivos e claros
-   [ ] ✅ Uso adequado de factories
-   [ ] ✅ Assertions específicas e relevantes
-   [ ] ✅ Setup/teardown apropriados
-   [ ] ✅ Casos de borda cobertos
-   [ ] ✅ Validações testadas
-   [ ] ✅ Mocks utilizados adequadamente
-   [ ] ✅ Performance aceitável (<10s total)
-   [ ] ✅ Documentação atualizada se necessário

**Lembre-se**: Testes são código de produção também. Mantenha-os limpos, organizados e bem documentados!
