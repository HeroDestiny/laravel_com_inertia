# Guia de Desenvolvimento

Guia completo para desenvolvimento no projeto Laravel com Inertia.js.

## Ambiente de Desenvolvimento

### Ferramentas Necessárias

-   **VS Code**: Editor recomendado com extensões específicas
-   **Git**: Para controle de versão
-   **Docker**: Para DevContainer (opcional)
-   **Terminal**: Bash, Zsh ou equivalente

### Extensões VS Code Recomendadas

```json
{
    "recommendations": [
        "ms-vscode-remote.remote-containers",
        "bmewburn.vscode-intelephense-client",
        "vue.volar",
        "bradlc.vscode-tailwindcss",
        "ms-vscode.vscode-typescript-next",
        "esbenp.prettier-vscode",
        "dbaeumer.vscode-eslint",
        "mikestead.dotenv"
    ]
}
```

## Estrutura de Desenvolvimento

### Organização de Código

#### Backend (Laravel)

```
src/app/
├── Console/                   # Comandos Artisan
│   └── Commands/             # Comandos customizados
├── Http/                     # Camada HTTP
│   ├── Controllers/          # Controladores
│   ├── Middleware/          # Middleware customizado
│   ├── Requests/            # Form requests
│   └── Resources/           # API resources
├── Models/                  # Modelos Eloquent
├── Providers/               # Service providers
├── Services/                # Serviços de negócio
├── Events/                  # Eventos do sistema
├── Listeners/               # Listeners de eventos
├── Jobs/                    # Jobs para filas
├── Mail/                    # Classes de email
└── Policies/                # Políticas de autorização
```

#### Frontend (Vue.js)

```
src/resources/js/
├── Components/              # Componentes reutilizáveis
│   ├── Forms/              # Componentes de formulário
│   ├── Layout/             # Componentes de layout
│   └── UI/                 # Componentes de interface
├── Pages/                  # Páginas da aplicação
│   ├── Auth/               # Páginas de autenticação
│   ├── Dashboard/          # Dashboard
│   └── Pacientes/          # Gestão de pacientes
├── Layouts/                # Layouts base
├── Composables/            # Composables Vue
├── Utils/                  # Funções utilitárias
├── Types/                  # Definições TypeScript
└── app.ts                  # Entry point
```

## Fluxo de Desenvolvimento

### 1. Configuração Inicial

#### DevContainer

```bash
# 1. Abrir projeto no VS Code
code .

# 2. Reabrir no container quando solicitado
# Ctrl+Shift+P > "Dev Containers: Reopen in Container"

# 3. Aguardar setup automático
```

#### Manual

```bash
# 1. Instalar dependências
composer install
npm install

# 2. Configurar ambiente
cp .env.example .env
php artisan key:generate

# 3. Configurar banco
php artisan migrate --seed

# 4. Iniciar servidores
php artisan serve --host=0.0.0.0 --port=8000  # Terminal 1
npm run dev                                    # Terminal 2
```

### 2. Desenvolvimento Daily

#### Comandos Essenciais

```bash
# Servidor Laravel
php artisan serve --host=0.0.0.0

# Servidor Vite (desenvolvimento)
npm run dev

# Build para produção
npm run build

# Testes
php artisan test
npm run test:js

# Linting e formatação
npm run lint
npm run format

# Verificação de tipos
npm run type-check
```

#### Tasks do VS Code

Use `Ctrl+Shift+P > Tasks: Run Task`:

-   **Laravel: Serve** - Inicia servidor Laravel
-   **Vite: Dev Server** - Inicia servidor de desenvolvimento
-   **Run Tests** - Executa todos os testes
-   **Lint & Format** - Executa linting e formatação

### 3. Workflow de Feature

#### Criação de Feature

```bash
# 1. Criar branch
git checkout -b feature/nome-da-feature

# 2. Desenvolver (seguir padrões abaixo)

# 3. Testar
php artisan test
npm run test:js

# 4. Commit
git add .
git commit -m "feat: adiciona nova funcionalidade"

# 5. Push e PR
git push origin feature/nome-da-feature
```

## Padrões de Código

### PHP (Laravel)

#### Padrões PSR-12

```php
<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Http\Requests\PacienteRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PacienteController extends Controller
{
    /**
     * Display a listing of patients.
     */
    public function index(Request $request): Response
    {
        $pacientes = Paciente::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Pacientes/Index', [
            'pacientes' => $pacientes,
            'filters' => $request->only(['search']),
        ]);
    }
}
```

#### Model Patterns

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paciente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'surname',
        'birthdate',
        'cpf',
        'role',
        'education',
        'mother_name',
        'email',
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return "{$this->name} {$this->surname}";
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        });
    }
}
```

### TypeScript (Vue.js)

#### Component Pattern

```vue
<template>
    <div class="paciente-form">
        <form @submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <InputLabel for="name" value="Nome" />
                    <TextInput id="name" v-model="form.name" type="text" required autofocus :error="form.errors.name" />
                    <InputError :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="surname" value="Sobrenome" />
                    <TextInput id="surname" v-model="form.surname" type="text" required :error="form.errors.surname" />
                    <InputError :message="form.errors.surname" />
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <PrimaryButton :disabled="form.processing">
                    {{ form.processing ? 'Salvando...' : 'Salvar' }}
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Paciente } from '@/types/global';

interface Props {
    paciente?: Paciente;
}

const props = defineProps<Props>();

const form = useForm({
    name: props.paciente?.name ?? '',
    surname: props.paciente?.surname ?? '',
    email: props.paciente?.email ?? '',
    cpf: props.paciente?.cpf ?? '',
    birthdate: props.paciente?.birthdate ?? '',
    mother_name: props.paciente?.mother_name ?? '',
    role: props.paciente?.role ?? '',
    education: props.paciente?.education ?? '',
});

const submit = () => {
    if (props.paciente) {
        form.put(route('pacientes.update', props.paciente.id));
    } else {
        form.post(route('pacientes.store'));
    }
};
</script>
```

#### Composable Pattern

```typescript
// composables/usePacientes.ts
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { Paciente } from '@/types/global';

export function usePacientes() {
    const loading = ref(false);
    const search = ref('');

    const filteredPacientes = computed(() => {
        // Logic for filtering
    });

    const searchPacientes = (query: string) => {
        router.get(
            route('pacientes.index'),
            { search: query },
            {
                preserveState: true,
                replace: true,
                onStart: () => (loading.value = true),
                onFinish: () => (loading.value = false),
            }
        );
    };

    const deletePaciente = (paciente: Paciente) => {
        if (confirm(`Deseja excluir ${paciente.name}?`)) {
            router.delete(route('pacientes.destroy', paciente.id));
        }
    };

    return {
        loading,
        search,
        filteredPacientes,
        searchPacientes,
        deletePaciente,
    };
}
```

## Ferramentas de Desenvolvimento

### Debugging

#### PHP Debugging

```php
// Xdebug configuration (php.ini)
xdebug.mode = debug
xdebug.start_with_request = yes
xdebug.client_host = host.docker.internal
xdebug.client_port = 9003

// Debug helpers
dd($variable);                    // Dump and die
dump($variable);                  // Dump
logger()->info('Debug message');  // Log
\DB::enableQueryLog();           // Query log
```

#### Frontend Debugging

```typescript
// Vue DevTools
app.config.devtools = true;

// Console debugging
console.log('Debug:', data);
console.table(array);
console.group('Group');

// Error boundary
app.config.errorHandler = (err, instance, info) => {
    console.error('Vue error:', err, info);
};
```

### Análise de Código

#### PHPStan

```bash
# Executar análise
vendor/bin/phpstan analyse

# Configuração (phpstan.neon)
parameters:
    level: 9
    paths:
        - app
    ignoreErrors:
        - '#Unsafe usage of new static#'
```

#### ESLint

```bash
# Executar linting
npm run lint

# Autofix
npm run lint -- --fix

# Configuração (eslint.config.js)
export default [
    {
        files: ['**/*.{js,ts,vue}'],
        rules: {
            'no-console': 'warn',
            'no-unused-vars': 'error',
        },
    },
];
```

### Performance

#### Laravel Performance

```bash
# Cache de configuração
php artisan config:cache

# Cache de rotas
php artisan route:cache

# Cache de views
php artisan view:cache

# Otimização do autoloader
composer dump-autoload --optimize
```

#### Frontend Performance

```bash
# Análise de bundle
npm run build -- --analyze

# Verificação de performance
npm run lighthouse
```

## Testing

### PHP Tests

#### Feature Test

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Paciente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PacienteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_patients_index(): void
    {
        $user = User::factory()->create();
        $pacientes = Paciente::factory(3)->create();

        $response = $this->actingAs($user)
            ->get(route('pacientes.index'));

        $response->assertOk();
        $response->assertInertia(fn ($page) =>
            $page->component('Pacientes/Index')
                 ->has('pacientes.data', 3)
        );
    }

    public function test_user_can_create_patient(): void
    {
        $user = User::factory()->create();
        $patientData = [
            'name' => 'João',
            'surname' => 'Silva',
            'email' => 'joao@example.com',
            'cpf' => '12345678901',
            'birthdate' => '1990-01-01',
            'mother_name' => 'Maria Silva',
        ];

        $response = $this->actingAs($user)
            ->post(route('pacientes.store'), $patientData);

        $response->assertRedirect(route('pacientes.index'));
        $this->assertDatabaseHas('pacientes', [
            'email' => 'joao@example.com'
        ]);
    }
}
```

#### Unit Test

```php
<?php

namespace Tests\Unit;

use App\Models\Paciente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PacienteModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_name_accessor(): void
    {
        $paciente = Paciente::factory()->make([
            'name' => 'João',
            'surname' => 'Silva'
        ]);

        $this->assertEquals('João Silva', $paciente->full_name);
    }

    public function test_search_scope(): void
    {
        $paciente1 = Paciente::factory()->create(['name' => 'João']);
        $paciente2 = Paciente::factory()->create(['name' => 'Maria']);

        $results = Paciente::search('João')->get();

        $this->assertCount(1, $results);
        $this->assertEquals($paciente1->id, $results->first()->id);
    }
}
```

### Frontend Tests

#### Component Test

```typescript
// tests/js/components/PrimaryButton.test.ts
import { mount } from '@vue/test-utils';
import { describe, it, expect } from 'vitest';
import PrimaryButton from '@/Components/PrimaryButton.vue';

describe('PrimaryButton', () => {
    it('renders correctly', () => {
        const wrapper = mount(PrimaryButton, {
            slots: {
                default: 'Click me',
            },
        });

        expect(wrapper.text()).toBe('Click me');
        expect(wrapper.classes()).toContain('bg-gray-800');
    });

    it('handles disabled state', () => {
        const wrapper = mount(PrimaryButton, {
            props: { disabled: true },
        });

        expect(wrapper.attributes('disabled')).toBeDefined();
        expect(wrapper.classes()).toContain('opacity-25');
    });

    it('emits click event', async () => {
        const wrapper = mount(PrimaryButton);

        await wrapper.trigger('click');

        expect(wrapper.emitted()).toHaveProperty('click');
    });
});
```

## Database

### Migrações

#### Criar Migração

```bash
# Nova tabela
php artisan make:migration create_pacientes_table

# Alterar tabela
php artisan make:migration add_column_to_pacientes_table --table=pacientes
```

#### Estrutura de Migração

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->date('birthdate');
            $table->string('cpf')->unique();
            $table->string('role')->nullable();
            $table->string('education')->nullable();
            $table->string('mother_name');
            $table->string('email')->unique();
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['name', 'surname']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
```

### Factories

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PacienteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'birthdate' => fake()->dateTimeBetween('-80 years', '-18 years'),
            'cpf' => fake()->cpf(),
            'role' => fake()->optional()->jobTitle(),
            'education' => fake()->optional()->randomElement([
                'Ensino Fundamental',
                'Ensino Médio',
                'Superior Incompleto',
                'Superior Completo',
                'Pós-graduação',
            ]),
            'mother_name' => fake()->name('female'),
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}
```

### Seeders

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Paciente;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuário admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // Usuários de teste
        User::factory(10)->create();

        // Pacientes de teste
        Paciente::factory(50)->create();
    }
}
```

## Comandos Úteis

### Desenvolvimento Daily

```bash
# Limpar caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recriar banco
php artisan migrate:fresh --seed

# Gerar UML
php artisan generate:uml
npm run docs:uml

# Verificações
php artisan test --coverage
npm run type-check
npm run lint
```

### Produção

```bash
# Otimizações
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload --optimize

# Build
npm run build

# Migrações
php artisan migrate --force
```

## Integração Contínua

### GitHub Actions

#### .github/workflows/tests.yml

```yaml
name: Tests

on:
    push:
        branches: [main, develop]
    pull_request:
        branches: [main]

jobs:
    test:
        runs-on: ubuntu-latest

        services:
            postgres:
                image: postgres:15
                env:
                    POSTGRES_PASSWORD: postgres
                options: >-
                    --health-cmd pg_isready
                    --health-interval 10s
                    --health-timeout 5s
                    --health-retries 5

        steps:
            - uses: actions/checkout@v4

            - name: Setup PHP
              uses: shivammathur/setup-php@v2
              with:
                  php-version: '8.2'

            - name: Setup Node.js
              uses: actions/setup-node@v4
              with:
                  node-version: '18'

            - name: Install dependencies
              run: |
                  composer install --no-progress --prefer-dist --optimize-autoloader
                  npm ci

            - name: Prepare Laravel Application
              run: |
                  cp .env.example .env
                  php artisan key:generate

            - name: Run tests
              run: |
                  php artisan test
                  npm run test:js
```

## Git Workflow

### Conventional Commits

```bash
# Tipos de commit
feat:     # Nova funcionalidade
fix:      # Correção de bug
docs:     # Documentação
style:    # Formatação
refactor: # Refatoração
test:     # Testes
chore:    # Manutenção

# Exemplos
git commit -m "feat: adiciona busca de pacientes"
git commit -m "fix: corrige validação de CPF"
git commit -m "docs: atualiza README"
```

### Branch Strategy

```bash
# Branches principais
main      # Produção
develop   # Desenvolvimento

# Branches de feature
feature/nome-da-feature
bugfix/nome-do-bug
hotfix/nome-do-hotfix

# Workflow
git checkout develop
git checkout -b feature/nova-funcionalidade
# ... desenvolver
git push origin feature/nova-funcionalidade
# ... criar PR para develop
```

---

**Próximos passos**: [Testing Guide](../testing/README.md) | [Deployment Guide](../deployment/README.md) | [Architecture Overview](../architecture/README.md)
