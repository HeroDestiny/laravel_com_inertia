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

# Lê o conteúdo do arquivo PlantUML
PUML_CONTENT=$(cat "$PUML_FILE")

# Codifica o conteúdo em base64 para a URL do PlantUML
ENCODED=$(echo -n "$PUML_CONTENT" | base64 | tr -d '\n')

# URL do serviço PlantUML
PLANTUML_URL="http://www.plantuml.com/plantuml/png/$ENCODED"

# Baixa a imagem
OUTPUT_FILE="$OUTPUT_DIR/domain-models.png"
curl -s "$PLANTUML_URL" -o "$OUTPUT_FILE"

if [ $? -eq 0 ]; then
    echo "Imagem gerada com sucesso: $OUTPUT_FILE"
else
    echo "Erro ao gerar a imagem."
    exit 1
fi
