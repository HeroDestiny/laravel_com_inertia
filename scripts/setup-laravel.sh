#!/bin/bash

# Script para configurar o Laravel com PostgreSQL
echo "Configurando Laravel para PostgreSQL..."

cd src

# Copia o .env.example se .env não existir
if [ ! -f .env ]; then
    cp .env.example .env
    echo "Arquivo .env criado"
fi

# Gera a chave da aplicação
php artisan key:generate

# Executa as migrações
echo "Executando migrações..."
php artisan migrate

# Executa os seeders (se existirem)
echo "Executando seeders..."
php artisan db:seed

echo "Laravel configurado com PostgreSQL!"
