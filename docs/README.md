# Documentação - Laravel com Inertia.js

Documentação técnica completa do projeto Laravel + Inertia.js + Vue.js.

## Início Rápido

### Para Novos Desenvolvedores

1. **[Setup Inicial](./setup/README.md)** - Primeira configuração
2. **[DevContainer](./development/DEVCONTAINER.md)** - Ambiente recomendado
3. **[Development Guide](./development/README.md)** - Fluxo de desenvolvimento

### Para Deploy

1. **[Docker Production](./deployment/DOCKER.md)** - Deploy com containers
2. **[Deployment Guide](./deployment/README.md)** - Guia completo de deploy

## Estrutura da Documentação

### Setup e Configuração

-   **[Setup Guide](./setup/README.md)** - Configuração inicial completa
-   **[Environment Setup](./setup/)** - Configurações de ambiente

### Desenvolvimento

-   **[Development Overview](./development/README.md)** - Visão geral do desenvolvimento
-   **[DevContainer Guide](./development/DEVCONTAINER.md)** - Ambiente containerizado
-   **[UML Diagrams](./development/UML_DIAGRAMS.md)** - Diagramas automáticos de domínio

### Deploy e Produção

-   **[Deployment Overview](./deployment/README.md)** - Visão geral de deploy
-   **[Docker Setup](./deployment/DOCKER.md)** - Configuração Docker para produção

### Migrações e Database

-   **[Migration Guide](./migrations/README.md)** - Guia de migrações de banco
-   **[PostgreSQL Migration](./migrations/POSTGRESQL_MIGRATION.md)** - Migração para PostgreSQL

### Segurança e Qualidade

-   **[Security Analysis](./SECURITY_ANALYSIS.md)** - Análise OWASP Top 10 e boas práticas

## Recursos Adicionais

### Scripts Utilitários

-   **[Scripts Overview](../scripts/README.md)** - Scripts de desenvolvimento e deploy
-   **[Quick Checks](../scripts/quick-check-local.sh)** - Verificações antes de commit

### CI/CD

-   **[GitHub Actions](../.github/workflows/)** - Workflows automatizados
-   **[Quality Gates](../.github/workflows/README.md)** - Controle de qualidade

## Tecnologias Principais

### Backend

-   **Laravel 11** - Framework PHP moderno
-   **PHP 8.2+** - Linguagem backend
-   **PostgreSQL** - Banco de dados principal

### Frontend

-   **Inertia.js** - SPA sem API
-   **Vue.js 3** - Framework JavaScript reativo
-   **TypeScript** - JavaScript tipado
-   **Tailwind CSS** - Framework CSS utility-first

### DevOps

-   **DevContainer** - Ambiente de desenvolvimento
-   **Docker** - Containerização
-   **GitHub Actions** - CI/CD automatizado

## Como Contribuir

1. Leia a documentação relevante
2. Configure o ambiente com [DevContainer](./development/DEVCONTAINER.md)
3. Siga o [Development Guide](./development/README.md)
4. Execute testes: `./scripts/quick-check-local.sh`
5. Submeta seu Pull Request

## Suporte

-   **Issues:** Reporte problemas no GitHub
-   **Documentação:** Sempre atualizada neste diretório
-   **Scripts:** Use `./scripts/` para automações

### [Migrations](./migrations/)

Documentação sobre migrações e mudanças de banco de dados:

-   [PostgreSQL Migration](./migrations/POSTGRESQL_MIGRATION.md) - Migração de SQLite para PostgreSQL

## Links Úteis

-   [README Principal](../README.md) - Documentação principal do projeto
-   [Laravel Documentation](https://laravel.com/docs)
-   [Inertia.js Documentation](https://inertiajs.com/)
-   [Vue.js Documentation](https://vuejs.org/)

## Contribuindo

Ao adicionar nova documentação:

1. Coloque os arquivos na pasta apropriada
2. Atualize este README.md se necessário
3. Use nomes de arquivo descritivos em MAIÚSCULAS
4. Use títulos claros para facilitar a navegação

## Convenções

-   **Nomes de arquivo**: `UPPER_CASE.md`
-   **Estrutura**: Use títulos claros
-   **Links**: Sempre relativos à estrutura do projeto
-   **Idioma**: Português brasileiro
