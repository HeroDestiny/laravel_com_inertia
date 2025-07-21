# Migração SQLite → PostgreSQL

## **Migração Concluída**

Seu projeto foi migrado com sucesso de SQLite para PostgreSQL. Todas as configurações foram atualizadas.

### **Alterações Realizadas:**

#### **1. Configurações Laravel**

- `config/database.php` - Padrão alterado para PostgreSQL
- `.env` e `.env.example` - Configurações do PostgreSQL
- Adicionado `doctrine/dbal` para melhor suporte

#### **2. DevContainer**

- PostgreSQL 16 adicionado ao devcontainer
- Banco `laravel_inertia` criado automaticamente
- Usuário `laravel_user` configurado
- Porta 5432 exposta

#### **3. Docker (Desenvolvimento)**

- Laravel Sail configurado para PostgreSQL
- Serviço PostgreSQL adicionado
- Health checks configurados
- Volumes persistentes

#### **4. Docker (Produção)**

- Dockerfile atualizado para `pdo_pgsql`
- PostgreSQL 16 Alpine no docker-compose
- Script de inicialização com aguardo do DB
- Variáveis de ambiente configuradas

#### **5. CI/CD**

- GitHub Actions com PostgreSQL de teste
- Extensões PHP atualizadas
- Configuração automática de test database

### **Como Usar:**

#### **Desenvolvimento (DevContainer)**

```bash
# O PostgreSQL já está rodando no devcontainer
php artisan migrate
php artisan db:seed  # se tiver seeders
```

#### **Desenvolvimento (Laravel Sail)**

```bash
# Usar Laravel Sail com PostgreSQL
cd docker/development
docker-compose up -d
./vendor/bin/sail artisan migrate
```

#### **Produção**

```bash
# Deploy com PostgreSQL
cd docker/production
docker-compose up -d
```

### **Verificar Conexão:**

```bash
# Testar conexão
php artisan tinker
> DB::connection()->getPdo();
> DB::select('SELECT version()');
```

### **Configurações do Banco:**

**Desenvolvimento:**

- Host: `127.0.0.1` (devcontainer) ou `pgsql` (sail)
- Port: `5432`
- Database: `laravel_inertia`
- Username: `laravel_user`
- Password: `laravel_password`

**Produção:**

- Host: `postgres`
- Port: `5432`
- Database: `${DB_DATABASE}`
- Username: `${DB_USERNAME}`
- Password: `${DB_PASSWORD}`

### **Importantes:**

1. **Dados Existentes**: Se você tinha dados no SQLite, precisará migrar manualmente
2. **Tipos de Dados**: PostgreSQL é mais rígido que SQLite com tipos
3. **Performance**: PostgreSQL é mais performático para aplicações complexas
4. **Backup**: Sempre faça backup antes de executar migrações

### **Próximos Passos:**

1. Execute as migrações: `php artisan migrate`
2. Se tiver seeders: `php artisan db:seed`
3. Teste a aplicação completamente
4. Configure backup automático do PostgreSQL
5. Monitore performance e queries

### **Troubleshooting:**

**Erro de Conexão:**

```bash
# Verificar se PostgreSQL está rodando
pg_isready -h 127.0.0.1 -p 5432

# Verificar logs
docker logs postgres_container_name
```

**Erro de Migração:**

```bash
# Limpar cache e tentar novamente
php artisan config:clear
php artisan cache:clear
php artisan migrate:fresh
```

### **Benefícios Obtidos:**

- **Performance superior** para queries complexas
- **ACID compliance** completo
- **Tipos de dados avançados** (JSON, Array, etc.)
- **Índices sofisticados** (GIN, GiST, etc.)
- **Extensões poderosas** (UUID, Full-text search)
- **Escalabilidade** para aplicações grandes
- **Backup e replicação** profissionais

**Sua aplicação agora está pronta para escalar!**
