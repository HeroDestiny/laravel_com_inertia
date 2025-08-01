# Arquitetura do Backend (Laravel)

Documentação detalhada da arquitetura backend do projeto Laravel.

## Visão Geral

O backend utiliza Laravel 12 como framework principal, seguindo padrões arquiteturais modernos e melhores práticas de desenvolvimento PHP.

## Estrutura do Backend

```
src/app/
├── Console/                   # Artisan commands
├── Http/                      # HTTP layer
│   ├── Controllers/           # Request controllers
│   ├── Middleware/           # HTTP middleware
│   ├── Requests/             # Form request validation
│   └── Resources/            # API resources
├── Models/                   # Eloquent models
├── Providers/                # Service providers
├── Services/                 # Business logic
├── Events/                   # Domain events
├── Listeners/                # Event listeners
├── Jobs/                     # Background jobs
├── Mail/                     # Email classes
└── Policies/                 # Authorization policies
```

## Camada HTTP

### Controllers

#### Base Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
}
```

#### Resource Controllers

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
    public function index(): Response
    {
        return Inertia::render('Pacientes/Index', [
            'pacientes' => Paciente::paginate(15)
        ]);
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create(): Response
    {
        return Inertia::render('Pacientes/Create');
    }

    /**
     * Store a newly created patient.
     */
    public function store(PacienteRequest $request)
    {
        Paciente::create($request->validated());

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente criado com sucesso!');
    }

    /**
     * Display the specified patient.
     */
    public function show(Paciente $paciente): Response
    {
        return Inertia::render('Pacientes/Show', [
            'paciente' => $paciente
        ]);
    }

    /**
     * Show the form for editing the patient.
     */
    public function edit(Paciente $paciente): Response
    {
        return Inertia::render('Pacientes/Edit', [
            'paciente' => $paciente
        ]);
    }

    /**
     * Update the specified patient.
     */
    public function update(PacienteRequest $request, Paciente $paciente)
    {
        $paciente->update($request->validated());

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente atualizado com sucesso!');
    }

    /**
     * Remove the specified patient.
     */
    public function destroy(Paciente $paciente)
    {
        $paciente->delete();

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente removido com sucesso!');
    }
}
```

### Middleware

#### Authentication Middleware

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
```

#### Inertia Middleware

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ];
    }
}
```

### Form Requests

#### Validation Requests

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PacienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'date', 'before:today'],
            'cpf' => ['required', 'string', 'cpf', 'unique:pacientes,cpf'],
            'role' => ['nullable', 'string', 'max:255'],
            'education' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:pacientes,email'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'surname.required' => 'O sobrenome é obrigatório.',
            'birthdate.required' => 'A data de nascimento é obrigatória.',
            'birthdate.before' => 'A data de nascimento deve ser anterior a hoje.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.cpf' => 'O CPF deve ter um formato válido.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'mother_name.required' => 'O nome da mãe é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ter um formato válido.',
            'email.unique' => 'Este email já está cadastrado.',
        ];
    }
}
```

## Camada de Dados

### Models

#### User Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the patients for the user.
     */
    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }
}
```

#### Paciente Model

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

    /**
     * The attributes that are mass assignable.
     */
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

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'birthdate' => 'date',
    ];

    /**
     * Get the user that owns the patient.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the patient's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name} {$this->surname}";
    }

    /**
     * Scope a query to only include active patients.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope a query to search patients by name.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        });
    }
}
```

### Factories

#### User Factory

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
```

#### Paciente Factory

```php
<?php

namespace Database\Factories;

use App\Models\Paciente;
use Illuminate\Database\Eloquent\Factories\Factory;

class PacienteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Paciente::class;

    /**
     * Define the model's default state.
     */
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

## Camada de Serviços

### Service Classes

```php
<?php

namespace App\Services;

use App\Models\Paciente;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PacienteService
{
    /**
     * Get paginated patients with optional search.
     */
    public function getPaginated(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = Paciente::query();

        if ($search) {
            $query->search($search);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Create a new patient.
     */
    public function create(array $data): Paciente
    {
        return Paciente::create($data);
    }

    /**
     * Update an existing patient.
     */
    public function update(Paciente $paciente, array $data): bool
    {
        return $paciente->update($data);
    }

    /**
     * Delete a patient.
     */
    public function delete(Paciente $paciente): bool
    {
        return $paciente->delete();
    }

    /**
     * Get patient statistics.
     */
    public function getStatistics(): array
    {
        return [
            'total' => Paciente::count(),
            'active' => Paciente::active()->count(),
            'recent' => Paciente::where('created_at', '>=', now()->subWeek())->count(),
        ];
    }
}
```

## Configuração e Providers

### Service Providers

#### AppServiceProvider

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('viewTelescope', function ($user) {
            return in_array($user->email, [
                'admin@example.com',
            ]);
        });
    }
}
```

### Configuration Files

#### Database Configuration

```php
<?php

return [
    'default' => env('DB_CONNECTION', 'sqlite'),

    'connections' => [
        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],
    ],
];
```

## Roteamento

### Web Routes

```php
<?php

use App\Http\Controllers\PacienteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Patient management routes
    Route::resource('pacientes', PacienteController::class);
});

require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
```

### Authentication Routes

```php
<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
```

## Segurança

### CSRF Protection

-   Automatic CSRF token generation
-   Middleware validation
-   Inertia.js integration

### Authentication

-   Laravel Breeze implementation
-   Email verification
-   Password reset
-   Remember me functionality

### Authorization

```php
<?php

namespace App\Policies;

use App\Models\Paciente;
use App\Models\User;

class PacientePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Paciente $paciente): bool
    {
        return $user->id === $paciente->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Paciente $paciente): bool
    {
        return $user->id === $paciente->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Paciente $paciente): bool
    {
        return $user->id === $paciente->user_id;
    }
}
```

## Performance e Otimização

### Query Optimization

```php
// Eager loading
$pacientes = Paciente::with('user')->get();

// Lazy eager loading
$pacientes->load('user');

// Selective fields
$pacientes = Paciente::select(['id', 'name', 'email'])->get();

// Pagination
$pacientes = Paciente::paginate(15);
```

### Caching

```php
use Illuminate\Support\Facades\Cache;

// Cache patient statistics
$stats = Cache::remember('patient_stats', 3600, function () {
    return [
        'total' => Paciente::count(),
        'active' => Paciente::active()->count(),
    ];
});

// Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database Indexing

```php
Schema::table('pacientes', function (Blueprint $table) {
    $table->index('email');
    $table->index('cpf');
    $table->index(['name', 'surname']);
    $table->index('created_at');
});
```

## Testing

### Feature Tests

```php
<?php

namespace Tests\Feature;

use App\Models\Paciente;
use App\Models\User;
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
        $patientData = Paciente::factory()->make()->toArray();

        $response = $this->actingAs($user)
            ->post(route('pacientes.store'), $patientData);

        $response->assertRedirect(route('pacientes.index'));
        $this->assertDatabaseHas('pacientes', [
            'email' => $patientData['email']
        ]);
    }
}
```

### Unit Tests

```php
<?php

namespace Tests\Unit;

use App\Models\Paciente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PacienteModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_full_name_attribute(): void
    {
        $paciente = Paciente::factory()->make([
            'name' => 'João',
            'surname' => 'Silva'
        ]);

        $this->assertEquals('João Silva', $paciente->full_name);
    }

    public function test_patient_search_scope(): void
    {
        $paciente1 = Paciente::factory()->create(['name' => 'João']);
        $paciente2 = Paciente::factory()->create(['name' => 'Maria']);

        $results = Paciente::search('João')->get();

        $this->assertCount(1, $results);
        $this->assertEquals($paciente1->id, $results->first()->id);
    }
}
```

---

**Próximos passos**: [Frontend Architecture](./FRONTEND.md) | [Database Schema](./DATABASE.md) | [API Documentation](./API.md)
