# Índice Completo da Documentação

Navegação rápida por toda a documentação do projeto Laravel + Inertia.js.

## 🚀 Início Rápido

### Para Desenvolvedores

1. **[Setup Inicial](./setup/README.md)** - Configure seu ambiente
2. **[DevContainer](./development/DEVCONTAINER.md)** - Ambiente recomendado 🐳
3. **[Development Guide](./development/README.md)** - Fluxo de desenvolvimento

### Para Deploy

1. **[Deployment Guide](./deployment/README.md)** - Visão geral de deploy
2. **[Docker Production](./deployment/DOCKER.md)** - Deploy com containers

## 📚 Documentação por Categoria

### �️ Setup e Configuração

-   **[Setup Guide](./setup/README.md)** - Configuração inicial completa
    -   DevContainer vs Instalação Local
    -   Pré-requisitos e dependências
    -   Configuração de banco de dados PostgreSQL

### 💻 Desenvolvimento

-   **[Development Guide](./development/README.md)** - Guia completo de desenvolvimento

    -   Stack tecnológico
    -   Fluxo de desenvolvimento
    -   Estrutura de arquivos
    -   Debugging e troubleshooting

-   **[DevContainer Guide](./development/DEVCONTAINER.md)** - Ambiente containerizado

    -   Configuração automática
    -   VS Code integration
    -   PostgreSQL integrado

-   **[UML Diagrams](./development/UML_DIAGRAMS.md)** - Documentação visual 📊
    -   Geração automática de diagramas
    -   Visualização online com PlantUML
    -   Estrutura de domínio atualizada
    -   Sistema robusto com múltiplos encoders

### 🔒 Segurança

-   **[Security Analysis](./SECURITY_ANALYSIS.md)** - Análise completa de segurança
    -   OWASP Top 10 2021 compliance
    -   Vulnerabilidades identificadas e corrigidas
    -   Melhorias implementadas
    -   Scripts seguros de PostgreSQL

### 🚢 Deploy e Produção

-   **[Deployment Guide](./deployment/README.md)** - Deploy completo

    -   Docker vs Deploy tradicional
    -   Configurações de produção
    -   Monitoramento e logs
    -   Backup e manutenção

-   **[Docker Setup](./deployment/DOCKER.md)** - Containerização
    -   Configuração Docker
    -   Docker Compose
    -   Otimizações de produção

### 🗄️ Database e Migrações

-   **[Migrations Guide](./migrations/README.md)** - Gerenciamento de banco

    -   Comandos úteis
    -   Estrutura do banco
    -   Backup e restore
    -   Troubleshooting

-   **[PostgreSQL Migration](./migrations/POSTGRESQL_MIGRATION.md)** - Migração específica
    -   SQLite para PostgreSQL
    -   Configuração detalhada

### 🔒 Segurança

-   **[Security Analysis](./SECURITY_ANALYSIS.md)** - Análise OWASP Top 10
    -   Vulnerabilidades comuns
    -   Implementações de segurança
    -   Recomendações

## Utilitários e Scripts

### Scripts de Desenvolvimento

-   **[Scripts Overview](../scripts/README.md)** - Todos os scripts utilitários
-   **[Quick Checks](../scripts/quick-check-local.sh)** - Verificações de qualidade
-   **[Setup After Rebuild](../scripts/setup-after-rebuild.sh)** - Configuração pós-rebuild
-   **[Docker Manager](../scripts/docker-manager.sh)** - Gerenciamento Docker
-   **[PostgreSQL Test](../scripts/test-postgres-connection.php)** - Teste seguro de conectividade 🔒

### Tasks do VS Code

Disponíveis via `Ctrl+Shift+P` → "Tasks: Run Task":

-   **Laravel: Serve** - Servidor backend
-   **Vite: Dev Server** - Desenvolvimento frontend
-   **Laravel: Fresh Migrate** - Reset database
-   **Run Tests** - Testes automatizados
-   **Generate UML Docs** - Gerar diagramas
-   **Lint & Format** - Qualidade de código

## Tecnologias Documentadas

### Backend

-   **Laravel 11** - Framework PHP moderno
-   **PHP 8.2+** - Linguagem principal
-   **PostgreSQL** - Banco de dados relacional (DevContainer integrado)
-   **Composer** - Gerenciador de dependências PHP
-   **PlantUML** - Geração de diagramas UML

### Frontend

-   **Inertia.js** - SPA sem API
-   **Vue.js 3** - Framework JavaScript reativo
-   **TypeScript** - JavaScript tipado
-   **Tailwind CSS** - Framework CSS utility-first
-   **shadcn/ui** - Componentes UI

### Ferramentas

-   **Vite** - Build tool moderno
-   **ESLint** - Linter JavaScript/TypeScript
-   **Prettier** - Formatador de código
-   **PHPStan** - Análise estática PHP
-   **Psalm** - Análise estática PHP adicional

### DevOps e Ferramentas

-   **DevContainer** - Ambiente de desenvolvimento (PostgreSQL integrado)
-   **Docker** - Containerização para produção
-   **GitHub Actions** - CI/CD automatizado com UML
-   **Xdebug** - Debugging PHP
-   **PlantUML Online** - Visualização de diagramas
-   **OWASP ZAP** - Análise de segurança

### Qualidade e Segurança

-   **PHPStan** - Análise estática PHP (nível 8)
-   **Psalm** - Análise estática adicional
-   **ESLint** - Linter JavaScript/TypeScript
-   **Prettier** - Formatador de código
-   **OWASP Top 10** - Compliance de segurança
-   **Prepared Statements** - Prevenção SQL Injection

## 🆕 Novidades e Melhorias Recentes

### Sistema UML Robusto ✨

-   Geração automática de diagramas a partir dos models Laravel
-   Sistema de encoding múltiplo com fallback automático
-   Visualização online integrada com PlantUML
-   Testes automatizados para validação

### Melhorias de Segurança 🔒

-   Script PostgreSQL refatorado com práticas seguras
-   Eliminação de credenciais hardcoded
-   Implementação de prepared statements
-   Logs seguros sem exposição de dados sensíveis

### DevContainer Aprimorado 🐳

-   PostgreSQL integrado no ambiente de desenvolvimento
-   Scripts de setup automático pós-rebuild
-   Configuração zero-hassle para novos desenvolvedores

## Estrutura de Arquivos

```
docs/
├── README.md                    # Este arquivo (índice)
├── INDEX.md                     # Índice completo (este arquivo)
├── SECURITY_ANALYSIS.md         # Análise de segurança
├── setup/
│   └── README.md               # Setup e configuração
├── development/
│   ├── README.md               # Guia de desenvolvimento
│   ├── DEVCONTAINER.md         # DevContainer guide
│   └── UML_DIAGRAMS.md         # Diagramas UML
├── deployment/
│   ├── README.md               # Deployment guide
│   └── DOCKER.md               # Docker setup
└── migrations/
    ├── README.md               # Database migrations
    └── POSTGRESQL_MIGRATION.md # PostgreSQL migration
```

## Links Externos Úteis

### Documentação Oficial

-   **[Laravel](https://laravel.com/docs)** - Documentação oficial do Laravel
-   **[Inertia.js](https://inertiajs.com)** - Documentação do Inertia.js
-   **[Vue.js](https://vuejs.org)** - Documentação do Vue.js 3
-   **[Tailwind CSS](https://tailwindcss.com)** - Documentação do Tailwind

### Ferramentas e Recursos

-   **[PlantUML Online](http://www.plantuml.com/plantuml/uml/)** - Visualizar diagramas UML ⭐
-   **[PostgreSQL Docs](https://www.postgresql.org/docs/)** - Documentação PostgreSQL
-   **[Docker Docs](https://docs.docker.com)** - Documentação Docker
-   **[TypeScript Handbook](https://www.typescriptlang.org/docs/)** - Guia TypeScript
-   **[OWASP Top 10](https://owasp.org/Top10/)** - Guia de segurança web

### Segurança

-   **[OWASP ZAP](https://zaproxy.org/)** - Scanner de vulnerabilidades
-   **[Security Headers](https://securityheaders.com/)** - Análise de headers
-   **[SSL Labs](https://www.ssllabs.com/ssltest/)** - Teste SSL/TLS

## 📋 Como Atualizar Esta Documentação

1. **Editar arquivos Markdown** na pasta `docs/`
2. **Seguir estrutura consistente** com seções claras e emojis
3. **Incluir exemplos práticos** sempre que possível
4. **Manter links internos atualizados**
5. **Atualizar este índice** quando adicionar novos documentos
6. **Verificar segurança** ao documentar scripts e configurações

## 🤝 Feedback e Contribuições

-   **Issues:** Reporte problemas na documentação via GitHub Issues
-   **Pull Requests:** Contribuições são bem-vindas
-   **Sugestões:** Use GitHub Discussions para ideias
-   **Segurança:** Reporte vulnerabilidades via email privado

---

**📅 Última atualização:** Julho 2025  
**📖 Versão da documentação:** 3.0  
**🔒 Nível de segurança:** OWASP Top 10 2021 Compliant  
**⚡ Features:** UML Automático + DevContainer PostgreSQL + Scripts Seguros
