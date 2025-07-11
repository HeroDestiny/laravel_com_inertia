@echo off
REM Script para parar PostgreSQL no Windows

echo Parando PostgreSQL...

docker-compose -f docker-compose.postgres.yml down

echo PostgreSQL parado com sucesso!
pause
