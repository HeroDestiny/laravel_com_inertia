# DevContainer Laravel + PostgreSQL - Guia de Troubleshooting

## ✅ Verificações Rápidas

### 1. Verificar se PostgreSQL está rodando

```bash
pg_isready -h postgres -p 5432
```

### 2. Testar conexão Laravel

```bash
php artisan migrate:status
```

### 3. Verificar extensões PHP

```bash
php -m | grep pdo
php -m | grep pgsql
```

### 4. Verificar logs do PostgreSQL

```bash
docker logs laravel_com_inertia-postgres-1
```

## 🔧 Comandos de Recuperação

### Rebuildar o devcontainer

1. `Ctrl+Shift+P` → "Dev Containers: Rebuild Container"
2. Aguardar a reconstrução completa

### Resetar banco de dados

```bash
php artisan migrate:fresh --seed
```

### Limpar caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## 🐛 Problemas Comuns

### PostgreSQL não conecta

-   Verificar se container Docker está rodando
-   Aguardar alguns segundos após inicialização
-   Verificar variáveis de ambiente no .env

### Migrações falham

-   Verificar se PostgreSQL está pronto
-   Executar `php artisan migrate:status` primeiro
-   Usar `php artisan migrate:fresh` se necessário

### Performance lenta

-   Verificar se volumes Docker estão otimizados
-   Limpar caches com comandos acima
