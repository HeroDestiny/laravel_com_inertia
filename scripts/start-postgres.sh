#!/bin/bash

# Script para iniciar PostgreSQL com Docker Compose
echo "Iniciando PostgreSQL com Docker..."

# Verifica se o Docker está rodando
if ! docker info > /dev/null 2>&1; then
    echo "Docker não está rodando. Inicie o Docker Desktop primeiro."
    exit 1
fi

# Inicia os containers
docker-compose -f docker-compose.postgres.yml up -d

# Aguarda o PostgreSQL inicializar
echo "Aguardando PostgreSQL inicializar..."
sleep 10

# Verifica se os containers estão rodando
if docker-compose -f docker-compose.postgres.yml ps | grep -q "Up"; then
    echo "PostgreSQL iniciado com sucesso!"
    echo ""
    echo "Informações de conexão:"
    echo "   Host: localhost"
    echo "   Porta: 5432"
    echo "   Database: laravel_inertia"
    echo "   Usuário: laravel_user"
    echo "   Senha: laravel_password"
    echo ""
    echo "pgAdmin disponível em: http://localhost:8080"
    echo "   Email: admin@laravel.com"
    echo "   Senha: admin123"
else
    echo "Erro ao iniciar PostgreSQL"
    docker-compose -f docker-compose.postgres.yml logs
fi
