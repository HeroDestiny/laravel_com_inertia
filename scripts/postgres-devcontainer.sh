#!/bin/bash

# Script simplificado para PostgreSQL no devcontainer
echo "🐘 Iniciando PostgreSQL para desenvolvimento..."

# Para PostgreSQL não estar rodando, tentar iniciar
if ! docker ps | grep -q postgres-dev; then
    echo "Iniciando container PostgreSQL..."
    docker run --name postgres-dev \
        -e POSTGRES_DB=laravel_inertia \
        -e POSTGRES_USER=laravel_user \
        -e POSTGRES_PASSWORD=laravel_password \
        -p 5432:5432 \
        -d postgres:16-alpine

    # Aguardar PostgreSQL inicializar
    echo "⏳ Aguardando PostgreSQL inicializar..."
    sleep 8
    
    # Verificar se está funcionando
    if docker ps | grep -q postgres-dev; then
        echo "✅ PostgreSQL iniciado com sucesso!"
        echo ""
        echo "📊 Configurações de conexão:"
        echo "   Host: 127.0.0.1"
        echo "   Porta: 5432"
        echo "   Database: laravel_inertia"
        echo "   Usuário: laravel_user"
        echo "   Senha: laravel_password"
        echo ""
        echo "🚀 Execute agora: php artisan migrate"
    else
        echo "❌ Erro ao iniciar PostgreSQL"
        docker logs postgres-dev
    fi
else
    echo "✅ PostgreSQL já está rodando!"
fi
