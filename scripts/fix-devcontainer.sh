#!/bin/bash

# Script de correção de permissões para DevContainer
# Execute este script se o devcontainer falhar na instalação inicial

echo "Corrigindo permissões do DevContainer..."

# Garantir que o usuário vscode seja o dono dos arquivos
sudo chown -R vscode:vscode /workspaces/laravel_com_inertia

# Navegar para o diretório do projeto
cd /workspaces/laravel_com_inertia/src

# Limpar diretórios problemáticos
echo "Limpando caches e dependências..."
rm -rf vendor node_modules
composer clear-cache
npm cache clean --force

# Instalar dependências do Composer
echo "Instalando dependências do Composer..."
composer install --no-dev --optimize-autoloader

# Instalar dependências do Node.js
echo "Instalando dependências do Node.js..."
npm ci

# Configurar Laravel
echo "Configurando Laravel..."
if [ ! -f .env ]; then
    cp .env.example .env
fi

php artisan key:generate
php artisan storage:link
php artisan config:cache

# Instalar PlantUML (opcional)
echo "Instalando PlantUML..."
python3 -m pip install plantuml

echo "Setup manual concluído!"
echo ""
echo "Próximos passos:"
echo "1. Configure PostgreSQL usando os scripts em ./scripts/"
echo "2. Execute 'php artisan migrate' se necessário"
echo "3. Inicie o desenvolvimento com 'php artisan serve' e 'npm run dev'"
