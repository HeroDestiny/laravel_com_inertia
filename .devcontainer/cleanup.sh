#!/bin/bash

# Script de limpeza para executar antes de criar o DevContainer
# Remove containers antigos que possam causar conflito

echo "🧹 Limpando containers antigos antes de criar o DevContainer..."

# Remove containers específicos com nomes fixos (se existirem)
docker rm -f laravel-inertia-app laravel-inertia-postgres 2>/dev/null || true

# Remove containers parados relacionados ao projeto
docker container prune -f

echo "✅ Limpeza concluída! Pode prosseguir com o rebuild do DevContainer."
