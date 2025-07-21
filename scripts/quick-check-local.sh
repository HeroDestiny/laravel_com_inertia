#!/bin/bash

# Quick Checks - Versão Local
# Simula o workflow do GitHub Actions localmente

set -e  # Para na primeira falha

echo "Iniciando Quick Checks locais..."
echo "=================================="

cd "$(dirname "$0")/../src"

echo ""
echo "1. Instalando dependências..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts
npm ci

echo ""
echo "2. Verificando estilo de código (Pint)..."
vendor/bin/pint --test

echo ""
echo "3. Análise estática (PHPStan)..."
vendor/bin/phpstan analyse --memory-limit=1G --no-progress

echo ""
echo "4. Verificações Frontend..."
npm run lint

echo ""
echo "5. Preparando ambiente de testes..."
cp .env.example .env 2>/dev/null || true
php artisan key:generate

echo ""
echo "6. Executando testes..."
php artisan test

echo ""
echo "7. Build de produção..."
npm run build

echo ""
echo "Todos os quick checks passaram!"
echo "=================================="
