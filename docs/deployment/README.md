# Deployment Guide

Guia completo para deploy do projeto Laravel + Inertia.js em produção.

## Opções de Deploy

### 1. Docker (Recomendado)

Deploy containerizado para produção:

**Vantagens:**

-   Ambiente isolado e consistente
-   Fácil escalabilidade
-   Configuração de produção otimizada
-   Suporte a PostgreSQL

**Documentação:** [Docker Setup Detalhado](./DOCKER.md)

#### Quick Start Docker

```bash
# 1. Configure variáveis de produção
cp .env.example .env.production

# 2. Build e deploy
docker-compose -f docker/production/docker-compose.yml up -d --build

# 3. Execute migrações
docker exec laravel-app php artisan migrate --force
```

### 2. Deploy Tradicional

Para servidores VPS ou dedicados:

#### Requisitos do Servidor

-   **PHP:** 8.2+ com extensões: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
-   **Web Server:** Nginx ou Apache
-   **Database:** PostgreSQL 13+ ou MySQL 8.0+
-   **Node.js:** 18 LTS (para build dos assets)
-   **Composer:** 2.5+

#### Configuração do Servidor

**1. Clone e Dependências**

```bash
git clone https://github.com/HeroDestiny/laravel_com_inertia.git
cd laravel_com_inertia/src

# Instalar dependências
composer install --no-dev --optimize-autoloader
npm ci
```

**2. Configuração de Ambiente**

```bash
# Configurar produção
cp .env.example .env

# Editar variáveis de produção
nano .env
```

**3. Build e Otimização**

```bash
# Build assets para produção
npm run build

# Otimizar Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

**4. Permissões**

```bash
# Configurar permissões
chown -R www-data:www-data .
chmod -R 755 .
chmod -R 775 storage bootstrap/cache
```

#### Configuração Nginx

```nginx
server {
    listen 80;
    server_name seu-dominio.com;
    root /var/www/laravel_com_inertia/src/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Configurações de Produção

### Variáveis de Ambiente (.env)

```env
# Aplicação
APP_NAME="Laravel Inertia"
APP_ENV=production
APP_KEY=base64:SEU_APP_KEY_AQUI
APP_DEBUG=false
APP_URL=https://seu-dominio.com

# Database
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=laravel_inertia_prod
DB_USERNAME=laravel_user
DB_PASSWORD=senha_segura_aqui

# Cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=seu-smtp.com
MAIL_PORT=587
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=error
```

### Otimizações de Performance

#### 1. Cache de Configuração

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

#### 2. OPcache (php.ini)

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

#### 3. Composer Autoloader

```bash
composer install --optimize-autoloader --no-dev
composer dump-autoload --optimize
```

## Monitoramento e Logs

### Logs da Aplicação

```bash
# Localização dos logs
/var/www/laravel_com_inertia/src/storage/logs/

# Monitorar logs em tempo real
tail -f storage/logs/laravel.log

# Rotação de logs (configurar no sistema)
# Laravel automaticamente rotaciona logs diários
```

### Monitoramento de Performance

#### 1. Laravel Telescope (Desenvolvimento)

```bash
# Instalar apenas em desenvolvimento
composer require laravel/telescope --dev
php artisan telescope:install
```

#### 2. APM Solutions (Produção)

-   **New Relic** - Monitoramento completo
-   **Bugsnag** - Tracking de erros
-   **DataDog** - Métricas e logs

### Health Checks

```bash
# Endpoint de health check
# Adicionar rota em routes/web.php
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'database' => DB::connection()->getPdo() ? 'connected' : 'error',
        'cache' => Cache::put('health_check', 'ok', 60) ? 'ok' : 'error'
    ]);
});
```

## Backup e Manutenção

### Backup Automático

```bash
#!/bin/bash
# Script de backup diário

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/laravel_inertia"

# Backup database
pg_dump laravel_inertia_prod > $BACKUP_DIR/db_$DATE.sql

# Backup storage
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz /var/www/laravel_com_inertia/src/storage

# Limpar backups antigos (manter 7 dias)
find $BACKUP_DIR -type f -mtime +7 -delete
```

### Updates de Produção

```bash
#!/bin/bash
# Script de deploy/update

# Modo manutenção
php artisan down

# Atualizar código
git pull origin main

# Atualizar dependências
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Migrações
php artisan migrate --force

# Limpar e recriar caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Sair do modo manutenção
php artisan up
```

## SSL/HTTPS

### Let's Encrypt (Recomendado)

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-nginx

# Obter certificado
sudo certbot --nginx -d seu-dominio.com

# Renovação automática
sudo crontab -e
# Adicionar: 0 12 * * * /usr/bin/certbot renew --quiet
```

## Troubleshooting

### Problemas Comuns

**Erro 500:**

```bash
# Verificar logs
tail -f storage/logs/laravel.log

# Verificar permissões
chown -R www-data:www-data storage bootstrap/cache
```

**Assets não carregam:**

```bash
# Rebuild assets
npm run build

# Verificar storage link
php artisan storage:link
```

**Database connection:**

```bash
# Testar conexão
php artisan tinker
>>> DB::connection()->getPdo();
```

## Documentos Relacionados

-   **[Docker Deployment](./DOCKER.md)** - Deploy com containers
-   **[Development Guide](../development/README.md)** - Desenvolvimento local
-   **[Security Analysis](../SECURITY_ANALYSIS.md)** - Considerações de segurança
