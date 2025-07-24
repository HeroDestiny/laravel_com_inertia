#!/bin/sh

# Script de inicialização para produção com PostgreSQL
set -e

echo "🚀 Iniciando aplicação Laravel com PostgreSQL..."

# Note: Running as appuser, no need to change ownership
echo "✅ Executando como usuário não-root: $(whoami)"

# Aguardar PostgreSQL estar disponível  
echo "⏳ Aguardando PostgreSQL..."
until pg_isready -h $DB_HOST -p $DB_PORT -U $DB_USERNAME; do
  echo "PostgreSQL não está pronto - aguardando..."
  sleep 2
done

echo "✅ PostgreSQL está pronto!"

# Executar migrações (já rodando como appuser)
echo "🗄️ Executando migrações..."
php artisan migrate --force

# Limpar e recriar caches (já rodando como appuser)
echo "🧹 Recriando caches..."
php artisan config:cache
php artisan route:cache  
php artisan view:cache

# Otimizar autoloader (já rodando como appuser)
echo "⚡ Otimizando autoloader..."
composer dump-autoload --optimize

echo "✅ Aplicação iniciada com sucesso!"

# Iniciar supervisord como appuser
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
