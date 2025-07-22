# Database Migrations

Documentação sobre migrações de banco de dados e mudanças estruturais.

## Documentos Disponíveis

-   **[PostgreSQL Migration](./POSTGRESQL_MIGRATION.md)** - Migração completa de SQLite para PostgreSQL

## Comandos Úteis

### Executar Migrações

```bash
# Executar todas as migrações pendentes
php artisan migrate

# Reset completo com seeds
php artisan migrate:fresh --seed

# Executar em produção (força)
php artisan migrate --force
```

### Gerenciar Migrações

```bash
# Verificar status das migrações
php artisan migrate:status

# Fazer rollback da última migração
php artisan migrate:rollback

# Fazer rollback de X migrações
php artisan migrate:rollback --step=3

# Reset completo (cuidado!)
php artisan migrate:reset
```

### Criar Novas Migrações

```bash
# Criar nova migração
php artisan make:migration create_posts_table

# Migração para modificar tabela existente
php artisan make:migration add_column_to_users_table --table=users

# Migração com modelo
php artisan make:model Post -m
```

## Estrutura do Banco

### Tabelas Principais

-   **users** - Usuários do sistema
-   **pacientes** - Dados dos pacientes
-   **migrations** - Controle de migrações
-   **failed_jobs** - Jobs falidos
-   **password_reset_tokens** - Tokens de reset de senha
-   **sessions** - Sessões de usuário

### Relacionamentos

```
users (1) -----> (N) pacientes
```

## Ambientes

### Desenvolvimento (SQLite)

```bash
# Configuração rápida
touch database/database.sqlite
php artisan migrate --seed
```

### Produção (PostgreSQL)

```bash
# Verificar conectividade
php scripts/test-postgres-connection.php

# Executar migrações
php artisan migrate --force
```

## Backup e Restore

### SQLite

```bash
# Backup
cp database/database.sqlite backup_$(date +%Y%m%d).sqlite

# Restore
cp backup_20241201.sqlite database/database.sqlite
```

### PostgreSQL

```bash
# Backup
pg_dump laravel_inertia > backup_$(date +%Y%m%d).sql

# Restore
psql laravel_inertia < backup_20241201.sql
```

## Troubleshooting

### Problemas Comuns

**Erro de conexão:**

```bash
# Verificar configuração
php artisan tinker
>>> DB::connection()->getPdo();
```

**Migração travada:**

```bash
# Reset do estado
php artisan migrate:reset
php artisan migrate --seed
```

**Rollback necessário:**

```bash
# Ver última migração
php artisan migrate:status

# Fazer rollback
php artisan migrate:rollback --step=1
```
