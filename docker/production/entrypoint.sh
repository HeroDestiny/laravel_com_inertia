#!/bin/sh

# Script de inicialização para produção com PostgreSQL
set -e

echo "🚀 Iniciando aplicação Laravel com PostgreSQL..."

# Ensure proper ownership of application files
echo "� Configurando permissões..."
chown -R appuser:appgroup /var/www/html /var/log/nginx /var/log/supervisor /run/nginx /var/lib/nginx
chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Aguardar PostgreSQL estar disponível
echo "⏳ Aguardando PostgreSQL..."
until pg_isready -h $DB_HOST -p $DB_PORT -U $DB_USERNAME; do
  echo "PostgreSQL não está pronto - aguardando..."
  sleep 2
done

echo "✅ PostgreSQL está pronto!"

# Executar migrações como appuser
echo "🗄️ Executando migrações..."
su-exec appuser php artisan migrate --force

# Limpar e recriar caches como appuser
echo "🧹 Recriando caches..."
su-exec appuser php artisan config:cache
su-exec appuser php artisan route:cache
su-exec appuser php artisan view:cache

# Otimizar autoloader como appuser
echo "⚡ Otimizando autoloader..."
su-exec appuser composer dump-autoload --optimize

echo "✅ Aplicação iniciada com sucesso!"

# Iniciar supervisord (que irá rodar os serviços como appuser)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
