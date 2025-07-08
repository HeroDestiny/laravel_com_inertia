#!/bin/sh

# Script de inicialização para produção com PostgreSQL
set -e

echo "🚀 Iniciando aplicação Laravel com PostgreSQL..."

# Aguardar PostgreSQL estar disponível
echo "⏳ Aguardando PostgreSQL..."
until pg_isready -h $DB_HOST -p $DB_PORT -U $DB_USERNAME; do
  echo "PostgreSQL não está pronto - aguardando..."
  sleep 2
done

echo "✅ PostgreSQL está pronto!"

# Executar migrações
echo "🗄️ Executando migrações..."
php artisan migrate --force

# Limpar e recriar caches
echo "🧹 Recriando caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Otimizar autoloader
echo "⚡ Otimizando autoloader..."
composer dump-autoload --optimize

echo "✅ Aplicação iniciada com sucesso!"

# Iniciar supervisord
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
