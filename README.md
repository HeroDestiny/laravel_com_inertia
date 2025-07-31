# Laravel com Inertia.js

Sistema moderno de gerenciamento desenvolvido com Laravel 11, Inertia.js, Vue.js 3 e TypeScript.

## Visão Geral

Este projeto é uma aplicação web full-stack que combina o poder do Laravel como backend com a modernidade do Vue.js 3 e TypeScript no frontend, conectados através do Inertia.js para criar uma Single Page Application (SPA) sem a complexidade de uma API REST tradicional.

## Tecnologias Utilizadas

### Backend

-   **Laravel 12.0** - Framework PHP moderno
-   **PHP 8.2+** - Linguagem de programação
-   **PostgreSQL** - Banco de dados principal (SQLite como fallback)
-   **Inertia.js** - Adaptador para SPAs modernas

### Frontend

-   **Vue.js 3** - Framework JavaScript progressivo
-   **TypeScript** - Superset tipado do JavaScript
-   **Tailwind CSS** - Framework CSS utilitário
-   **Vite** - Build tool e dev server

### Ferramentas de Desenvolvimento

-   **DevContainer** - Ambiente de desenvolvimento containerizado
-   **Docker** - Containerização
-   **PlantUML** - Geração de diagramas UML
-   **ESLint** - Linter para JavaScript/TypeScript
-   **PHPStan/Psalm** - Análise estática PHP
-   **GitHub Actions** - CI/CD

## Funcionalidades

### Sistema de Autenticação

-   Login e registro de usuários
-   Verificação de email
-   Recuperação de senha
-   Middleware de autenticação

### Gerenciamento de Pacientes

-   Cadastro completo de pacientes
-   Listagem e busca
-   Edição e exclusão
-   Validação de dados

### Interface do Usuário

-   Design responsivo
-   Componentes reutilizáveis
-   Navegação intuitiva
-   Feedback visual para ações

## Instalação e Configuração

### Pré-requisitos

-   PHP 8.2 ou superior
-   Node.js 18 ou superior
-   Composer
-   Git
-   PostgreSQL (opcional - SQLite incluído)

### Instalação com DevContainer (Recomendado)

1. Clone o repositório:

```bash
git clone https://github.com/HeroDestiny/laravel_com_inertia.git
cd laravel_com_inertia
```

2. Abra o projeto no VS Code:

```bash
code .
```

3. Quando solicitado, clique em "Reopen in Container"

4. Execute as tasks do VS Code:
    - Laravel: Serve
    - Vite: Dev Server

### Instalação Manual

1. Clone e acesse o diretório:

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

````

## Uso da Aplicação

### Acessos
- **Aplicação**: http://localhost:8000
- **Mailhog** (emails): http://localhost:8025 (quando usando DevContainer)

### Fluxo Básico
1. Acesse a aplicação
2. Registre um novo usuário ou faça login
3. Acesse o dashboard
4. Gerencie pacientes através do menu

## Comandos Disponíveis

### PHP/Laravel
```bash
# Executar migrações
php artisan migrate

# Executar migrações com dados de teste
php artisan migrate:fresh --seed

# Executar testes
php artisan test

# Gerar documentação UML
php artisan generate:uml

# Servir aplicação
php artisan serve
````

### Node.js/Frontend

```bash
# Desenvolvimento
npm run dev

# Build para produção
npm run build

# Executar linter
npm run lint

# Verificar tipos TypeScript
npm run type-check

# Testes JavaScript
npm run test:js

# Gerar diagramas UML
npm run docs:uml
```

### Docker

```bash
# Gerenciar containers
npm run docker:manager

# Validar segurança
npm run docker:validate

# Testar conexão PostgreSQL
npm run test:postgres
```

## Estrutura do Projeto

```
laravel_com_inertia/
├── docker/                    # Configurações Docker
│   ├── development/           # Ambiente de desenvolvimento
│   ├── postgres/             # PostgreSQL setup
│   └── production/           # Ambiente de produção
├── docs/                     # Documentação completa
│   ├── development/          # Guias de desenvolvimento
│   ├── deployment/           # Configurações de deploy
│   ├── testing/              # Estratégias de teste
│   └── setup/               # Configuração inicial
├── scripts/                  # Scripts auxiliares
├── src/                     # Código principal da aplicação
│   ├── app/                 # Aplicação Laravel
│   │   ├── Http/Controllers/ # Controladores
│   │   ├── Models/          # Modelos Eloquent
│   │   └── Providers/       # Service Providers
│   ├── database/            # Migrações e seeders
│   ├── resources/           # Frontend (Vue.js + TypeScript)
│   │   ├── js/             # Código JavaScript/TypeScript
│   │   ├── css/            # Estilos CSS
│   │   └── views/          # Templates
│   ├── routes/             # Definições de rotas
│   ├── tests/              # Testes automatizados
│   └── storage/            # Arquivos de storage
└── README.md               # Este arquivo
```

## Arquitetura

### Backend (Laravel)

-   **MVC Pattern**: Separação clara entre Model, View e Controller
-   **Eloquent ORM**: Para interação com banco de dados
-   **Middleware**: Para autenticação e autorização
-   **Service Providers**: Para configuração de serviços
-   **Form Requests**: Para validação de dados

### Frontend (Vue.js + Inertia)

-   **Components**: Componentes Vue.js reutilizáveis
-   **TypeScript**: Tipagem estática para JavaScript
-   **Tailwind CSS**: Estilização utilitária
-   **Inertia.js**: Bridge entre Laravel e Vue.js

### Banco de Dados

-   **PostgreSQL**: Banco principal (ambiente containerizado)
-   **SQLite**: Fallback para desenvolvimento local
-   **Migrações**: Controle de versão do banco
-   **Seeders**: Dados de teste e inicialização

## Desenvolvimento

### Estrutura de Desenvolvimento

```bash
src/
├── app/Http/Controllers/
│   ├── PacienteController.php    # CRUD de pacientes
│   ├── Auth/                     # Autenticação
│   └── Settings/                 # Configurações
├── app/Models/
│   ├── User.php                  # Modelo de usuário
│   └── Paciente.php             # Modelo de paciente
├── resources/js/
│   ├── Components/              # Componentes Vue.js
│   ├── Pages/                   # Páginas da aplicação
│   ├── Layouts/                 # Layouts base
│   └── app.ts                   # Entry point
└── routes/
    ├── web.php                  # Rotas web
    ├── auth.php                 # Rotas de autenticação
    └── settings.php             # Rotas de configurações
```

### Padrões de Código

-   **PSR-12**: Padrão de codificação PHP
-   **TypeScript Strict**: Tipagem rígida
-   **ESLint**: Linting JavaScript/TypeScript
-   **Prettier**: Formatação automática

## Testes

### Cobertura de Testes

-   **55 testes** implementados
-   **190 assertions** executadas
-   Cobertura focada em funcionalidades críticas

### Tipos de Teste

```bash
# Todos os testes
php artisan test

# Apenas testes unitários
php artisan test --testsuite=Unit

# Apenas testes de funcionalidade
php artisan test --testsuite=Feature

# Com relatório de cobertura
php artisan test --coverage
```

### Testes JavaScript

```bash
# Executar testes JS
npm run test:js

# Com watching
npm run test:js:watch

# Com cobertura
npm run test:js:coverage
```

## Segurança

### Implementações de Segurança

-   **OWASP Top 10 2021** compliance
-   **CSRF Protection**: Proteção contra ataques CSRF
-   **SQL Injection**: Prevenção via Eloquent ORM
-   **XSS Protection**: Escape automático de dados
-   **Authentication**: Sistema robusto de autenticação
-   **Authorization**: Controle de acesso granular

### Configurações de Segurança

-   Headers de segurança configurados
-   Validação rigorosa de entrada
-   Sanitização de dados
-   Rate limiting implementado

## Documentação Técnica

### Diagramas UML

O projeto inclui geração automática de diagramas UML:

```bash
# Gerar todos os diagramas
npm run docs:uml

# Apenas arquivos PlantUML
npm run docs:uml:puml

# Gerar PNG
npm run docs:uml:png

# Gerar SVG
npm run docs:uml:svg

# Todos os formatos
npm run docs:uml:all
```

### Links da Documentação

-   [Configuração Completa](./docs/setup/README.md)
-   [Guia de Desenvolvimento](./docs/development/README.md)
-   [Deployment](./docs/deployment/README.md)
-   [Testes](./docs/testing/README.md)
-   [Segurança](./docs/SECURITY_ANALYSIS.md)
-   [Migrações](./docs/migrations/README.md)

## Deployment

### Ambiente de Produção

```bash
# Build de produção
npm run build

# Otimizações Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Executar migrações
php artisan migrate --force
```

### Docker (Produção)

```bash
# Build da imagem
docker build -f docker/production/Dockerfile -t laravel-app .

# Executar container
docker run -p 8000:8000 laravel-app
```

## Contribuição

### Processo de Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

### Padrões de Commit

-   `feat:` Nova funcionalidade
-   `fix:` Correção de bug
-   `docs:` Mudanças na documentação
-   `style:` Mudanças de formatação
-   `refactor:` Refatoração de código
-   `test:` Adição ou modificação de testes

## Suporte e Recursos

### Recursos Úteis

-   [Documentação Laravel](https://laravel.com/docs)
-   [Documentação Vue.js](https://vuejs.org/guide/)
-   [Documentação Inertia.js](https://inertiajs.com/)
-   [Documentação TypeScript](https://www.typescriptlang.org/docs/)
-   [Documentação Tailwind CSS](https://tailwindcss.com/docs)

### Suporte

-   Abra uma [issue](https://github.com/HeroDestiny/laravel_com_inertia/issues) para bugs
-   Faça uma [discussão](https://github.com/HeroDestiny/laravel_com_inertia/discussions) para dúvidas
-   Consulte a [documentação](./docs/) para guias detalhados

## Licença

Este projeto está licenciado sob a [Licença MIT](LICENSE) - veja o arquivo LICENSE para detalhes.

## Changelog

Consulte o [CHANGELOG.md](./docs/CHANGELOG.md) para ver as mudanças em cada versão.

---

**Versão**: 3.0.0  
**Autor**: HeroDestiny  
**Repositório**: [GitHub](https://github.com/HeroDestiny/laravel_com_inertia)
