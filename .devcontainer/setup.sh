#!/bin/bash

# Script de inicialização do DevContainer Laravel + PostgreSQL
# Este script é executado automaticamente quando o container inicia

echo "🔧 Iniciando configuração do ambiente Laravel + PostgreSQL..."

# Aguardar PostgreSQL estar pronto
echo "⏳ Aguardando PostgreSQL ficar disponível..."
timeout 60 bash -c 'until pg_isready -h postgres -p 5432; do echo "Aguardando PostgreSQL..."; sleep 2; done'

if [ $? -eq 0 ]; then
    echo "✅ PostgreSQL está pronto!"
    
    # Executar migrações se necessário
    cd /workspaces/laravel_com_inertia/src
    
    # Verificar se as migrações já foram executadas
    if ! php artisan migrate:status --ansi 2>/dev/null | grep -q "Ran"; then
        echo "🔄 Executando migrações iniciais..."
        php artisan migrate --force
        php artisan db:seed --force 2>/dev/null || echo "ℹ️  Nenhum seeder disponível"
    else
        echo "ℹ️  Migrações já executadas"
    fi
    
    # Limpar e gerar cache de configuração
    php artisan config:clear
    php artisan config:cache
    
    echo "🎉 Ambiente Laravel + PostgreSQL pronto para uso!"
    echo "📋 Comandos disponíveis:"
    echo "   - Laravel: http://localhost:8000"
    echo "   - Vite: http://localhost:5173"
    echo "   - Executar testes: php artisan test"
    echo "   - Migrações: php artisan migrate"
else
    echo "❌ Erro: PostgreSQL não ficou disponível em 60 segundos"
    exit 1
fi
