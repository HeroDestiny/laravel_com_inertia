# Dockerfile para Produção
# Multi-stage build para otimizar tamanho da imagem

# Stage 1: Build Node.js assets
FROM node:18-alpine AS node-builder

WORKDIR /app
COPY src/package*.json ./
RUN npm ci --only=production

COPY src/resources ./resources
COPY src/vite.config.ts ./
COPY src/tsconfig.json ./
COPY src/tailwind.config.js ./
COPY src/.prettierrc ./
COPY src/eslint.config.js ./

RUN npm run build

# Stage 2: PHP + Nginx
FROM php:8.2-fpm-alpine AS php-base

# Instalar dependências do sistema
RUN apk add --no-cache \
    nginx \
    supervisor \
    postgresql-client \
    zip \
    unzip \
    git \
    curl \
    libpq-dev

# Instalar extensões PHP
RUN docker-php-ext-install pdo pdo_pgsql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Nginx
COPY docker/production/nginx.conf /etc/nginx/nginx.conf

# Configurar Supervisor
COPY docker/production/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Stage 3: Application
FROM php-base AS app

WORKDIR /var/www/html

# Copiar código da aplicação
COPY src/ .

# Instalar dependências PHP (produção)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copiar assets compilados do stage anterior
COPY --from=node-builder /app/public/build ./public/build

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Otimizações Laravel (cache será feito após conexão com DB)
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Script de inicialização
COPY docker/production/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expor porta
EXPOSE 80

# Comando de inicialização
CMD ["/entrypoint.sh"]
