# Dockerfile para Produção
# Multi-stage build para otimizar tamanho da imagem

# Stage 1: Build Node.js assets
FROM node:18.19.1-alpine AS node-builder

WORKDIR /app

# Create non-root user for build
RUN addgroup -g 1001 nodegroup \
    && adduser -u 1001 -G nodegroup -s /bin/sh -D nodeuser

# Copy package files and install dependencies as non-root
COPY --chown=nodeuser:nodegroup src/package*.json ./
USER nodeuser
RUN npm ci --only=production --no-audit --no-fund

# Copy source files and build
COPY --chown=nodeuser:nodegroup src/resources ./resources
COPY --chown=nodeuser:nodegroup src/vite.config.ts ./
COPY --chown=nodeuser:nodegroup src/tsconfig.json ./
COPY --chown=nodeuser:nodegroup src/tailwind.config.js ./
COPY --chown=nodeuser:nodegroup src/.prettierrc ./
COPY --chown=nodeuser:nodegroup src/eslint.config.js ./

RUN npm run build

# Stage 2: PHP + Nginx base
FROM php:8.2.15-fpm-alpine AS php-base

# Security: Use specific versions and install security updates
RUN apk upgrade --no-cache \
    && apk add --no-cache \
    nginx=1.24.0-r15 \
    supervisor=4.2.5-r4 \
    postgresql-client=15.5-r0 \
    zip=3.0-r12 \
    unzip=6.0-r14 \
    git=2.40.1-r0 \
    curl=8.5.0-r0 \
    libpq-dev \
    shadow \
    su-exec

# Create a non-root user for the application
RUN addgroup -g 1000 appgroup \
    && adduser -u 1000 -G appgroup -s /bin/sh -D appuser

# Instalar extensões PHP
RUN docker-php-ext-install pdo pdo_pgsql

# Instalar Composer com versão fixa
COPY --from=composer:2.6.6 /usr/bin/composer /usr/bin/composer

# Create necessary directories with proper permissions
RUN mkdir -p /var/log/nginx /var/log/supervisor /run/nginx /var/lib/nginx/tmp /var/cache/nginx \
    && chown -R appuser:appgroup /var/log/nginx /var/log/supervisor /run/nginx /var/lib/nginx /var/cache/nginx \
    && chmod 755 /var/log/nginx /var/log/supervisor /run/nginx /var/lib/nginx /var/cache/nginx

# Configurar Nginx
COPY docker/production/nginx.conf /etc/nginx/nginx.conf

# Configurar Supervisor
COPY docker/production/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Stage 3: Application
FROM php-base AS app

WORKDIR /var/www/html

# Security: Copy only necessary application files (exclude sensitive files)
COPY --chown=appuser:appgroup src/app ./app
COPY --chown=appuser:appgroup src/bootstrap ./bootstrap
COPY --chown=appuser:appgroup src/config ./config
COPY --chown=appuser:appgroup src/database ./database
COPY --chown=appuser:appgroup src/public ./public
COPY --chown=appuser:appgroup src/resources ./resources
COPY --chown=appuser:appgroup src/routes ./routes
COPY --chown=appuser:appgroup src/storage ./storage
COPY --chown=appuser:appgroup src/artisan ./artisan
COPY --chown=appuser:appgroup src/composer.json ./composer.json
COPY --chown=appuser:appgroup src/composer.lock ./composer.lock

# Switch to non-root user for composer install
USER appuser

# Instalar dependências PHP (produção) como non-root
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --no-plugins

# Switch back to root temporarily for final setup
USER root

# Copiar assets compilados do stage anterior
COPY --from=node-builder --chown=appuser:appgroup /app/public/build ./public/build

# Configurar permissões finais
RUN chown -R appuser:appgroup /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache \
    && chmod -R 644 /var/www/html/public \
    && chmod 755 /var/www/html/public

# Script de inicialização
COPY docker/production/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:8080/health || exit 1

# Security: Remove unnecessary packages and clean cache
RUN apk del --no-cache git curl \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/* \
    && rm -rf /root/.composer /root/.npm

# Security: Switch to non-root user for runtime
USER appuser

# Expor porta
EXPOSE 8080

# Comando de inicialização
CMD ["/entrypoint.sh"]
