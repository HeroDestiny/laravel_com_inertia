#!/bin/bash

# Script simplificado para gerenciar Docker - DevContainer Environment
# Foco em operações essenciais para desenvolvimento

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"

function show_help() {
    echo "Gerenciador Docker - Laravel DevContainer"
    echo ""
    echo "Uso: $0 <comando>"
    echo ""
    echo "Comandos disponíveis:"
    echo "  status       - Mostra status dos containers"
    echo "  postgres     - Inicia PostgreSQL externo (se necessário)"
    echo "  clean        - Remove volumes e containers órfãos"
    echo "  logs         - Mostra logs do PostgreSQL"
    echo ""
    echo "Nota: O DevContainer já gerencia o ambiente principal."
    echo "Use as tasks do VS Code para desenvolvimento normal."
}

function start_postgres() {
    echo "Iniciando PostgreSQL externo (se o DevContainer não funcionar)..."
    cd "$PROJECT_ROOT"
    
    if [ -f "docker/postgres/docker-compose.yml" ]; then
        docker-compose -f docker/postgres/docker-compose.yml up -d
        echo ""
        echo "PostgreSQL externo iniciado!"
        echo "   Banco: localhost:5432"
        echo "   pgAdmin: http://localhost:8080"
    else
        echo "ERRO: Arquivo docker/postgres/docker-compose.yml não encontrado"
    fi
}

function clean_docker() {
    echo "Limpando containers e volumes órfãos..."
    docker container prune -f
    docker volume prune -f
    docker network prune -f
    echo "Limpeza concluída!"
}

function show_status() {
    echo "Status dos containers Docker:"
    echo ""
    docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
}

function show_logs() {
    echo "Logs do PostgreSQL:"
    docker logs postgres-dev 2>/dev/null || echo "Container postgres-dev não encontrado"
}

# Comando principal
case "${1:-}" in
    postgres)
        start_postgres
        ;;
    clean)
        clean_docker
        ;;
    status)
        show_status
        ;;
    logs)
        show_logs
        ;;
    help|--help|-h)
        show_help
        ;;
    *)
        echo "ERRO: Comando inválido: ${1:-}"
        echo ""
        show_help
        exit 1
        ;;
esac
