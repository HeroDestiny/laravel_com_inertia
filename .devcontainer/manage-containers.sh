#!/bin/bash

# Script para gerenciar containers do DevContainer Laravel Inertia
# Evita a criação de containers duplicados

COMPOSE_FILE=".devcontainer/docker-compose.postgres.yml"
PROJECT_NAME="devcontainer"

case "$1" in
    "start")
        echo "🚀 Iniciando containers Laravel Inertia..."
        docker-compose -f $COMPOSE_FILE -p $PROJECT_NAME up -d
        ;;
    "stop")
        echo "⏹️  Parando containers Laravel Inertia..."
        docker-compose -f $COMPOSE_FILE -p $PROJECT_NAME stop
        ;;
    "restart")
        echo "🔄 Reiniciando containers Laravel Inertia..."
        docker-compose -f $COMPOSE_FILE -p $PROJECT_NAME restart
        ;;
    "down")
        echo "🗑️  Removendo containers Laravel Inertia..."
        docker-compose -f $COMPOSE_FILE -p $PROJECT_NAME down
        ;;
    "clean")
        echo "🧹 Limpando containers antigos e orfãos..."
        # Remove containers específicos antigos se existirem
        docker rm -f laravel-inertia-app laravel-inertia-postgres 2>/dev/null || true
        # Remove containers orfãos
        docker container prune -f
        docker system prune -f
        echo "✅ Limpeza concluída!"
        ;;
    "clean-all")
        echo "🧹 Limpeza completa (containers + volumes)..."
        docker-compose -f $COMPOSE_FILE -p $PROJECT_NAME down -v
        docker rm -f laravel-inertia-app laravel-inertia-postgres 2>/dev/null || true
        docker volume prune -f
        docker system prune -f
        echo "✅ Limpeza completa concluída!"
        ;;
    "status")
        echo "📊 Status dos containers Laravel Inertia:"
        docker-compose -f $COMPOSE_FILE -p $PROJECT_NAME ps
        echo ""
        echo "📋 Containers relacionados:"
        docker ps -a --filter "name=devcontainer" --format "table {{.Names}}\t{{.Image}}\t{{.Status}}"
        ;;
    "logs")
        echo "📝 Logs dos containers:"
        docker-compose -f $COMPOSE_FILE -p $PROJECT_NAME logs -f
        ;;
    *)
        echo "Uso: $0 {start|stop|restart|down|clean|clean-all|status|logs}"
        echo ""
        echo "Comandos disponíveis:"
        echo "  start     - Inicia os containers"
        echo "  stop      - Para os containers (mantém volumes)"
        echo "  restart   - Reinicia os containers"
        echo "  down      - Remove os containers"
        echo "  clean     - Remove containers orfãos e antigos"
        echo "  clean-all - Remove containers + volumes"
        echo "  status    - Mostra status dos containers"
        echo "  logs      - Mostra logs dos containers"
        exit 1
        ;;
esac
