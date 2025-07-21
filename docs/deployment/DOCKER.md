# Docker Setup - Para Deploy e Produção

Este diretório contém configurações Docker para quando você quiser fazer deploy em produção ou configurar CI/CD.

## Quando Usar

- **Deploy em produção**
- **CI/CD Pipeline**
- **Ambientes de staging**
- **Desenvolvimento local** (use o devcontainer)

## Estrutura

```
docker/
├── development/          # Laravel Sail (opcional)
│   └── docker-compose.yml
├── production/          # Deploy profissional
│   ├── Dockerfile
│   ├── docker-compose.yml
│   └── nginx.conf
└── README.md           # Este arquivo
```

## Deploy Rápido

### Opção 1: Laravel Sail (Desenvolvimento)

```bash
# Instalar Sail
composer require laravel/sail --dev
php artisan sail:install

# Usar Sail
./vendor/bin/sail up -d
./vendor/bin/sail npm run dev
```

### Opção 2: Docker Personalizado (Produção)

```bash
# Build
docker build -f docker/production/Dockerfile -t laravel-inertia .

# Run
docker run -p 80:80 laravel-inertia
```

### Opção 3: Docker Compose (Completo)

```bash
cd docker/production
docker-compose up -d
```

## Configuração

### Para Desenvolvimento

Se quiser experimentar Docker localmente:

1. Use Laravel Sail: `php artisan sail:install`
2. Ou mantenha o devcontainer (recomendado)

### Para Produção

1. Configure as variáveis de ambiente
2. Use o Dockerfile de produção
3. Configure reverse proxy (Nginx/Traefik)

## Importante

- **Desenvolvimento**: Continue usando o devcontainer
- **Produção**: Use os arquivos Docker desta pasta
- **Performance**: Docker pode ser mais lento para desenvolvimento
