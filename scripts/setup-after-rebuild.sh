#!/bin/bash

# Script otimizado para setup após rebuild do DevContainer
# Versão 2025 - Focado no ambiente DevContainer com PostgreSQL integrado

set -e

echo "Setup pós-rebuild DevContainer - Laravel + Inertia..."
echo

cd /workspaces/laravel_com_inertia/src

# 1. Verificar se PostgreSQL está pronto (no DevContainer)
echo "Verificando PostgreSQL..."
if command -v pg_isready &> /dev/null; then
    max_attempts=15
    attempt=0
    
    while ! pg_isready -h postgres -p 5432 -U laravel_user &> /dev/null; do
        attempt=$((attempt + 1))
        if [ $attempt -ge $max_attempts ]; then
            echo "PostgreSQL DevContainer não detectado. Usando SQLite como fallback."
            break
        fi
        echo "   Aguardando PostgreSQL... ($attempt/$max_attempts)"
        sleep 2
    done
    
    if [ $attempt -lt $max_attempts ]; then
        echo "PostgreSQL DevContainer pronto!"
    fi
else
    echo "pg_isready não encontrado, assumindo SQLite"
fi

echo

# 2. Testar conectividade com script de diagnóstico (versão segura)
echo "Testando conectividade..."
if [ -f "../scripts/test-postgres-connection.php" ]; then
    # Script atualizado com melhorias de segurança:
    # - Uso de variáveis de ambiente para credenciais
    # - Prepared statements para prevenir SQL injection
    # - Sanitização de output e logs seguros
    php ../scripts/test-postgres-connection.php
else
    echo "Script de teste não encontrado, continuando..."
fi
echo

# 3. Configurar aplicação
echo "Configurando aplicação..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "   Arquivo .env criado"
fi

if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate
    echo "   Chave da aplicação gerada"
fi

# 4. Limpar caches
echo "Limpando caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo "   Caches limpos"

# 5. Executar migrações
echo "Executando migrações..."
php artisan migrate:fresh --seed --force
echo "   Banco configurado"

# 6. Gerar storage link
echo "Criando link de storage..."
php artisan storage:link
echo "   Storage link criado"

# 7. Verificar UML system
echo "Verificando sistema UML..."
if [ -f "scripts/check_uml_system.py" ]; then
    python3 scripts/check_uml_system.py
else
    echo "   Script UML não encontrado"
fi

# 8. Verificar frontend
echo "Verificando frontend..."
if [ ! -d "node_modules" ]; then
    echo "Instalando dependências Node.js..."
    npm ci
fi

echo
echo "Setup pós-rebuild concluído!"
echo
echo "Próximos passos:"
echo "   1. Execute as tasks do VS Code:"
echo "      - 'Laravel: Serve' para iniciar o servidor"
echo "      - 'Vite: Dev Server' para assets"
echo "   2. Acesse: http://localhost:8000"
echo "   3. Para UML: npm run docs:uml"
echo
