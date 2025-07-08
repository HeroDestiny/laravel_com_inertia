#!/bin/bash

# Script de inicialização do ambiente de desenvolvimento
# Este script configura o ambiente Laravel + Inertia + Vue rapidamente

set -e

echo "🚀 Configurando ambiente de desenvolvimento Laravel + Inertia + Vue..."

# Navegar para o diretório do projeto
cd /workspaces/laravel_com_inertia/src

# Verificar se o .env existe
if [ ! -f .env ]; then
    echo "📝 Criando arquivo .env..."
    cp .env.example .env
fi

# Verificar se a chave da aplicação está definida
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Gerando chave da aplicação..."
    php artisan key:generate
fi

# Limpar caches
echo "🧹 Limpando caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Verificar banco de dados PostgreSQL
echo "🐘 Verificando conexão com PostgreSQL..."
if php artisan tinker --execute="DB::connection()->getPdo();" 2>/dev/null; then
    echo "✅ PostgreSQL conectado com sucesso!"
else
    echo "❌ Erro: Não foi possível conectar ao PostgreSQL"
    echo "   Verifique se o PostgreSQL está rodando e as credenciais estão corretas"
    echo "   Configuração atual:"
    echo "   - Host: $(grep DB_HOST .env | cut -d= -f2)"
    echo "   - Port: $(grep DB_PORT .env | cut -d= -f2)"
    echo "   - Database: $(grep DB_DATABASE .env | cut -d= -f2)"
    echo "   - Username: $(grep DB_USERNAME .env | cut -d= -f2)"
    exit 1
fi

# Executar migrações
echo "🗄️ Executando migrações..."
php artisan migrate --force

# Link para storage
if [ ! -L public/storage ]; then
    echo "🔗 Criando link simbólico para storage..."
    php artisan storage:link
fi

# Instalar dependências Node.js se necessário
if [ ! -d node_modules ]; then
    echo "📦 Instalando dependências Node.js..."
    npm ci
fi

# Gerar caches para produção otimizada
echo "⚡ Otimizando aplicação..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verificar se tudo está funcionando
echo "🔍 Verificando configuração..."
php artisan about --only=environment

echo "✅ Ambiente configurado com sucesso!"
echo ""
echo "🎯 Próximos passos:"
echo "   • Execute 'npm run dev' para iniciar o servidor Vite"
echo "   • Execute 'php artisan serve' para iniciar o servidor Laravel"
echo "   • Ou use a tarefa 'Dev Environment: Start All' no VS Code"
echo ""
