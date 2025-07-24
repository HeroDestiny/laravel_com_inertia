# Troubleshooting - Problemas Comuns em Testes

Este documento lista problemas comuns encontrados durante execução de testes e suas soluções.

## 🚨 Problemas de Configuração

### 1. **Erro: "Class 'Tests\TestCase' not found"**

**Problema**: Autoload não configurado corretamente.

**Solução**:

```bash
# Regenerar autoload
composer dump-autoload

# Verificar composer.json
"autoload-dev": {
    "psr-4": {
        "Tests\\": "tests/"
    }
}
```

### 2. **Erro: "Database [testing] does not exist"**

**Problema**: Configuração de banco de teste incorreta.

**Solução**:

```bash
# Verificar .env.testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:

# Ou usar arquivo SQLite
DB_DATABASE=database/testing.sqlite

# Criar arquivo se necessário
touch database/testing.sqlite
```

### 3. **Erro: "APP_KEY is missing"**

**Problema**: Chave de aplicação não definida para testes.

**Solução**:

```bash
# No .env.testing, adicionar uma chave válida
APP_KEY=base64:GENERATED_KEY_HERE

# Ou gerar nova chave
php artisan key:generate --env=testing
```

## 🔧 Problemas de Execução

### 1. **Testes Lentos (>30 segundos)**

**Problema**: Configuração inadequada ou uso excessivo de RefreshDatabase.

**Soluções**:

```php
// ✅ Use transações quando possível
use DatabaseTransactions;

// ✅ Em vez de RefreshDatabase para testes rápidos
use RefreshDatabase; // Apenas quando necessário

// ✅ Configurar SQLite em memória
// .env.testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
CACHE_DRIVER=array
SESSION_DRIVER=array
```

### 2. **Erro: "Too many connections"**

**Problema**: Conexões de banco não fechadas adequadamente.

**Solução**:

```php
// ✅ No TestCase.php
protected function tearDown(): void
{
    DB::disconnect();
    parent::tearDown();
}

// ✅ Ou usar SQLite para testes
// .env.testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### 3. **Memory Limit Exceeded**

**Problema**: Testes consumindo muita memória.

**Soluções**:

```bash
# Aumentar limite temporariamente
php -d memory_limit=512M vendor/bin/phpunit

# Ou no phpunit.xml
<php>
    <ini name="memory_limit" value="512M"/>
</php>
```

## 🎭 Problemas com Mocks

### 1. **Mock não funciona como esperado**

**Problema**: Mock criado após o objeto ser instanciado.

**Solução**:

```php
// ❌ Errado - Mock criado tarde demais
public function test_service_call()
{
    $service = new ExternalService();
    Http::fake(); // Mock criado após instância

    $result = $service->call();
}

// ✅ Correto - Mock criado antes
public function test_service_call()
{
    Http::fake(); // Mock criado primeiro

    $service = new ExternalService();
    $result = $service->call();
}
```

### 2. **Mail::fake() não intercepta emails**

**Problema**: Configuração de mail não adequada para testes.

**Solução**:

```php
// ✅ Configurar corretamente
public function test_sends_email()
{
    Mail::fake();

    // Verificar configuração
    $this->assertEquals('array', config('mail.driver'));

    // Executar ação
    $this->post('/send-email');

    // Verificar
    Mail::assertSent(WelcomeEmail::class);
}
```

## 🔐 Problemas de Autenticação

### 1. **Middleware de autenticação bloqueia testes**

**Problema**: Rota protegida sem usuário autenticado.

**Solução**:

```php
// ✅ Autenticar usuário no teste
public function test_protected_route()
{
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/protected-route');
    $response->assertStatus(200);
}

// ✅ Ou no setUp para todos os testes
protected function setUp(): void
{
    parent::setUp();
    $this->actingAs(User::factory()->create());
}
```

### 2. **Session/CSRF issues**

**Problema**: Testes falhando por problemas de sessão.

**Solução**:

```php
// ✅ Usar withoutMiddleware para CSRF
$this->withoutMiddleware(VerifyCsrfToken::class);

// ✅ Ou inicializar sessão
$this->startSession();

// ✅ Para formulários Inertia
$response = $this->post('/route', $data, [
    'X-Inertia' => true,
    'X-Inertia-Version' => '1.0'
]);
```

## 📊 Problemas com Factories

### 1. **Factory não encontrada**

**Problema**: Factory não registrada ou arquivo não encontrado.

**Solução**:

```php
// ✅ Verificar se factory existe
// database/factories/PacienteFactory.php
class PacienteFactory extends Factory
{
    protected $model = Paciente::class;

    public function definition()
    {
        return [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            // ...
        ];
    }
}

// ✅ No model
class Paciente extends Model
{
    use HasFactory;
}
```

### 2. **Dados de factory inválidos**

**Problema**: Factory gerando dados que não passam na validação.

**Solução**:

```php
// ✅ Factory com dados válidos
public function definition()
{
    return [
        'name' => $this->faker->firstName,
        'email' => $this->faker->unique()->safeEmail,
        'cpf' => $this->faker->unique()->numerify('###########'),
        'birthdate' => $this->faker->dateTimeBetween('-80 years', '-18 years'),
    ];
}

// ✅ Estados específicos
public function adult()
{
    return $this->state(fn () => [
        'birthdate' => $this->faker->dateTimeBetween('-65 years', '-18 years')
    ]);
}
```

## 🎯 Problemas com Inertia

### 1. **Assertions Inertia falhando**

**Problema**: Estrutura de dados não confere com expectativa.

**Solução**:

```php
// ✅ Debug da resposta primeiro
public function test_inertia_response()
{
    $response = $this->get('/pacientes');

    // Debug para ver estrutura
    dd($response->inertia());

    // Então fazer assertion
    $response->assertInertia(
        fn ($page) => $page
            ->component('Pacientes/Index')
            ->has('pacientes')
    );
}
```

### 2. **Componente Inertia não carregado**

**Problema**: Teste não reconhece como resposta Inertia.

**Solução**:

```php
// ✅ Headers corretos para Inertia
$response = $this->get('/route', [
    'X-Inertia' => true,
    'Accept' => 'text/html, application/xhtml+xml',
]);

// ✅ Verificar se é resposta Inertia
$this->assertTrue($response->headers->has('X-Inertia'));
```

## 🗄️ Problemas de Banco de Dados

### 1. **RefreshDatabase não limpa dados**

**Problema**: Dados persistindo entre testes.

**Solução**:

```php
// ✅ Usar RefreshDatabase corretamente
use RefreshDatabase;

// ✅ Ou limpar manualmente no tearDown
protected function tearDown(): void
{
    DB::table('pacientes')->truncate();
    parent::tearDown();
}

// ✅ Verificar se migrations estão funcionando
php artisan migrate:status --env=testing
```

### 2. **Foreign key constraints**

**Problema**: Erro de chave estrangeira ao criar dados de teste.

**Solução**:

```php
// ✅ Criar dependências primeiro
$user = User::factory()->create();
$paciente = Paciente::factory()->create(['user_id' => $user->id]);

// ✅ Ou usar relacionamentos na factory
public function definition()
{
    return [
        'user_id' => User::factory(),
        'name' => $this->faker->name,
    ];
}
```

## ⚡ Problemas de Performance

### 1. **Testes muito lentos**

**Diagnóstico**:

```bash
# Executar com timing
vendor/bin/phpunit --debug

# Profile específico
vendor/bin/phpunit --filter slow_test --debug
```

**Soluções**:

```php
// ✅ Usar create() em vez de factory()->create() quando possível
User::factory()->create(); // Mais lento
$user = new User(User::factory()->raw()); // Mais rápido se não precisar persistir

// ✅ Evitar múltiplas RefreshDatabase
use DatabaseTransactions; // Mais rápido que RefreshDatabase

// ✅ Batch operations
User::factory()->count(100)->create(); // Mais rápido que loop
```

## 🔍 Debug e Diagnóstico

### 1. **Como debugar teste que falha esporadicamente**

```bash
# Executar múltiplas vezes
for i in {1..10}; do vendor/bin/phpunit --filter failing_test; done

# Com seed específico para reproduzir
vendor/bin/phpunit --filter failing_test --random-order-seed=12345

# Debug verboso
vendor/bin/phpunit --filter failing_test --debug --verbose
```

### 2. **Inspecionar dados durante teste**

```php
public function test_with_debug()
{
    $paciente = Paciente::factory()->create();

    // Debug dados
    dump($paciente->toArray());

    // Debug query
    DB::enableQueryLog();
    $this->get('/pacientes');
    dd(DB::getQueryLog());

    // Debug response
    $response = $this->get('/pacientes');
    dump($response->getContent());
}
```

### 3. **Ray Debug (se disponível)**

```php
// Instalar ray
composer require spatie/ray --dev

// Usar nos testes
public function test_with_ray()
{
    $data = ['test' => 'data'];
    ray($data); // Aparece no Ray app

    $response = $this->get('/test');
    ray($response->getContent());
}
```

## 🛠️ Ferramentas de Diagnóstico

### 1. **PHPUnit Verbose Output**

```bash
# Máximo detalhe
vendor/bin/phpunit --testdox --verbose --debug

# Com cores
vendor/bin/phpunit --colors=always

# Stop on first failure
vendor/bin/phpunit --stop-on-failure
```

### 2. **Coverage para identificar gaps**

```bash
# Se Xdebug instalado
vendor/bin/phpunit --coverage-text --coverage-filter=app

# HTML report
vendor/bin/phpunit --coverage-html coverage/
```

### 3. **Profiling com Blackfire (avançado)**

```bash
# Profile específico
blackfire run vendor/bin/phpunit --filter slow_test
```

## 📋 Checklist de Troubleshooting

Quando um teste falha, verifique na ordem:

1. **Configuração**:

    - [ ] .env.testing correto
    - [ ] APP_KEY definida
    - [ ] Database configurado

2. **Dados**:

    - [ ] Factories funcionando
    - [ ] RefreshDatabase/DatabaseTransactions
    - [ ] Foreign keys respeitadas

3. **Autenticação**:

    - [ ] Usuário autenticado se necessário
    - [ ] Middleware corretos

4. **Mocks**:

    - [ ] Criados antes da execução
    - [ ] Configurados corretamente

5. **Estrutura**:
    - [ ] Autoload atualizado
    - [ ] Namespaces corretos
    - [ ] Métodos nomeados adequadamente

## 🆘 Comandos de Emergência

```bash
# Reset completo
composer dump-autoload
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Recriar banco de teste
php artisan migrate:fresh --env=testing

# Verificar configuração de teste
php artisan config:show database --env=testing

# Test específico com máximo debug
vendor/bin/phpunit tests/Unit/ExampleTest.php --debug --verbose --stop-on-failure
```

---

## 🎯 Dicas Preventivas

1. **Execute testes frequentemente** - não deixe acumular problemas
2. **Use .env.testing** - nunca teste contra banco de produção
3. **Factories bem definidas** - dados consistentes evitam problemas
4. **Testes isolados** - cada teste deve funcionar independentemente
5. **CI/CD configurado** - problemas detectados automaticamente

**Lembre-se**: Se um teste falha esporadicamente, geralmente é problema de isolamento ou dependência de estado. Investigate!
