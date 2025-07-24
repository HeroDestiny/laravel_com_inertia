# Correções de Segurança Aplicadas no Docker - ATUALIZADO

## 🛡️ Correções Implementadas (Janeiro 2025)

### 1. **Correções CRÍTICAS**

#### ✅ Container não roda mais como ROOT
- **Antes**: Supervisord executava como `user=root`
- **Depois**: Todo o container executa como `appuser` (UID 1000)
- **Impacto**: Elimina risco de escalação de privilégios

#### ✅ Composer executado como usuário não-root
- **Antes**: `RUN composer install` como root
- **Depois**: `USER appuser` antes do composer install
- **Impacto**: Previne execução de código malicioso como root

#### ✅ Artisan cache executado como usuário não-root
- **Antes**: Cache gerado durante build como root
- **Depois**: Cache gerado no entrypoint como appuser
- **Impacto**: Arquivos de cache com permissões corretas

### 2. **Correções ALTAS**

#### ✅ Versões fixas das imagens base
```dockerfile
# Antes:
FROM node:18-alpine
COPY --from=composer:latest

# Depois:  
FROM node:18.19.1-alpine
COPY --from=composer:2.6.6
```

#### ✅ Cópia seletiva de arquivos
```dockerfile
# Antes:
COPY src/ .

# Depois:
COPY --chown=appuser:appgroup src/app ./app
COPY --chown=appuser:appgroup src/bootstrap ./bootstrap
# ... apenas arquivos necessários
```

#### ✅ .dockerignore criado
- Exclui arquivos sensíveis: `.env*`, `.git*`, logs, cache
- Reduz superficie de ataque e tamanho da imagem

### 3. **Correções MÉDIAS**

#### ✅ CSP rigorosa implementada
```nginx
# Antes:
Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'"

# Depois:
Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self'; connect-src 'self'; frame-ancestors 'none';"
```

#### ✅ Headers de segurança melhorados
- `X-Frame-Options: DENY`
- `Strict-Transport-Security` com 1 ano
- `Permissions-Policy` restritiva
- `server_tokens off`

#### ✅ HEALTHCHECK implementado
```dockerfile
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:8080/health || exit 1
```

#### ✅ Limpeza de cache e pacotes desnecessários
```dockerfile
RUN apk del --no-cache git curl \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/* \
    && rm -rf /root/.composer /root/.npm
```

### 4. **Correções de Hardening**

#### ✅ Usuário não-root no build stage
- Node.js build também executa como `nodeuser`
- Evita arquivos com ownership root

#### ✅ Permissões restritivas
```dockerfile
chmod -R 644 /var/www/html/public
chmod 755 /var/www/html/public
```

#### ✅ Supervisord configurado corretamente
- `user=appuser` para todos os processos
- Logs centralizados e seguros

## 🔒 Resultado da Segurança

### Antes:
- ❌ Container rodando como root
- ❌ Composer executado como root  
- ❌ Versões não fixas
- ❌ CSP permissiva
- ❌ Arquivos sensíveis copiados
- ❌ Sem healthcheck

### Depois:
- ✅ Container totalmente não-root
- ✅ Todas as operações como appuser
- ✅ Versões fixas e atualizadas
- ✅ CSP restritiva
- ✅ Apenas arquivos necessários
- ✅ Healthcheck implementado
- ✅ Headers de segurança rigorosos
- ✅ Limpeza de artefatos

## 🚀 Como usar

```bash
# Build da imagem segura
docker build -f docker/production/Dockerfile -t laravel-app:secure .

# Run com variáveis de ambiente
docker run -e DB_HOST=postgres -e DB_USERNAME=user -e DB_PASSWORD=pass laravel-app:secure
```

## 📊 Verificação

```bash
# Verificar usuário dentro do container
docker run laravel-app:secure whoami
# Output: appuser

# Verificar processo
docker run laravel-app:secure ps aux
# Todos os processos rodando como appuser
```
- Created a dedicated `appuser` (UID 1000) and `appgroup` (GID 1000) for running application services
- All application services (nginx, php-fpm) now run as the non-root `appuser`
- Prevents privilege escalation attacks and limits potential damage from container escape

### 2. Port Configuration
- Changed nginx to listen on port 8080 instead of port 80
- Non-root users cannot bind to privileged ports (< 1024)
- This is a standard security practice for containerized applications

### 3. File Permissions
- Proper ownership and permissions set for all application directories
- Application files owned by `appuser:appgroup`
- Storage and cache directories have appropriate write permissions

### 4. Service Isolation
- Supervisor runs as root but spawns services as `appuser`
- Each service (nginx, php-fpm) explicitly configured to run as `appuser`
- Provides proper process isolation and security boundaries

### 5. Directory Structure
- Created dedicated log directories with proper permissions
- Nginx temporary directories properly configured for non-root operation
- All runtime directories accessible to the application user

## Security Benefits

1. **Principle of Least Privilege**: Services run with minimal required permissions
2. **Attack Surface Reduction**: Eliminated root access for application services
3. **Container Security**: Follows Docker security best practices
4. **Process Isolation**: Clear separation between system and application processes

## Usage Notes

- The container still starts as root for initial setup (file permissions, directory creation)
- Application services immediately drop to non-root user through supervisor
- Port 8080 should be mapped to host port 80 in docker-compose or deployment configuration
- All Laravel operations (migrations, cache operations) run as the application user

## Example Docker Compose Update

```yaml
services:
  app:
    build: .
    ports:
      - "80:8080"  # Map host port 80 to container port 8080
    # ... other configuration
```

This approach maintains security while ensuring all necessary operations can be performed during container startup.
