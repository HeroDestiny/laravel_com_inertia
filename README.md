# Laravel com Inertia.js

Uma aplicação moderna desenvolvida com Laravel 11, Inertia.js, Vue.js 3, TypeScript e Tailwind CSS.

## Características

- **Backend:** Laravel 11 com PHP 8.2+
- **Frontend:** Vue.js 3 + TypeScript + Inertia.js
- **Styling:** Tailwind CSS + shadcn/ui components
- **Database:** PostgreSQL (produção) / SQLite (desenvolvimento)
- **DevContainer:** Ambiente de desenvolvimento containerizado
- **CI/CD:** GitHub Actions com testes automatizados
- **UML:** Geração automática de diagramas de domínio

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

- PHP 8.2+
- Node.js 18+
- Composer
- PostgreSQL (opcional)

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

- **Aplicação:** http://localhost:8000
- **Vite Dev Server:** http://localhost:5173

## Tecnologias

- **Backend:** Laravel 11 + PHP 8.2
- **Frontend:** Inertia.js + Vue.js 3 + TypeScript
- **Styling:** Tailwind CSS + shadcn/ui components
- **Database:** PostgreSQL / SQLite
- **Tools:** Vite, ESLint, Prettier, PHPStan, Psalm
- **Development:** DevContainer + Xdebug

## Documentação

A documentação completa está organizada em [`docs/`](./docs/):

### Guias Principais

- **[Documentação Completa](./docs/README.md)** - Índice geral
- **[Setup e Instalação](./docs/setup/)** - Configuração inicial
- **[Desenvolvimento](./docs/development/)** - Guias para desenvolvedores
- **[Deploy e Produção](./docs/deployment/)** - Configurações de produção
- **[Segurança](./docs/SECURITY_ANALYSIS.md)** - Análise OWASP Top 10

### Links Úteis

- [DevContainer Guide](./docs/development/DEVCONTAINER.md) - Ambiente containerizado
- [UML Diagrams](./docs/development/UML_DIAGRAMS.md) - Diagramas automáticos
- [Docker Production](./docs/deployment/DOCKER.md) - Deploy com Docker
- [Scripts Utilities](./scripts/README.md) - Scripts auxiliares

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
- [PostgreSQL Migration](./docs/migrations/POSTGRESQL_MIGRATION.md)

## Comandos Úteis

```bash
# Executar testes
php artisan test

# Gerar diagramas UML
npm run docs:uml

# Linting e formatação
npm run lint

# Build para produção
npm run build
```

## Contribuindo

1. Fork o projeto
2. Crie sua branch de feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## � Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.
