# Documentação Completa - Laravel com Inertia.js

Documentação técnica completa do projeto Laravel com Inertia.js, Vue.js 3 e TypeScript.

## Índice da Documentação

### 1. Configuração e Setup

-   [Configuração Inicial](./setup/README.md) - Guia completo de instalação
-   [Configuração de Ambiente](./setup/ENVIRONMENT.md) - Variáveis de ambiente
-   [Configuração de Banco de Dados](./setup/DATABASE.md) - PostgreSQL e SQLite

### 2. Desenvolvimento

-   [Guia de Desenvolvimento](./development/DEVELOPMENT_GUIDE.md) - Padrões e práticas completas
-   [DevContainer](./development/DEVCONTAINER.md) - Ambiente containerizado
-   [Comandos NPM](./development/NPM_COMMANDS.md) - Scripts disponíveis
-   [Diagramas UML](./development/UML_DIAGRAMS.md) - Geração automática
-   [Scripts UML](./development/UML_SCRIPTS.md) - Scripts de automação
-   [Segurança no Desenvolvimento](./development/SECURITY_REPORT.md) - Práticas seguras

### 3. Arquitetura

-   [Arquitetura do Sistema](./architecture/README.md) - Visão geral completa
-   [Backend (Laravel)](./architecture/BACKEND.md) - Estrutura detalhada do backend
-   [Frontend (Vue.js)](./architecture/FRONTEND.md) - Estrutura detalhada do frontend
-   [Banco de Dados](./architecture/DATABASE.md) - Schema e relacionamentos

### 4. Deploy e Produção

-   [Deploy com Docker](./deployment/DOCKER.md) - Containerização
-   [Deploy em Produção](./deployment/README.md) - Ambiente de produção
-   [Configuração de Servidores](./deployment/SERVERS.md) - Nginx, PHP-FPM

### 5. Testes

-   [Estratégia de Testes](./testing/README.md) - Visão geral dos testes
-   [Melhores Práticas](./testing/BEST_PRACTICES.md) - Padrões de teste
-   [Solução de Problemas](./testing/TROUBLESHOOTING.md) - Debug e resolução

### 6. Migrações

-   [Migrações PostgreSQL](./migrations/POSTGRESQL_MIGRATION.md) - Migração de dados
-   [Guia de Migrações](./migrations/README.md) - Controle de versão do banco

### 7. Segurança

-   [Análise de Segurança](./SECURITY_ANALYSIS.md) - Análise OWASP Top 10
-   [Relatório de Segurança](./development/SECURITY_REPORT.md) - Implementações
-   [Configurações Seguras](./security/SECURE_CONFIG.md) - Configurações

### 8. Utilitários

-   [Scripts de Automação](../scripts/README.md) - Scripts auxiliares
-   [Validação Docker](./utilities/DOCKER_VALIDATION.md) - Verificações
-   [Monitoramento](./utilities/MONITORING.md) - Logs e métricas

## Quick Start

### Para Desenvolvedores

1. [DevContainer Setup](./development/DEVCONTAINER.md)
2. [Comandos Essenciais](./development/DEVELOPMENT_GUIDE.md#comandos-essenciais)
3. [Estrutura do Projeto](./architecture/README.md)

### Para Deploy

1. [Docker Production](./deployment/DOCKER.md)
2. [Configuração de Ambiente](./deployment/README.md)
3. [Monitoramento](./utilities/MONITORING.md)

### Para Testes

1. [Executar Testes](./testing/README.md)
2. [Cobertura](./testing/BEST_PRACTICES.md)
3. [Debug](./testing/TROUBLESHOOTING.md)

## Funcionalidades Implementadas

### Sistema de Autenticação

-   Login e registro de usuários
-   Verificação de email
-   Recuperação de senha
-   Middleware de autenticação e autorização

### Gerenciamento de Pacientes

-   CRUD completo de pacientes
-   Busca e filtros avançados
-   Validação robusta de dados
-   Interface responsiva

### Arquitetura Moderna

-   Laravel 12 + Inertia.js + Vue.js 3
-   TypeScript para tipagem estática
-   Tailwind CSS para estilização
-   PostgreSQL como banco principal

### Qualidade e Segurança

-   55 testes implementados com 190 assertions
-   Análise estática com PHPStan e Psalm
-   Segurança OWASP Top 10 2021 compliant
-   CI/CD automatizado com GitHub Actions

## Tecnologias Utilizadas

### Backend

-   **Laravel 12**: Framework PHP moderno
-   **PHP 8.2+**: Linguagem de programação
-   **PostgreSQL**: Banco de dados principal
-   **Inertia.js**: Bridge para SPAs modernas

### Frontend

-   **Vue.js 3**: Framework JavaScript progressivo
-   **TypeScript**: Superset tipado do JavaScript
-   **Tailwind CSS**: Framework CSS utilitário
-   **Vite**: Build tool e dev server

### DevOps

-   **Docker**: Containerização
-   **GitHub Actions**: CI/CD
-   **DevContainer**: Ambiente de desenvolvimento
-   **PlantUML**: Geração de diagramas

## Recursos Adicionais

### Links Externos

-   [Laravel Documentation](https://laravel.com/docs)
-   [Vue.js Guide](https://vuejs.org/guide/)
-   [Inertia.js Documentation](https://inertiajs.com/)
-   [TypeScript Handbook](https://www.typescriptlang.org/docs/)
-   [Tailwind CSS](https://tailwindcss.com/docs)

### Ferramentas

-   [PHPStan](https://phpstan.org/user-guide/getting-started)
-   [Psalm](https://psalm.dev/docs)
-   [ESLint](https://eslint.org/docs/user-guide/)
-   [Vite](https://vitejs.dev/guide/)

### Comunidade

-   [GitHub Issues](https://github.com/HeroDestiny/laravel_com_inertia/issues)
-   [GitHub Discussions](https://github.com/HeroDestiny/laravel_com_inertia/discussions)
-   [Laravel Community](https://laravel.com/community)
-   [Vue.js Community](https://vuejs.org/community/)

## Estatísticas do Projeto

### Métricas de Qualidade

-   **Testes**: 55 testes automatizados
-   **Assertions**: 190 verificações
-   **Cobertura**: Focada em funcionalidades críticas
-   **Análise Estática**: PHPStan Level 9

### Linhas de Código

-   **PHP**: Controllers, Models, Services
-   **TypeScript**: Components, Pages, Composables
-   **CSS**: Tailwind utilities e componentes customizados
-   **SQL**: Migrações e seeders

### Estrutura de Arquivos

```
laravel_com_inertia/
├── src/                      # Código principal (Laravel + Vue.js)
├── docs/                     # Documentação completa
├── docker/                   # Configurações Docker
├── scripts/                  # Scripts auxiliares
└── README.md                 # Documentação principal
```

## Como Contribuir

### Para Desenvolvedores

1. Leia o [Guia de Desenvolvimento](./development/DEVELOPMENT_GUIDE.md)
2. Configure o [ambiente de desenvolvimento](./setup/README.md)
3. Siga os [padrões de código](./development/DEVELOPMENT_GUIDE.md#padrões-de-código)
4. Execute os [testes](./testing/README.md) antes de submeter

### Para Documentação

1. Mantenha a documentação atualizada
2. Use linguagem clara e objetiva
3. Inclua exemplos práticos
4. Teste todas as instruções

### Para Issues e Bugs

1. Use os templates disponíveis
2. Forneça informações detalhadas
3. Inclua passos para reprodução
4. Adicione logs relevantes

---

**Última atualização**: 31 de Julho de 2025  
**Versão**: 3.0.0  
**Autor**: [HeroDestiny](https://github.com/HeroDestiny)  
**Licença**: MIT

-   **[Scripts Overview](../scripts/README.md)** - Todos os scripts
-   **[Quick Check](../scripts/quick-check-local.sh)** - Verificações rápidas
-   **[Setup After Rebuild](../scripts/setup-after-rebuild.sh)** - Pós-rebuild

### Tasks do VS Code

Execute via `Ctrl+Shift+P` → "Tasks: Run Task":

-   **Laravel: Serve** - Servidor backend
-   **Vite: Dev Server** - Desenvolvimento frontend
-   **Run Tests** - Testes automatizados
-   **Generate UML** - Diagramas UML

## Stack Tecnológico

### Backend

-   **Laravel 12** + **PHP 8.2+** + **PostgreSQL**
-   **PHPStan/Psalm** - Análise estática
-   **PlantUML** - Geração de diagramas

### Frontend

-   **Inertia.js** + **Vue.js 3** + **TypeScript**
-   **Tailwind CSS** + **shadcn/ui**
-   **Vite** + **ESLint/Prettier**

### DevOps

-   **DevContainer** - PostgreSQL integrado
-   **Docker** - Produção
-   **GitHub Actions** - CI/CD

## Novidades v3.0

### Sistema UML Automático

-   Geração automática de diagramas
-   Múltiplos métodos de encoding
-   Visualização online integrada

### Segurança Aprimorada

-   Scripts PostgreSQL seguros
-   OWASP Top 10 2021 compliance
-   Logs seguros sem exposição de dados

### DevContainer

-   PostgreSQL integrado
-   Configuração automática pós-rebuild
-   Zero configuração necessária

## Comandos Rápidos

```bash
# Desenvolvimento
php artisan serve --host=0.0.0.0
npm run dev

# Qualidade
php artisan test
npm run lint
./scripts/quick-check-local.sh

# UML
php artisan generate:uml
npm run docs:uml

# Produção
npm run build
php artisan migrate --force
```

## Links Externos

### Documentação Oficial

-   [Laravel](https://laravel.com/docs)
-   [Inertia.js](https://inertiajs.com)
-   [Vue.js](https://vuejs.org)
-   [Tailwind CSS](https://tailwindcss.com)

### Ferramentas

-   [PlantUML Online](http://www.plantuml.com/plantuml/uml/)
-   [OWASP Top 10](https://owasp.org/Top10/)
-   [PostgreSQL Docs](https://www.postgresql.org/docs/)

## Contribuindo

1. **Issues:** Reporte problemas via GitHub Issues
2. **Pull Requests:** Contribuições são bem-vindas
3. **Discussões:** Use GitHub Discussions para ideias
4. **Segurança:** Reporte vulnerabilidades via email

---

**Atualizado:** Julho 2025 | **Versão:** 3.0 | **Segurança:** OWASP Compliant
