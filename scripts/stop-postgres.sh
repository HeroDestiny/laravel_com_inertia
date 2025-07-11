#!/bin/bash

# Script para parar PostgreSQL
echo "Parando PostgreSQL..."

docker-compose -f docker-compose.postgres.yml down

echo "PostgreSQL parado com sucesso!"
