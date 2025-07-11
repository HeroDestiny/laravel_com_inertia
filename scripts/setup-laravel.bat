@echo off
REM Script para configurar o Laravel com PostgreSQL no Windows

echo Configurando Laravel para PostgreSQL...

cd src

REM Copia o .env.example se .env não existir
if not exist .env (
    copy .env.example .env
    echo Arquivo .env criado
)

REM Gera a chave da aplicação
php artisan key:generate

REM Executa as migrações
echo Executando migrações...
php artisan migrate

REM Executa os seeders (se existirem)
echo Executando seeders...
php artisan db:seed

echo Laravel configurado com PostgreSQL!
pause
