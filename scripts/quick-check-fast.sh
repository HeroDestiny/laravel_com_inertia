#!/bin/bash

# Quick Checks - Versão Rápida para Desenvolvimento
# Apenas as verificações essenciais (sem build)

set -e

echo "🚀 Quick Checks - Modo Rápido"
echo "==============================="

cd "$(dirname "$0")/../src"

echo ""
echo "1. 🎨 Estilo de código (Pint)..."
vendor/bin/pint --test

echo ""
echo "2. 🔍 Análise estática (PHPStan)..."
vendor/bin/phpstan analyse --memory-limit=512M --no-progress --error-format=table

echo ""
echo "3. 🧪 Testes unitários..."
vendor/bin/phpunit --testsuite=Unit

echo ""
echo "✅ Checks rápidos concluídos!"
echo "⏱️  Para checks completos use: ./quick-check-local.sh"
