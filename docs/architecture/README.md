# Arquitetura do Sistema

Visão geral da arquitetura do projeto Laravel com Inertia.js.

## Visão Geral

O sistema segue uma arquitetura moderna de aplicação web full-stack, combinando:

-   **Backend**: Laravel como API e servidor de aplicação
-   **Frontend**: Vue.js 3 com TypeScript para interface do usuário
-   **Bridge**: Inertia.js conectando backend e frontend sem API REST tradicional
-   **Database**: PostgreSQL como banco principal

## Diagrama de Arquitetura

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Backend       │    │   Database      │
│   (Vue.js 3)    │◄──►│   (Laravel 12)  │◄──►│   (PostgreSQL)  │
│                 │    │                 │    │                 │
│ • TypeScript    │    │ • PHP 8.2+      │    │ • Primary DB    │
│ • Tailwind CSS  │    │ • Eloquent ORM  │    │ • SQLite (dev)  │
│ • Vite          │    │ • Inertia.js    │    │ • Migrations    │
│ • Components    │    │ • Middleware    │    │ • Seeders       │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

## Camadas da Aplicação

### 1. Apresentação (Frontend)

-   **Vue.js 3**: Framework reativo para interface
-   **TypeScript**: Tipagem estática
-   **Tailwind CSS**: Framework CSS utilitário
-   **Inertia.js**: Client-side routing e state management

### 2. Aplicação (Backend)

-   **Laravel**: Framework PHP moderno
-   **Controllers**: Lógica de controle
-   **Middleware**: Interceptadores de requisição
-   **Service Providers**: Configuração de serviços

### 3. Domínio (Business Logic)

-   **Models**: Entidades de negócio
-   **Repositories**: Abstração de dados (implícito via Eloquent)
-   **Services**: Lógica de negócio complexa
-   **Events**: Sistema de eventos

### 4. Infraestrutura (Data Layer)

-   **Eloquent ORM**: Mapeamento objeto-relacional
-   **Migrations**: Controle de versão do banco
-   **Seeders**: População de dados
-   **Cache**: Sistema de cache (Redis/Memcached)

## Fluxo de Dados

### Request Flow

```
1. Browser Request
   ↓
2. Laravel Router
   ↓
3. Middleware Stack
   ↓
4. Controller Action
   ↓
5. Business Logic (Services/Models)
   ↓
6. Database (Eloquent)
   ↓
7. Response (Inertia.js)
   ↓
8. Vue.js Component Render
```

### Data Flow

```
User Interaction → Vue Component → Inertia Request → Laravel Controller
                                                            ↓
Database ← Eloquent Model ← Business Logic ← Validation ← Form Request
                                                            ↓
Vue Component ← Inertia Response ← Controller Response ← Business Logic
```

## Componentes Principais

### Backend Components

#### Controllers

```php
app/Http/Controllers/
├── Controller.php              # Base controller
├── PacienteController.php      # Patient management
├── Auth/
│   ├── AuthenticatedSessionController.php
│   ├── ConfirmablePasswordController.php
│   ├── EmailVerificationNotificationController.php
│   ├── EmailVerificationPromptController.php
│   ├── NewPasswordController.php
│   ├── PasswordController.php
│   ├── PasswordResetLinkController.php
│   ├── RegisteredUserController.php
│   └── VerifyEmailController.php
└── Settings/
    └── ProfileController.php
```

#### Models

```php
app/Models/
├── User.php                    # User entity
└── Paciente.php               # Patient entity
```

#### Routes

```php
routes/
├── web.php                    # Main web routes
├── auth.php                   # Authentication routes
├── console.php                # Artisan commands
└── settings.php               # Settings routes
```

### Frontend Components

#### Page Components

```typescript
resources/js/Pages/
├── Auth/                      # Authentication pages
├── Dashboard.vue              # Main dashboard
├── Profile/                   # User profile
├── Settings/                  # Application settings
└── Welcome.vue               # Landing page
```

#### Layout Components

```typescript
resources/js/Layouts/
├── AuthenticatedLayout.vue    # Authenticated user layout
└── GuestLayout.vue           # Guest user layout
```

#### Shared Components

```typescript
resources/js/Components/
├── ApplicationLogo.vue
├── DangerButton.vue
├── Dropdown.vue
├── InputError.vue
├── InputLabel.vue
├── Modal.vue
├── NavLink.vue
├── PrimaryButton.vue
├── ResponsiveNavLink.vue
├── SecondaryButton.vue
├── TextInput.vue
└── ...
```

## Padrões Arquiteturais

### MVC (Model-View-Controller)

-   **Model**: Eloquent models para entidades de dados
-   **View**: Vue.js components para interface
-   **Controller**: Laravel controllers para lógica de controle

### Repository Pattern (Implícito)

-   Eloquent ORM atua como repository layer
-   Models encapsulam queries complexas
-   Scopes para queries reutilizáveis

### Service Layer

```php
app/Services/
├── AuthService.php
├── PacienteService.php
└── NotificationService.php
```

### Event-Driven Architecture

```php
app/Events/
├── UserRegistered.php
└── PacienteCreated.php

app/Listeners/
├── SendWelcomeEmail.php
└── LogPacienteActivity.php
```

## Configuração e Ambiente

### Configuração Laravel

```php
config/
├── app.php                    # Application config
├── auth.php                   # Authentication config
├── cache.php                  # Cache configuration
├── database.php               # Database connections
├── filesystems.php            # File storage
├── logging.php                # Logging configuration
├── mail.php                   # Email configuration
├── queue.php                  # Queue configuration
├── services.php               # Third-party services
└── session.php                # Session configuration
```

### Environment Variables

```bash
# Application
APP_NAME="Laravel com Inertia"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=laravel_inertia
DB_USERNAME=postgres
DB_PASSWORD=password

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Mail
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
```

## Segurança

### Middleware Stack

```php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \App\Http\Middleware\HandleInertiaRequests::class,
        \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
    ],
];
```

### CSRF Protection

-   Automatic CSRF token generation
-   Inertia.js handles token refresh
-   Form validation with CSRF checks

### Authentication

-   Laravel Breeze for authentication scaffolding
-   Email verification
-   Password reset functionality
-   Remember me functionality

## Performance

### Optimization Strategies

-   **Laravel Optimization**: Route, config, and view caching
-   **Frontend Optimization**: Code splitting, lazy loading
-   **Database Optimization**: Query optimization, indexing
-   **Asset Optimization**: Vite bundling, compression

### Caching Strategy

```php
// Configuration caching
php artisan config:cache

// Route caching
php artisan route:cache

// View caching
php artisan view:cache

// Application caching
Cache::remember('key', $ttl, $callback);
```

### Database Performance

```php
// Query optimization
User::with('pacientes')->get();

// Eager loading
Paciente::with('user')->paginate(15);

// Database indexing
Schema::table('pacientes', function (Blueprint $table) {
    $table->index(['created_at', 'user_id']);
});
```

## Monitoramento e Observabilidade

### Logging

```php
// Application logs
Log::info('User logged in', ['user_id' => $user->id]);

// Error logging
Log::error('Database error', ['exception' => $e]);

// Custom channels
Log::channel('audit')->info('Patient created', $data);
```

### Health Checks

```bash
# Application health
php artisan health:check

# Database connectivity
php artisan db:check

# Cache status
php artisan cache:status
```

### Metrics

-   Response time monitoring
-   Database query performance
-   Error rate tracking
-   User activity metrics

## Escalabilidade

### Horizontal Scaling

-   Load balancer configuration
-   Session storage (Redis/Database)
-   File storage (S3/CDN)
-   Database read replicas

### Vertical Scaling

-   PHP-FPM optimization
-   Database connection pooling
-   Memory optimization
-   CPU utilization

### Microservices Migration

-   Domain separation
-   API Gateway
-   Service communication
-   Data consistency

---

**Próximos passos**: [Backend Architecture](./BACKEND.md) | [Frontend Architecture](./FRONTEND.md) | [Database Schema](./DATABASE.md)
