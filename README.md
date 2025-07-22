# Laravel com Inertia.js

Uma aplicação moderna desenvolvida com Laravel 11, Inertia.js, Vue.js 3, TypeScript e Tailwind CSS.

## ✨ Características

-   **Backend:** Laravel 11 com PHP 8.2+
-   **Frontend:** Vue.js 3 + TypeScript + Inertia.js
-   **Styling:** Tailwind CSS + shadcn/ui components
-   **Database:** PostgreSQL integrado (DevContainer) / SQLite (fallback)
-   **DevContainer:** Ambiente zero-config com PostgreSQL
-   **CI/CD:** GitHub Actions com testes automatizados
-   **UML:** Geração automática de diagramas com PlantUML 📊
-   **Segurança:** OWASP Top 10 2021 compliant 🔒
-   **Scripts:** Utilitários seguros para desenvolvimento
-   **Qualidade:** PHPStan, Psalm, ESLint integrados

## Início Rápido

### Opção 1: DevContainer (Recomendado)

1. Abra o projeto no VS Code
2. Clique em "Reopen in Container" quando solicitado
3. Aguarde a configuração automática
4. Execute as tasks do VS Code:
    - `Laravel: Serve` (Ctrl+Shift+P > Tasks: Run Task)
    - `Vite: Dev Server`

### Opção 2: Instalação Manual

#### Pré-requisitos

-   PHP 8.2+
-   Node.js 18+
-   Composer
-   PostgreSQL (opcional)

#### Instalação

1. Clone o repositório:

```bash
git clone https://github.com/HeroDestiny/laravel_com_inertia.git
cd laravel_com_inertia/src
```

2. Instale as dependências:

```bash
composer install
npm install
```

3. Configure o ambiente:

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

4. Inicie os servidores:

```bash
php artisan serve --host=0.0.0.0 --port=8000
npm run dev
```

## Acesso

-   **Aplicação:** http://localhost:8000
-   **Vite Dev Server:** http://localhost:5173

## 🆕 Novidades v3.0

### 📊 Sistema UML Automático

```bash
# Gerar diagramas UML dos seus models
php artisan generate:uml

# Visualizar online
npm run docs:uml:online
```

### 🔒 Segurança Aprimorada

-   Scripts PostgreSQL seguros (sem credenciais hardcoded)
-   Prepared statements para prevenir SQL injection
-   Análise OWASP Top 10 2021 implementada
-   Logs seguros sem exposição de dados sensíveis

### 🐳 DevContainer com PostgreSQL

-   PostgreSQL integrado no ambiente de desenvolvimento
-   Setup automático pós-rebuild
-   Zero configuração para novos desenvolvedores

## 🛠️ Tecnologias

-   **Backend:** Laravel 11 + PHP 8.2
-   **Frontend:** Inertia.js + Vue.js 3 + TypeScript
-   **Styling:** Tailwind CSS + shadcn/ui components
-   **Database:** PostgreSQL (DevContainer) / SQLite (fallback)
-   **Tools:** Vite, ESLint, Prettier, PHPStan, Psalm
-   **Development:** DevContainer + Xdebug + PlantUML
-   **Security:** OWASP compliance + Secure scripts

## 📚 Documentação

A documentação completa está organizada em [`docs/`](./docs/):

### 🚀 Guias Principais

-   **[📖 Índice Completo](./docs/INDEX.md)** - Navegação completa
-   **[⚙️ Setup e Instalação](./docs/setup/)** - Configuração inicial
-   **[💻 Desenvolvimento](./docs/development/)** - Guias para desenvolvedores
-   **[🚢 Deploy e Produção](./docs/deployment/)** - Configurações de produção
-   **[🔒 Análise de Segurança](./docs/SECURITY_ANALYSIS.md)** - OWASP Top 10
-   **[📋 Changelog](./docs/CHANGELOG.md)** - Histórico de mudanças

### 🔗 Links Úteis

-   [🐳 DevContainer Guide](./docs/development/DEVCONTAINER.md) - Ambiente containerizado
-   [📊 UML Diagrams](./docs/development/UML_DIAGRAMS.md) - Diagramas automáticos
-   [🐋 Docker Production](./docs/deployment/DOCKER.md) - Deploy com Docker
-   [🔧 Scripts Utilities](./scripts/README.md) - Scripts auxiliares

## Estrutura do Projeto

```
├── src/                    # Código fonte Laravel
│   ├── app/               # Aplicação Laravel
│   ├── resources/         # Assets Vue.js + CSS
│   └── routes/            # Rotas da aplicação
├── docs/                  # Documentação completa
├── scripts/               # Scripts utilitários
├── docker/                # Configurações Docker
└── .devcontainer/         # DevContainer config
```

## Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature
3. Execute os testes: `./scripts/quick-check-local.sh`
4. Commit suas mudanças
5. Abra um Pull Request

## Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para detalhes.

-   [PostgreSQL Migration](./docs/migrations/POSTGRESQL_MIGRATION.md)

## ⚡ Comandos Úteis

```bash
# Desenvolvimento
php artisan serve --host=0.0.0.0        # Servidor Laravel
npm run dev                              # Vite dev server

# Qualidade de Código
php artisan test                         # Executar testes
npm run lint                            # Linting e formatação
./scripts/quick-check-local.sh          # Verificações locais

# Diagramas UML
php artisan generate:uml                 # Gerar arquivo .puml
npm run docs:uml                        # Gerar diagrama completo
npm run docs:uml:online                 # Abrir PlantUML online

# Segurança e Diagnóstico
php scripts/test-postgres-connection.php # Teste PostgreSQL seguro
python3 scripts/check_uml_system.py     # Diagnóstico UML

# Produção
npm run build                           # Build para produção
php artisan migrate --force             # Migrar em produção
```

## 📊 Status do Projeto

![GitHub last commit](https://img.shields.io/github/last-commit/HeroDestiny/laravel_com_inertia)
![GitHub issues](https://img.shields.io/github/issues/HeroDestiny/laravel_com_inertia)
![GitHub pull requests](https://img.shields.io/github/issues-pr/HeroDestiny/laravel_com_inertia)

### Funcionalidades

-   ✅ Laravel 11 + Inertia.js + Vue.js 3
-   ✅ DevContainer com PostgreSQL integrado
-   ✅ Sistema UML automático
-   ✅ Segurança OWASP Top 10 compliance
-   ✅ Scripts seguros para PostgreSQL
-   ✅ CI/CD com GitHub Actions
-   ✅ Análise estática (PHPStan, Psalm)
-   ✅ Testes automatizados
-   🔄 Docker para produção (em desenvolvimento)

## 🤝 Contribuindo

1. Fork o projeto
2. Crie sua branch de feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

### 🔒 Reportar Vulnerabilidades

Para questões de segurança, entre em contato diretamente via email privado.

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

**⭐ Se este projeto foi útil, considere dar uma estrela!**

**📧 Suporte:** Para dúvidas, abra uma [issue](https://github.com/HeroDestiny/laravel_com_inertia/issues) ou use [Discussions](https://github.com/HeroDestiny/laravel_com_inertia/discussions)
