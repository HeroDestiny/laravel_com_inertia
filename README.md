# 🚀 Laravel com Inertia.js

Aplicação moderna desenvolvida com Laravel e Inertia.js, utilizando Vue.js 3, TypeScript e Tailwind CSS.

## ⚡ Início Rápido

### Pré-requisitos

- PHP 8.2+
- Node.js 18+
- Composer

### Instalação

1. Clone o repositório
2. Instale as dependências:

```bash
cd src
composer install
npm install
```

3. Configure o ambiente:

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

4. Inicie o servidor de desenvolvimento:

```bash
php artisan serve
npm run dev
```

## 🛠️ Tecnologias

- **Backend**: Laravel 11
- **Frontend**: Inertia.js + Vue.js 3 + TypeScript
- **Styling**: Tailwind CSS + shadcn/ui
- **Desenvolvimento**: DevContainer + Xdebug

## 📚 Documentação

A documentação completa está disponível em [`docs/`](./docs/):

- **[📖 Documentação Completa](./docs/README.md)** - Índice geral da documentação
- **[🚀 Setup](./docs/setup/)** - Configuração inicial
- **[💻 Development](./docs/development/)** - Guias de desenvolvimento
- **[🚢 Deployment](./docs/deployment/)** - Deploy e produção
- **[🔒 Análise de Segurança](./docs/SECURITY_ANALYSIS.md)** - Análise OWASP Top 10

### 🔗 Links Rápidos

- [🐳 Development Container](./docs/development/DEVCONTAINER.md)
- [🎨 Diagramas UML](./docs/development/UML_DIAGRAMS.md)
- [🐳 Docker Setup](./docs/deployment/DOCKER.md)
- [🐘 PostgreSQL Migration](./docs/migrations/POSTGRESQL_MIGRATION.md)

## 🧪 Comandos Úteis

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

## 🤝 Contribuindo

1. Fork o projeto
2. Crie sua branch de feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## � Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.
