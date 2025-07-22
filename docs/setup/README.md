# Setup e Configuração

Guias para configuração inicial do projeto Laravel com Inertia.js.

## Opções de Setup

### 1. DevContainer (Recomendado)

A forma mais rápida e consistente:

1. Abra o projeto no VS Code
2. Instale a extensão "Dev Containers"
3. Clique em "Reopen in Container"
4. Aguarde a configuração automática

**Documentação:** [DevContainer Guide](../development/DEVCONTAINER.md)

### 2. Instalação Local

Para desenvolvimento em ambiente local:

```bash
# Clone e instale dependências
git clone https://github.com/HeroDestiny/laravel_com_inertia.git
cd laravel_com_inertia/src
composer install && npm install

# Configure ambiente
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# Inicie servidores
php artisan serve --host=0.0.0.0 --port=8000
npm run dev
```

## Documentos Relacionados

-   **[DevContainer Guide](../development/DEVCONTAINER.md)** - Ambiente containerizado
-   **[Development Guide](../development/README.md)** - Fluxo de desenvolvimento
-   **[Deployment Guide](../deployment/README.md)** - Deploy para produção
