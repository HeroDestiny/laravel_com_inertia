# 💻 Development Guide

Guia completo para desenvolvimento no projeto Laravel + Inertia.js.

## 🛠️ Stack Tecnológico

### Backend

-   **Laravel 11** - Framework PHP
-   **PHP 8.2+** - Linguagem principal
-   **PostgreSQL** - Banco de dados principal

### Frontend

-   **Inertia.js** - SPA sem necessidade de API REST
-   **Vue.js 3** - Framework JavaScript reativo
-   **TypeScript** - JavaScript tipado
-   **Tailwind CSS** - Framework CSS utility-first

### Ferramentas

-   **Vite** - Build tool moderno
-   **PHPStan/Psalm** - Análise estática PHP
-   **ESLint/Prettier** - Qualidade frontend

### Documentação e Scripts

-   **[UML Scripts](./UML_SCRIPTS.md)** - Geração automática de diagramas UML
-   **[NPM Commands](./NPM_COMMANDS.md)** - Todos os comandos NPM configurados

## 🚀 Fluxo de Desenvolvimento

### 1. Setup Inicial

**DevContainer (Recomendado):**

1. Abra no VS Code
2. "Reopen in Container"
3. Execute tasks: `Laravel: Serve` + `Vite: Dev Server`

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

# Inclui: Pint, PHPStan, ESLint, testes e build
```

## 📁 Estrutura do Projeto

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

## ⚡ Comandos Essenciais

### Desenvolvimento

```bash
php artisan serve --host=0.0.0.0    # Servidor Laravel
npm run dev                          # Vite dev server
```

### Qualidade de Código

```bash
php artisan test                     # Executar testes
npm run lint                         # Lint e formatação
./scripts/quick-check-local.sh       # Verificações completas
```

### Banco de Dados

```bash
php artisan migrate                  # Executar migrações
php artisan migrate:fresh --seed     # Reset completo com seeds
php artisan db:seed                  # Apenas seeds
```

### UML e Documentação

```bash
php artisan generate:uml             # Gerar diagramas UML
npm run docs:uml                     # Gerar PNG + visualizar
```

## 🔧 Debugging e Troubleshooting

### Logs

```bash
# Logs da aplicação
tail -f storage/logs/laravel.log

# Logs do servidor
php artisan serve --verbose
```

### Debug com Xdebug (DevContainer)

1. Configure breakpoints no VS Code
2. Inicie debug com F5
3. Acesse a aplicação no navegador

### Problemas Comuns

1. **Erro 500**: Verificar logs em `storage/logs/laravel.log`
2. **Assets não carregam**: Verificar se `npm run dev` está rodando
3. **Banco não conecta**: Verificar configuração no `.env`

## 🧪 Testes

### Executar Testes

```bash
# Todos os testes
php artisan test

# Testes específicos
php artisan test --filter=UserTest

# Com cobertura
php artisan test --coverage
```

### Estrutura de Testes

-   **Unit Tests**: Testes unitários em `tests/Unit/`
-   **Feature Tests**: Testes de integração em `tests/Feature/`
-   **Total**: 55 testes com 190 assertions

## 📊 Qualidade de Código

### Análise Estática

```bash
# PHPStan
./vendor/bin/phpstan analyse

# Psalm
./vendor/bin/psalm

# ESLint
npm run lint
```

### Formatação

```bash
# PHP (Pint)
./vendor/bin/pint

# JavaScript/TypeScript
npm run format
```

## 🔗 Links Úteis

-   **[DevContainer Guide](./DEVCONTAINER.md)** - Ambiente containerizado
-   **[UML Diagrams](./UML_DIAGRAMS.md)** - Documentação visual
-   **[UML Scripts](./UML_SCRIPTS.md)** - Scripts detalhados de geração UML
-   **[NPM Commands](./NPM_COMMANDS.md)** - Referência completa de comandos NPM
-   **[Testing Guide](../testing/README.md)** - Estratégia de testes
-   **[Deployment](../deployment/README.md)** - Deploy para produção

## 📋 Comandos Rápidos

### Comandos de Desenvolvimento Diário

```bash
npm run dev                  # Servidor de desenvolvimento
npm run docs:uml            # Gerar diagramas UML
npm run lint                # Lint e formatação de código
npm run test                # Executar testes
```

### Scripts de Sistema

```bash
npm run check:fast          # Verificações rápidas
npm run docker:manager      # Gerenciamento do Docker
npm run test:postgres       # Testar conexão com banco de dados
```

Para lista completa, consulte **[NPM Commands](./NPM_COMMANDS.md)**.

---

**🎯 Foco na produtividade com ferramentas modernas!**
