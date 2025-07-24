#!/bin/bash

# Quick Checks - Versão Local Otimizada
# Simula o workflow do GitHub Actions localmente (sem reinstalar dependências)

set -e  # Para na primeira falha

echo "Iniciando Quick Checks locais..."
echo "=================================="

cd "$(dirname "$0")/../src"

# Verificar se dependências estão instaladas
echo ""
echo "🔍 Verificando dependências..."
if [ ! -d "vendor" ] || [ ! -f "vendor/bin/pint" ]; then
    echo "⚠️  Dependências PHP não encontradas. Instalando..."
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts
else
    echo "✅ Dependências PHP OK"
fi

if [ ! -d "node_modules" ] || [ ! -f "node_modules/.bin/eslint" ]; then
    echo "⚠️  Dependências Node.js não encontradas. Instalando..."
    npm ci
else
    echo "✅ Dependências Node.js OK"
fi

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
echo "5. Verificando configuração do ambiente..."
if [ ! -f ".env" ]; then
    echo "⚠️  Arquivo .env não encontrado. Criando..."
    cp .env.example .env
    php artisan key:generate
else
    echo "✅ Arquivo .env existe"
fi

echo ""
echo "6. Executando testes..."
vendor/bin/phpunit

echo ""
echo "7. Build de produção..."
npm run build

echo ""
echo "✅ Todos os quick checks passaram!"
echo "=================================="
echo "🚀 Tempo economizado: dependências não reinstaladas"
