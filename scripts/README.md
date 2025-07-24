# Scripts do Projeto Laravel + Inertia

Scripts utilitários para gerenciamento do ambiente de desenvolvimento.

## Scripts Disponíveis

### Scripts Ativos

#### `setup-after-rebuild.sh`

**Uso:** `./scripts/setup-after-rebuild.sh`

-   Setup completo após rebuild do DevContainer
-   Configura PostgreSQL, Laravel, migrações
-   Verifica sistema UML e frontend
-   **Quando usar:** Após rebuild do devcontainer ou problemas de ambiente

#### `docker-manager.sh`

**Uso:** `./scripts/docker-manager.sh [comando]`

-   `status` - Status dos containers
-   `postgres` - Inicia PostgreSQL externo (backup)
-   `clean` - Limpa containers órfãos
-   `logs` - Mostra logs do PostgreSQL
-   **Quando usar:** Debugging de containers ou PostgreSQL externo

#### `quick-check-local.sh`

**Uso:** `./scripts/quick-check-local.sh`

-   **Versão otimizada** para desenvolvimento local
-   ✅ **Verifica dependências** antes de reinstalar (economiza tempo)
-   ✅ Pint, PHPStan, ESLint, testes, build
-   **Quando usar:** Antes de commits ou periodicamente

#### `quick-check-fast.sh` ⚡

**Uso:** `./scripts/quick-check-fast.sh`

-   **Versão ultra-rápida** para desenvolvimento ativo
-   ✅ Apenas: Pint + PHPStan + Testes unitários
-   ⏱️ **~3 segundos** vs ~30 segundos da versão completa
-   **Quando usar:** Durante desenvolvimento ativo, várias vezes por dia

-   Executa todos os checks de qualidade
-   Simula workflow do GitHub Actions
-   **Quando usar:** Antes de commits ou push

#### `test-postgres-connection.php`

**Uso:** `php test-postgres-connection.php`

-   Diagnóstico completo de conectividade PostgreSQL com validações de segurança
-   Usa variáveis de ambiente para credenciais (não hardcoded)
-   Implementa prepared statements para prevenir SQL injection
-   Logs erros sem expor informações sensíveis
-   Sanitiza output para prevenir XSS
-   **Quando usar:** Problemas de conexão com banco ou verificação pós-rebuild

### Scripts UML (em `src/scripts/`)

#### `check_uml_system.py`

**Uso:** `python3 scripts/check_uml_system.py` ou `npm run docs:uml:check`

-   Verifica sistema de geração UML
-   **Quando usar:** Problemas com diagramas UML

## Fluxo de Desenvolvimento Recomendado

### Primeiro Setup (DevContainer)

```bash
# Automaticamente executado pelo devcontainer.json
# Se falhar, execute manualmente:
./scripts/setup-after-rebuild.sh
```

### Desenvolvimento Diário

```bash
# Use as tasks do VS Code (recomendado):
# - Ctrl+Shift+P > "Tasks: Run Task"
# - Laravel: Serve
# - Vite: Dev Server

# Ou via terminal:
cd src
php artisan serve --host=0.0.0.0 --port=8000
npm run dev
```

### Antes de Commit

```bash
./scripts/quick-check-local.sh
```

### Debugging

```bash
# Problemas com PostgreSQL:
php scripts/test-postgres-connection.php
./scripts/docker-manager.sh status

# Problemas com UML:
npm run docs:uml:check

# Limpeza geral:
./scripts/docker-manager.sh clean
```

## Tasks do VS Code (Preferidas)

As seguintes tasks estão configuradas e são a forma **recomendada** de trabalhar:

-   **Laravel: Serve** - Inicia servidor Laravel
-   **Vite: Dev Server** - Inicia desenvolvimento frontend
-   **Laravel: Fresh Migrate** - Reset do banco com seeds
-   **Run Tests** - Executa todos os testes
-   **Quick Checks** - Verificações de qualidade
-   **Generate UML Docs** - Gera diagramas UML
-   **Lint & Format** - Formatação de código

## Scripts Removidos (2025)

Os seguintes scripts foram removidos por serem obsoletos:

-   `fix-devcontainer.sh` - DevContainer resolve automaticamente
-   `setup-dev.sh` - Tasks do VS Code substituem
-   `setup-laravel.sh` - Muito básico
-   `start-postgres.sh` - DevContainer gerencia automaticamente
-   `stop-postgres.sh` - Desnecessário
-   `postgres-devcontainer.sh` - Redundante
-   Arquivos `.bat` vazios

## Dicas

1. **Use as tasks do VS Code** - São mais integradas e confiáveis
2. **DevContainer é self-contained** - A maioria dos scripts de setup são desnecessários
3. **Scripts são para casos especiais** - Debugging, limpeza, checks de CI
4. **UML integrado** - Use `npm run docs:uml` para gerar diagramas

## Links Úteis

-   **Aplicação:** http://localhost:8000
-   **Vite Dev:** http://localhost:5173
-   **UML Online:** http://www.plantuml.com/plantuml/uml/
