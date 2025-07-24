# Dockerfile customizado para Laravel + PostgreSQL
FROM mcr.microsoft.com/devcontainers/php:1-8.2-bullseye

# Instalar dependências PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    postgresql-client \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP PostgreSQL
RUN docker-php-ext-install pdo_pgsql pgsql

# Instalar Composer globalmente (se não estiver)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar usuário para desenvolvimento
USER vscode

# Configurar Git safe directory
RUN git config --global --add safe.directory /workspaces/laravel_com_inertia
