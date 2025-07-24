#!/bin/sh

# Initial setup script that runs as root and then drops to appuser
set -e

echo "🔧 Executando configurações iniciais como root..."

# Ensure proper ownership of application files
chown -R appuser:appgroup /var/www/html /var/log/nginx /var/log/supervisor /run/nginx /var/lib/nginx

# Create necessary directories if they don't exist
mkdir -p /var/www/html/storage/logs /var/www/html/bootstrap/cache
chown -R appuser:appgroup /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

echo "✅ Configurações iniciais concluídas"

# Drop to appuser and execute the entrypoint
echo "👤 Mudando para usuário appuser..."
exec su-exec appuser /entrypoint.sh
