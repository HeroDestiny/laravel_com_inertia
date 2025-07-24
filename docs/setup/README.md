# ⚙️ Setup e Configuração

Guia para configuração inicial do projeto Laravel com Inertia.js.

## 🐳 DevContainer (Recomendado)

A forma mais rápida e consistente:

1. Abra o projeto no VS Code
2. Instale a extensão "Dev Containers"
3. Clique em "Reopen in Container"
4. Aguarde a configuração automática (PostgreSQL incluído)

**Vantagens:**

-   ✅ PostgreSQL integrado
-   ✅ Todas as dependências pré-instaladas
-   ✅ Configuração zero
-   ✅ Ambiente consistente entre desenvolvedores

## 💻 Instalação Local

### Pré-requisitos

-   **PHP 8.2+** com extensões: `pdo_sqlite`, `pdo_pgsql`, `curl`, `zip`
-   **Node.js 18+** com npm
-   **Composer** 2.x
-   **PostgreSQL 13+** (opcional, SQLite como fallback)

### Passos de Instalação

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

## 🔧 Configuração Avançada

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

## 🚀 Próximos Passos

Após a configuração inicial:

1. **[💻 Development Guide](../development/README.md)** - Fluxo de desenvolvimento
2. **[🧪 Testing Guide](../testing/README.md)** - Executar testes
3. **[📊 UML Diagrams](../development/UML_DIAGRAMS.md)** - Gerar diagramas

## 🔍 Troubleshooting

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

**💡 Dica:** Use o DevContainer para evitar problemas de configuração!
