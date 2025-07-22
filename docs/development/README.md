# Development Guide

Guia completo para desenvolvimento no projeto Laravel + Inertia.js.

## Stack Tecnológico

### Backend

-   **Laravel 11** - Framework PHP
-   **PHP 8.2+** - Linguagem principal
-   **PostgreSQL** - Banco de dados

### Frontend

-   **Inertia.js** - SPA sem API
-   **Vue.js 3** - Framework reativo
-   **TypeScript** - JavaScript tipado
-   **Tailwind CSS** - Framework CSS utility-first

### Ferramentas

-   **Vite** - Build tool moderno
-   **PHPStan/Psalm** - Análise estática PHP
-   **ESLint/Prettier** - Qualidade frontend

## Documentos Disponíveis

-   **[DevContainer Guide](./DEVCONTAINER.md)** - Ambiente de desenvolvimento containerizado
-   **[UML Diagrams](./UML_DIAGRAMS.md)** - Geração automática de diagramas

## Fluxo de Desenvolvimento

### 1. Setup Inicial

**DevContainer (Recomendado):**

```bash
# 1. Abra no VS Code
# 2. "Reopen in Container"
# 3. Execute tasks: Laravel: Serve + Vite: Dev Server
```

**Local:**

```bash
./scripts/setup-after-rebuild.sh
```

### 2. Desenvolvimento Diário

Use as **tasks do VS Code** (`Ctrl+Shift+P` → "Tasks: Run Task"):

-   **Laravel: Serve** - Servidor backend
-   **Vite: Dev Server** - Desenvolvimento frontend
-   **Laravel: Fresh Migrate** - Reset database
-   **Run Tests** - Testes automatizados

### 3. Qualidade de Código

```bash
# Antes de cada commit
./scripts/quick-check-local.sh

# Inclui: Pint, PHPStan, ESLint, testes, build
```

## Estrutura do Projeto

### Backend (src/app/)

```
├── Console/Commands/       # Comandos Artisan
├── Http/Controllers/       # Controllers
├── Models/                # Models Eloquent
└── Providers/             # Service Providers
```

### Frontend (src/resources/)

```
├── js/
│   ├── Components/        # Componentes Vue
│   ├── Pages/            # Páginas Inertia.js
│   └── types/            # Tipos TypeScript
└── css/app.css           # Estilos Tailwind
```

## Debugging e Troubleshooting

### Xdebug (DevContainer)

-   Breakpoints no VS Code
-   Debug com F5
-   Logs: `tail -f src/storage/logs/laravel.log`

### Scripts Úteis

```bash
# Verificar PostgreSQL
php scripts/test-postgres-connection.php

# Reset ambiente
./scripts/setup-after-rebuild.sh

# Limpar caches
php artisan optimize:clear
```

## Próximos Passos

-   **[UML Diagrams](./UML_DIAGRAMS.md)** - Documentação visual
-   **[Deployment](../deployment/README.md)** - Deploy para produção
-   **[Scripts](../../scripts/README.md)** - Utilitários de desenvolvimento
