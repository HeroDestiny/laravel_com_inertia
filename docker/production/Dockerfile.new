# Dockerfile para Produção
# Multi-stage build para otimizar o tamanho da imagem

# Stage 1: Construção dos assets Node.js
FROM node:18.19.1-alpine AS node-builder

WORKDIR /app

# Criar usuário não-root para build
RUN addgroup -g 1001 nodegroup \
    && adduser -u 1001 -G nodegroup -s /bin/sh -D nodeuser

# Copiar arquivos package e definir permissões seguras
COPY src/package*.json ./
RUN chown nodeuser:nodegroup package*.json \
    && chmod 644 package*.json

USER nodeuser
RUN npm ci --only=production --no-audit --no-fund --ignore-scripts

# Voltar para root para copiar arquivos e definir permissões com segurança
USER root

# Copiar arquivos fonte sem ownership de usuário (inicialmente pertencentes ao root)
COPY src/resources ./resources
COPY src/vite.config.ts ./
COPY src/tsconfig.json ./
COPY src/tailwind.config.js ./
COPY src/.prettierrc ./
COPY src/eslint.config.js ./

# Segurança: Definir ownership adequado e remover permissões de escrita
RUN chown -R nodeuser:nodegroup . \
    && find . -type f -exec chmod 644 {} \; \
    && find . -type d -exec chmod 755 {} \;

# Voltar para nodeuser para o build
USER nodeuser

RUN npm run build

# Stage 2: Base PHP + Nginx
FROM php:8.2.15-fpm-alpine AS php-base

# Segurança: Usar versões específicas e instalar atualizações de segurança
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

# Criar usuário não-root para a aplicação
RUN addgroup -g 1000 appgroup \
    && adduser -u 1000 -G appgroup -s /bin/sh -D appuser

# Instalar extensões PHP
RUN docker-php-ext-install pdo pdo_pgsql

# Instalar Composer com versão fixa e permissões seguras
COPY --from=composer:2.6.6 /usr/bin/composer /usr/bin/composer
RUN chmod 755 /usr/bin/composer \
    && chown root:root /usr/bin/composer

# Criar diretórios necessários com permissões adequadas
RUN mkdir -p /var/log/nginx /var/log/supervisor /run/nginx /var/lib/nginx/tmp /var/cache/nginx \
    && chown -R appuser:appgroup /var/log/nginx /var/log/supervisor /run/nginx /var/lib/nginx /var/cache/nginx \
    && chmod 755 /var/log/nginx /var/log/supervisor /run/nginx /var/lib/nginx /var/cache/nginx

# Configurar Nginx
COPY docker/production/nginx.conf /etc/nginx/nginx.conf
RUN chmod 644 /etc/nginx/nginx.conf \
    && chown root:root /etc/nginx/nginx.conf

# Configurar Supervisor
COPY docker/production/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN chmod 644 /etc/supervisor/conf.d/supervisord.conf \
    && chown root:root /etc/supervisor/conf.d/supervisord.conf

# Stage 3: Aplicação
FROM php-base AS app

WORKDIR /var/www/html

# Segurança: Copiar arquivos da aplicação sem ownership de usuário (inicialmente pertencentes ao root para segurança)
COPY src/app ./app
COPY src/bootstrap ./bootstrap
COPY src/config ./config
COPY src/database ./database
COPY src/public ./public
COPY src/resources ./resources
COPY src/routes ./routes
COPY src/storage ./storage
COPY src/artisan ./artisan
COPY src/composer.json ./composer.json
COPY src/composer.lock ./composer.lock

# Segurança: Definir ownership e permissões adequadas para todos os arquivos da aplicação
RUN find . -type f -exec chmod 644 {} \; \
    && find . -type d -exec chmod 755 {} \; \
    && chmod +x artisan

# Segurança: Definir ownership para arquivos que não precisam de acesso de escrita (manter como root)
RUN chown -R root:root ./app ./config ./database ./public ./resources ./routes ./composer.json ./composer.lock ./artisan

# Segurança: Definir ownership e permissões adequadas para o diretório bootstrap
RUN chown -R root:root ./bootstrap \
    && find ./bootstrap -name "*.php" -exec chmod 644 {} \; \
    && mkdir -p ./bootstrap/cache \
    && chown -R appuser:appgroup ./bootstrap/cache \
    && chmod 755 ./bootstrap/cache

# Segurança: Definir ownership e permissões adequadas para o diretório storage (precisa de acesso de escrita)
RUN chown -R appuser:appgroup ./storage \
    && find ./storage -type d -exec chmod 755 {} \; \
    && find ./storage -type f -exec chmod 644 {} \;

# Alternar para usuário não-root para instalação via composer
USER appuser

# Instalar dependências PHP (produção) como usuário não-root
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --no-plugins

# Voltar para root temporariamente para configuração final
USER root

# Copiar assets compilados do stage anterior sem ownership de usuário
COPY --from=node-builder /app/public/build ./public/build

# Segurança: Definir permissões somente leitura para assets compilados (pertencentes ao root)
RUN chown -R root:root ./public/build \
    && find ./public/build -type f -exec chmod 644 {} \; \
    && find ./public/build -type d -exec chmod 755 {} \;

# Segurança: Configuração final de permissões - apenas o necessário deve ter permissão de escrita
RUN chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R appuser:appgroup /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 644 /var/www/html/public \
    && chmod 755 /var/www/html/public \
    && chown -R root:root /var/www/html/public

# Segurança: Auditoria final - garantir que não existam permissões de escrita não autorizadas
RUN find /var/www/html -type f -perm /022 ! -path "*/storage/*" ! -path "*/bootstrap/cache/*" -exec chmod 644 {} \; \
    && find /var/www/html -type d -perm /022 ! -path "*/storage*" ! -path "*/bootstrap/cache*" -exec chmod 755 {} \; \
    && find /var/www/html -user appuser -type f ! -path "*/storage/*" ! -path "*/bootstrap/cache/*" -exec chown root:root {} \; \
    && find /var/www/html -user appuser -type d ! -path "*/storage*" ! -path "*/bootstrap/cache*" -exec chown root:root {} \;

# Script de inicialização
COPY docker/production/entrypoint.sh /entrypoint.sh
RUN chmod 755 /entrypoint.sh \
    && chown root:root /entrypoint.sh

# Verificação de saúde
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:8080/health || exit 1

# Segurança: Remover pacotes desnecessários e limpar cache
RUN apk del --no-cache git curl \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/* \
    && rm -rf /root/.composer /root/.npm

# Segurança: Alternar para usuário não-root para execução
USER appuser

# Expor porta
EXPOSE 8080

# Comando de inicialização
CMD ["/entrypoint.sh"]
