#!/bin/bash

# Script para gerenciar ambientes Docker do Laravel

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"

function show_help() {
    echo "🐳 Gerenciador Docker - Laravel com Inertia"
    echo ""
    echo "Uso: $0 <comando>"
    echo ""
    echo "Comandos disponíveis:"
    echo "  postgres     - Inicia PostgreSQL standalone (+ pgAdmin)"
    echo "  sail         - Inicia Laravel Sail (desenvolvimento completo)"
    echo "  production   - Build/deploy para produção"
    echo "  stop         - Para todos os containers"
    echo "  clean        - Remove volumes e containers órfãos"
    echo "  status       - Mostra status dos containers"
    echo ""
    echo "Exemplos:"
    echo "  $0 postgres    # Apenas banco PostgreSQL"
    echo "  $0 sail        # Ambiente completo de desenvolvimento"
    echo "  $0 stop        # Para tudo"
}

function start_postgres() {
    echo "🚀 Iniciando PostgreSQL standalone..."
    cd "$PROJECT_ROOT"
    docker-compose -f docker/postgres/docker-compose.yml up -d
    echo ""
    echo "✅ PostgreSQL iniciado!"
    echo "   📊 Banco: localhost:5432"
    echo "   🌐 pgAdmin: http://localhost:8080"
    echo "      📧 Email: admin@laravel.com"
    echo "      🔑 Senha: admin123"
}

function start_sail() {
    echo "🚀 Iniciando Laravel Sail..."
    cd "$PROJECT_ROOT/src"
    
    if [ ! -f "vendor/bin/sail" ]; then
        echo "⚠️  Laravel Sail não encontrado. Instalando dependências..."
        composer install
    fi
    
    ./vendor/bin/sail up -d
    echo ""
    echo "✅ Laravel Sail iniciado!"
    echo "   🌐 App: http://localhost"
    echo "   ⚡ Vite: http://localhost:5173"
    echo "   📊 PostgreSQL: localhost:5432"
}

function start_production() {
    echo "🚀 Iniciando ambiente de produção..."
    cd "$PROJECT_ROOT"
    
    if [ ! -f ".env" ]; then
        echo "⚠️  Arquivo .env não encontrado para produção!"
        echo "   Crie um .env com as variáveis de produção"
        exit 1
    fi
    
    docker-compose -f docker/production/docker-compose.yml up --build -d
    echo ""
    echo "✅ Ambiente de produção iniciado!"
    echo "   🌐 App: http://localhost"
}

function stop_all() {
    echo "🛑 Parando todos os containers..."
    cd "$PROJECT_ROOT"
    
    # Para PostgreSQL standalone
    docker-compose -f docker/postgres/docker-compose.yml down 2>/dev/null || true
    
    # Para Laravel Sail
    cd src 2>/dev/null && ./vendor/bin/sail down 2>/dev/null || true
    
    # Para produção
    cd "$PROJECT_ROOT"
    docker-compose -f docker/production/docker-compose.yml down 2>/dev/null || true
    
    echo "✅ Todos os containers parados!"
}

function clean_docker() {
    echo "🧹 Limpando containers e volumes órfãos..."
    docker container prune -f
    docker volume prune -f
    docker network prune -f
    echo "✅ Limpeza concluída!"
}

function show_status() {
    echo "📊 Status dos containers Docker:"
    echo ""
    docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
}

# Comando principal
case "${1:-}" in
    postgres)
        start_postgres
        ;;
    sail)
        start_sail
        ;;
    production)
        start_production
        ;;
    stop)
        stop_all
        ;;
    clean)
        clean_docker
        ;;
    status)
        show_status
        ;;
    help|--help|-h)
        show_help
        ;;
    *)
        echo "❌ Comando inválido: ${1:-}"
        echo ""
        show_help
        exit 1
        ;;
esac
