#!/bin/bash

# 🧪 Script para testar configuração de cobertura SonarQube
# Usage: ./test-sonar-coverage.sh

set -e

echo "🧪 Testando configuração de cobertura para SonarQube..."

# Verificar se estamos no diretório correto
if [ ! -f "phpunit.xml" ]; then
    echo "❌ Execute este script no diretório src/"
    exit 1
fi

echo "📁 Verificando estrutura de diretórios..."

# Criar diretório de cobertura se não existir
mkdir -p coverage

echo "🐘 Executando testes PHP com cobertura..."

# Executar testes PHP com cobertura
vendor/bin/phpunit --coverage-clover=coverage/clover.xml --log-junit=coverage/junit.xml

echo "📊 Verificando arquivos de cobertura gerados..."

# Verificar se os arquivos foram gerados
if [ -f "coverage/clover.xml" ]; then
    echo "✅ clover.xml gerado com sucesso"
    # Mostrar algumas estatísticas básicas
    lines=$(grep -o 'lines covered="[0-9]*"' coverage/clover.xml | head -1 | grep -o '[0-9]*')
    total_lines=$(grep -o 'lines total="[0-9]*"' coverage/clover.xml | head -1 | grep -o '[0-9]*')
    if [ ! -z "$lines" ] && [ ! -z "$total_lines" ] && [ "$total_lines" -gt 0 ]; then
        coverage=$((lines * 100 / total_lines))
        echo "📈 Cobertura PHP: $coverage% ($lines/$total_lines linhas)"
    fi
else
    echo "❌ clover.xml não foi gerado"
fi

if [ -f "coverage/junit.xml" ]; then
    echo "✅ junit.xml gerado com sucesso"
else
    echo "❌ junit.xml não foi gerado"
fi

echo "🎯 Testando cobertura JavaScript/TypeScript..."

# Verificar se vitest está configurado
if [ -f "vitest.config.ts" ]; then
    echo "📦 Executando testes JS com cobertura..."
    npm run test:js:coverage || echo "⚠️  Testes JS falharam ou não configurados"
    
    if [ -f "coverage/lcov.info" ]; then
        echo "✅ lcov.info gerado com sucesso"
    else
        echo "⚠️  lcov.info não foi gerado (normal se não há testes JS)"
    fi
else
    echo "⚠️  vitest.config.ts não encontrado, cobertura JS não configurada"
fi

echo "🔧 Verificando configuração SonarQube..."

# Verificar sonar-project.properties
if [ -f "../sonar-project.properties" ]; then
    echo "✅ sonar-project.properties encontrado"
    
    # Verificar configurações essenciais
    if grep -q "sonar.php.coverage.reportPaths" ../sonar-project.properties; then
        echo "✅ Caminho de cobertura PHP configurado"
    else
        echo "❌ Caminho de cobertura PHP não configurado"
    fi
    
    if grep -q "sonar.php.tests.reportPath" ../sonar-project.properties; then
        echo "✅ Caminho de testes PHP configurado"
    else
        echo "❌ Caminho de testes PHP não configurado"
    fi
else
    echo "❌ sonar-project.properties não encontrado"
fi

echo "📋 Resumo dos arquivos de cobertura:"
ls -la coverage/ 2>/dev/null || echo "❌ Diretório coverage/ não existe"

echo ""
echo "🎉 Teste de configuração concluído!"
echo ""
echo "📚 Próximos passos:"
echo "1. Configure os secrets no GitHub (SONAR_TOKEN, SONAR_HOST_URL)"
echo "2. Execute a pipeline para ver a análise completa"
echo "3. Acesse o SonarQube/SonarCloud para visualizar os resultados"
