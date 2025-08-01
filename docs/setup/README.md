# Guia de Instalação e Configuração

Guia completo para instalação e configuração do projeto Laravel com Inertia.js.

## Pré-requisitos

### Requisitos de Sistema

-   **Sistema Operacional**: Linux, macOS, ou Windows com WSL2
-   **PHP**: 8.2 ou superior
-   **Node.js**: 18.0 ou superior
-   **Composer**: 2.0 ou superior
-   **Git**: 2.0 ou superior

### Bancos de Dados Suportados

-   **PostgreSQL**: 13.0 ou superior (recomendado)
-   **SQLite**: 3.8 ou superior (desenvolvimento)
-   **MySQL**: 8.0 ou superior (opcional)

### Ferramentas Opcionais

-   **Docker**: Para ambiente containerizado
-   **Redis**: Para cache e sessões
-   **Mailhog**: Para testes de email

## Métodos de Instalação

### 1. DevContainer (Recomendado)

Esta é a forma mais rápida e consistente de configurar o ambiente.

#### Pré-requisitos DevContainer

-   VS Code
-   Docker Desktop
-   Extensão Dev Containers

#### Passos

```bash
# 1. Clone o repositório
git clone https://github.com/HeroDestiny/laravel_com_inertia.git
cd laravel_com_inertia

# 2. Abra no VS Code
code .

# 3. Quando solicitado, clique em "Reopen in Container"
# O VS Code irá automaticamente:
# - Construir o container
# - Instalar dependências PHP e Node.js
# - Configurar PostgreSQL
# - Configurar Mailhog
```

#### Verificação DevContainer

```bash
# Verificar versões
php --version      # Deve mostrar PHP 8.2+
node --version     # Deve mostrar Node 18+
composer --version # Deve mostrar Composer 2+

# Verificar serviços
docker ps          # PostgreSQL e Mailhog devem estar rodando
```

### 2. Instalação Manual

Para desenvolvimento sem Docker ou em ambiente de produção.

#### 1. Clone e Navegue

```bash
git clone https://github.com/HeroDestiny/laravel_com_inertia.git
cd laravel_com_inertia/src
```

#### 2. Instale Dependências PHP

```bash
# Verificar versão do PHP
php --version

# Instalar dependências do Composer
composer install

# Para produção, use:
composer install --optimize-autoloader --no-dev
```

#### 3. Instale Dependências Node.js

```bash
# Verificar versão do Node
node --version
npm --version

# Instalar dependências
npm install

# Para produção, use:
npm ci --production
```

#### 4. Configuração de Ambiente

```bash
# Copiar arquivo de configuração
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate
```

#### 5. Configurar Banco de Dados

##### PostgreSQL (Recomendado)

```bash
# Instalar PostgreSQL (Ubuntu/Debian)
sudo apt update
sudo apt install postgresql postgresql-contrib

# Criar usuário e banco
sudo -u postgres psql
```

```sql
-- No prompt do PostgreSQL
CREATE USER laravel_user WITH PASSWORD 'sua_senha_segura';
CREATE DATABASE laravel_inertia OWNER laravel_user;
GRANT ALL PRIVILEGES ON DATABASE laravel_inertia TO laravel_user;
\q
```

##### SQLite (Desenvolvimento)

```bash
# Criar arquivo do banco
touch database/database.sqlite
```

#### 6. Configurar .env

```bash
# Editar arquivo .env
nano .env
```

```env
# Configurações básicas
APP_NAME="Laravel com Inertia"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel_inertia
DB_USERNAME=laravel_user
DB_PASSWORD=sua_senha_segura

# SQLite (alternativa)
# DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/database.sqlite

# Email (desenvolvimento)
MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Cache e Sessões
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Queue
QUEUE_CONNECTION=sync
```

#### 7. Executar Migrações

```bash
# Executar migrações
php artisan migrate

# Com dados de exemplo
php artisan migrate --seed

# Para ambiente fresh (apaga tudo)
php artisan migrate:fresh --seed
```

#### 8. Construir Assets

```bash
# Desenvolvimento
npm run dev

# Produção
npm run build
```

#### 9. Iniciar Servidores

```bash
# Terminal 1 - Laravel
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2 - Vite (desenvolvimento)
npm run dev
```

## Configuração Avançada

### PostgreSQL Otimizado

#### postgresql.conf

```ini
# Configurações de performance
shared_buffers = 256MB
effective_cache_size = 1GB
maintenance_work_mem = 64MB
checkpoint_completion_target = 0.9
wal_buffers = 16MB
default_statistics_target = 100
random_page_cost = 1.1
effective_io_concurrency = 200
```

#### Criação de Índices

```sql
-- Índices para performance
CREATE INDEX idx_pacientes_email ON pacientes(email);
CREATE INDEX idx_pacientes_cpf ON pacientes(cpf);
CREATE INDEX idx_pacientes_name_surname ON pacientes(name, surname);
CREATE INDEX idx_pacientes_created_at ON pacientes(created_at);
CREATE INDEX idx_users_email ON users(email);
```

### PHP Configuração

#### php.ini (Desenvolvimento)

```ini
; Configurações para desenvolvimento
display_errors = On
error_reporting = E_ALL
log_errors = On
memory_limit = 512M
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 300
max_input_vars = 3000

; Extensões necessárias
extension=pdo_pgsql
extension=pdo_sqlite
extension=gd
extension=curl
extension=mbstring
extension=xml
extension=zip
```

## Verificação da Instalação

### Scripts de Verificação

#### check-installation.sh

```bash
#!/bin/bash

echo "Verificando instalação..."

# Verificar PHP
echo "📋 PHP Version:"
php --version | head -n 1

# Verificar Composer
echo "📦 Composer Version:"
composer --version

# Verificar Node.js
echo "🟢 Node.js Version:"
node --version

# Verificar npm
echo "📦 npm Version:"
npm --version

# Verificar dependências PHP
echo "Verificando dependências PHP..."
php artisan --version

# Verificar banco de dados
echo "Verificando conexão com banco..."
php artisan migrate:status

# Verificar assets
echo "Verificando assets..."
if [ -f "public/build/manifest.json" ]; then
    echo "✅ Assets compilados encontrados"
else
    echo "❌ Assets não compilados. Execute: npm run build"
fi

echo "✅ Verificação concluída!"
```

### Comandos de Verificação

```bash
# Verificar configuração
php artisan config:show

# Verificar rotas
php artisan route:list

# Verificar migrações
php artisan migrate:status

# Verificar cache
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verificar testes
php artisan test

# Verificar assets
npm run build
npm run type-check
npm run lint
```

## Solução de Problemas Comuns

### Erro de Permissões

```bash
# Corrigir permissões de diretórios
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Para desenvolvimento (não recomendado em produção)
chmod -R 777 storage
chmod -R 777 bootstrap/cache
```

### Erro de Chave da Aplicação

```bash
# Gerar nova chave
php artisan key:generate
```

### Erro de Assets

```bash
# Limpar cache do Vite
rm -rf node_modules/.vite
npm run build
```

### Erro de Banco de Dados

```bash
# Verificar conexão
php artisan tinker
>>> DB::connection()->getPdo()

# Recriar banco
php artisan migrate:fresh --seed
```

### Problemas com Node.js

```bash
# Limpar cache do npm
npm cache clean --force
rm -rf node_modules package-lock.json
npm install
```

## Próximos Passos

Após a instalação bem-sucedida:

1. **[Configurar Desenvolvimento](../development/README.md)**
2. **[Executar Testes](../testing/README.md)**
3. **[Configurar Deploy](../deployment/README.md)**
4. **[Monitoramento](../utilities/MONITORING.md)**

---

**Suporte**: Se encontrar problemas, consulte a [documentação de troubleshooting](../testing/TROUBLESHOOTING.md) ou abra uma [issue](https://github.com/HeroDestiny/laravel_com_inertia/issues).

```bash
# 1. Clone o repositório
git clone https://github.com/HeroDestiny/laravel_com_inertia.git
cd laravel_com_inertia/src

# 2. Instale dependências
composer install --no-dev
npm install

# 3. Configure o ambiente
cp .env.example .env
php artisan key:generate

# 4. Configure o banco de dados
# SQLite (mais simples)
touch database/database.sqlite
php artisan migrate --seed

# OU PostgreSQL (configurar DB_* no .env)
php artisan migrate --seed

# 5. Inicie os servidores
php artisan serve --host=0.0.0.0 --port=8000
npm run dev
```

## Configuração Avançada

### PostgreSQL Local

Se quiser usar PostgreSQL localmente:

```bash
# 1. Instalar PostgreSQL
sudo apt install postgresql postgresql-contrib  # Ubuntu/Debian
# brew install postgresql                        # macOS

# 2. Criar usuário e banco
sudo -u postgres createuser --interactive
sudo -u postgres createdb laravel_inertia

# 3. Configurar .env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel_inertia
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### Verificação da Instalação

```bash
# Verificar PHP
php -v
php -m | grep -E "(pdo_sqlite|pdo_pgsql|curl|zip)"

# Verificar Node.js
node -v
npm -v

# Verificar Laravel
php artisan --version

# Executar testes
php artisan test
```

## Próximos Passos

Após a configuração inicial:

1. **[💻 Development Guide](../development/README.md)** - Fluxo de desenvolvimento
2. **[Testing Guide](../testing/README.md)** - Executar testes
3. **[UML Diagrams](../development/UML_DIAGRAMS.md)** - Gerar diagramas

## Troubleshooting

### Problemas Comuns

1. **Erro de permissões**

    ```bash
    sudo chown -R $USER:$USER storage bootstrap/cache
    chmod -R 755 storage bootstrap/cache
    ```

2. **Dependências PHP faltando**

    ```bash
    # Ubuntu/Debian
    sudo apt install php8.2-sqlite3 php8.2-pgsql php8.2-curl php8.2-zip

    # macOS
    brew install php@8.2
    ```

3. **Erro na migração**

    ```bash
    # Verificar configuração do banco
    php artisan config:cache
    php artisan migrate:status
    ```

4. **Problemas com Vite**
    ```bash
    npm cache clean --force
    rm -rf node_modules package-lock.json
    npm install
    ```

---

**Dica:** Use o DevContainer para evitar problemas de configuração!
