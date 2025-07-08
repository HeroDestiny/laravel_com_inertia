#!/bin/bash

# Script para gerar imagem PNG do diagrama UML
# Este script usa o serviço web do PlantUML para gerar a imagem

PUML_FILE="${1:-storage/uml/domain-models.puml}"
OUTPUT_DIR="storage/uml"

if [ ! -f "$PUML_FILE" ]; then
    echo "Erro: Arquivo $PUML_FILE não encontrado!"
    exit 1
fi

echo "Gerando imagem PNG do diagrama UML..."

# Usa o script Python que implementa a codificação correta
python3 scripts/generate_uml_image.py "$PUML_FILE"

if [ $? -eq 0 ]; then
    echo "Imagem gerada com sucesso!"
else
    echo "Erro ao gerar a imagem."
    exit 1
fi
