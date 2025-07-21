@echo off
REM Script para iniciar PostgreSQL com Docker Compose no Windows

echo Iniciando PostgreSQL com Docker...

REM Verifica se o Docker está rodando
docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo Docker não está rodando. Inicie o Docker Desktop primeiro.
    pause
    exit /b 1
)

REM Inicia os containers
docker-compose -f docker-compose.postgres.yml up -d

REM Aguarda o PostgreSQL inicializar
echo Aguardando PostgreSQL inicializar...
timeout /t 10 /nobreak >nul

REM Verifica se os containers estão rodando
docker-compose -f docker-compose.postgres.yml ps | find "Up" >nul
if %errorlevel% equ 0 (
    echo PostgreSQL iniciado com sucesso!
    echo.
    echo Informações de conexão:
    echo    Host: localhost
    echo    Porta: 5432
    echo    Database: laravel_inertia
    echo    Usuário: laravel_user
    echo    Senha: laravel_password
    echo.
    echo pgAdmin disponível em: http://localhost:8080
    echo    Email: admin@laravel.com
    echo    Senha: admin123
) else (
    echo Erro ao iniciar PostgreSQL
    docker-compose -f docker-compose.postgres.yml logs
)

pause
