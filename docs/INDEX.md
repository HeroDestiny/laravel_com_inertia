# 📖 Índice da Documentação

-   **[Development Guide](./development/README.md)** - Fluxo completo
-   **[DevContainer](./development/DEVCONTAINER.md)** - Ambiente containerizado
-   **[UML Diagrams](./development/UML_DIAGRAMS.md)** - Diagramas automáticos
-   **[UML Scripts](./development/UML_SCRIPTS.md)** - Scripts detalhados de geração UML
-   **[NPM Commands](./development/NPM_COMMANDS.md)** - Todos os comandos NPM disponíveis

Navegação rápida por toda a documentação do projeto Laravel + Inertia.js.

## 🚀 Guias Essenciais

### Para Iniciantes

1. **[⚙️ Setup](./setup/README.md)** - Configure seu ambiente
2. **[🐳 DevContainer](./development/DEVCONTAINER.md)** - Ambiente recomendado
3. **[💻 Development](./development/README.md)** - Fluxo de desenvolvimento

### Para Deploy

1. **[🚢 Deployment](./deployment/README.md)** - Guia de deploy
2. **[🐋 Docker](./deployment/DOCKER.md)** - Deploy com containers

## 📚 Documentação completa

### 🛠️ Desenvolvimento

-   **[Development Guide](./development/README.md)** - Fluxo completo
-   **[DevContainer](./development/DEVCONTAINER.md)** - Ambiente containerizado
-   **[UML Diagrams](./development/UML_DIAGRAMS.md)** - Diagramas automáticos

### 🧪 Testes

-   **[Testing Guide](./testing/README.md)** - 55 testes, 190 asserções
-   **[Best Practices](./testing/BEST_PRACTICES.md)** - Padrões de teste
-   **[Troubleshooting](./testing/TROUBLESHOOTING.md)** - Solução de problemas

### 🗄️ Database

-   **[Migration Guide](./migrations/README.md)** - Gerenciamento de banco
-   **[PostgreSQL Migration](./migrations/POSTGRESQL_MIGRATION.md)** - Migração específica

### 🔒 Segurança

-   **[Security Analysis](./SECURITY_ANALYSIS.md)** - Análise OWASP Top 10

## 🔧 Utilitários

### Scripts Essenciais

-   **[Scripts Overview](../scripts/README.md)** - Todos os scripts
-   **[Quick Check](../scripts/quick-check-local.sh)** - Verificações rápidas
-   **[Setup After Rebuild](../scripts/setup-after-rebuild.sh)** - Pós-rebuild

### Tasks do VS Code

Execute via `Ctrl+Shift+P` → "Tasks: Run Task":

-   **Laravel: Serve** - Servidor backend
-   **Vite: Dev Server** - Desenvolvimento frontend
-   **Run Tests** - Testes automatizados
-   **Generate UML** - Diagramas UML

## 🛠️ Stack Tecnológico

### Backend

-   **Laravel 11** + **PHP 8.2+** + **PostgreSQL**
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

## 🆕 Novidades v3.0

### ✨ Sistema UML Automático

-   Geração automática de diagramas
-   Múltiplos métodos de encoding
-   Visualização online integrada

### 🔒 Segurança Aprimorada

-   Scripts PostgreSQL seguros
-   OWASP Top 10 2021 compliance
-   Logs seguros sem exposição de dados

### 🐳 DevContainer

-   PostgreSQL integrado
-   Configuração automática pós-rebuild
-   Zero configuração necessária

## ⚡ Comandos Rápidos

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

## 🔍 Links Externos

### Documentação Oficial

-   [Laravel](https://laravel.com/docs)
-   [Inertia.js](https://inertiajs.com)
-   [Vue.js](https://vuejs.org)
-   [Tailwind CSS](https://tailwindcss.com)

### Ferramentas

-   [PlantUML Online](http://www.plantuml.com/plantuml/uml/)
-   [OWASP Top 10](https://owasp.org/Top10/)
-   [PostgreSQL Docs](https://www.postgresql.org/docs/)

## 🤝 Contribuindo

1. **Issues:** Reporte problemas via GitHub Issues
2. **Pull Requests:** Contribuições são bem-vindas
3. **Discussões:** Use GitHub Discussions para ideias
4. **Segurança:** Reporte vulnerabilidades via email

---

**📅 Atualizado:** Julho 2025 | **📖 Versão:** 3.0 | **🔒 Segurança:** OWASP Compliant
