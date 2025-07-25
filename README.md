# Laravel com Inertia.js

Uma aplicação moderna desenvolvida com Laravel 11, Inertia.js, Vue.js 3, TypeScript e Tailwind CSS.

## ✨ Recursos Principais

-   **🚀 Stack Moderna**: Laravel 11 + Inertia.js + Vue.js 3 + TypeScript
-   **🎨 Interface**: Tailwind CSS + shadcn/ui components
-   **🐘 Database**: PostgreSQL (DevContainer) / SQLite (fallback)
-   **🐳 DevContainer**: Ambiente zero-config com PostgreSQL integrado
-   **📊 UML**: Geração automática de diagramas com PlantUML
-   **🔒 Segurança**: OWASP Top 10 2021 compliant
-   **🧪 Testes**: 55 testes com 190 assertions (100% passando)
-   **⚡ CI/CD**: GitHub Actions automatizado

## 🚀 Início Rápido

### DevContainer (Recomendado)

1. Abra o projeto no VS Code
2. Clique em "Reopen in Container" quando solicitado
3. Execute as tasks no VS Code:
    - `Laravel: Serve` (Ctrl+Shift+P > Tasks: Run Task)
    - `Vite: Dev Server`

### Instalação Manual

#### Pré-requisitos

-   PHP 8.2+
-   Node.js 18+
-   Composer
-   PostgreSQL (opcional)

#### Passos

```bash
# 1. Clone e instale dependências
git clone https://github.com/HeroDestiny/laravel_com_inertia.git
cd laravel_com_inertia/src
composer install && npm install

# 2. Configure o ambiente
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# 3. Inicie os servidores
php artisan serve --host=0.0.0.0 --port=8000
npm run dev
```

**Acesso**: http://localhost:8000

## 📚 Documentação

### 📖 Guias Principais

-   **[📚 Documentação Completa](./docs/)** - Toda a documentação
-   **[⚙️ Setup](./docs/setup/)** - Configuração inicial
-   **[💻 Desenvolvimento](./docs/development/)** - Guias para desenvolvedores
-   **[🚢 Deploy](./docs/deployment/)** - Configurações de produção
-   **[🧪 Testes](./docs/testing/)** - Estratégia de testes
-   **[🔒 Segurança](./docs/SECURITY_ANALYSIS.md)** - Análise OWASP Top 10

### 🔗 Links Rápidos

-   [DevContainer Guide](./docs/development/DEVCONTAINER.md) - Ambiente containerizado
-   [UML Diagrams](./docs/development/UML_DIAGRAMS.md) - Diagramas automáticos
-   [Docker Production](./docs/deployment/DOCKER.md) - Deploy com Docker
-   [Scripts Utilities](./scripts/README.md) - Scripts auxiliares

## ⚡ Comandos Essenciais

```bash
# Desenvolvimento
php artisan serve --host=0.0.0.0    # Servidor Laravel
npm run dev                          # Servidor Vite

# Qualidade de Código
php artisan test                     # Executar testes
npm run lint                         # Linting e formatação
./scripts/quick-check-local.sh       # Verificações completas

# UML e Documentação
php artisan generate:uml             # Gerar diagramas UML
npm run docs:uml                     # Gerar PNG + visualizar

# Produção
npm run build                        # Build para produção
php artisan migrate --force          # Migrar em produção
```

## 📊 Status do Projeto

![GitHub last commit](https://img.shields.io/github/last-commit/HeroDestiny/laravel_com_inertia)
![GitHub issues](https://img.shields.io/github/issues/HeroDestiny/laravel_com_inertia)
![GitHub pull requests](https://img.shields.io/github/issues-pr/HeroDestiny/laravel_com_inertia)
![License](https://img.shields.io/badge/license-MIT-blue.svg)

### ✅ Funcionalidades Implementadas

-   Laravel 11 + Inertia.js + Vue.js 3 + TypeScript
-   DevContainer com PostgreSQL integrado
-   Sistema UML automático
-   Segurança OWASP Top 10 compliance
-   Scripts seguros para PostgreSQL
-   CI/CD com GitHub Actions
-   Análise estática (PHPStan, Psalm)
-   Testes automatizados (55 testes, 190 assertions)

## 🤝 Contribuindo

1. Fork o projeto
2. Crie sua branch (`git checkout -b feature/amazing-feature`)
3. Execute os testes (`./scripts/quick-check-local.sh`)
4. Commit suas mudanças (`git commit -m 'Add amazing feature'`)
5. Push para a branch (`git push origin feature/amazing-feature`)
6. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

**⭐ Se este projeto foi útil, considere dar uma estrela!**

**📧 Suporte:** [Issues](https://github.com/HeroDestiny/laravel_com_inertia/issues) | [Discussions](https://github.com/HeroDestiny/laravel_com_inertia/discussions)
