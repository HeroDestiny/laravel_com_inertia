#!/usr/bin/env python3

import base64
import urllib.parse
import urllib.request
import sys
import os

def generate_uml_image(puml_file, output_file):
    """
    Gera uma imagem PNG a partir de um arquivo PlantUML usando o serviço web
    """
    if not os.path.exists(puml_file):
        print(f"Erro: Arquivo {puml_file} não encontrado!")
        return False
    
    try:
        # Lê o conteúdo do arquivo PlantUML
        with open(puml_file, 'r', encoding='utf-8') as f:
            puml_content = f.read()
        
        # Codifica para URL
        encoded_content = base64.b64encode(puml_content.encode('utf-8')).decode('ascii')
        
        # URL do serviço PlantUML
        url = f"http://www.plantuml.com/plantuml/png/{encoded_content}"
        
        print(f"Baixando imagem do PlantUML...")
        
        # Baixa a imagem
        urllib.request.urlretrieve(url, output_file)
        
        print(f"Imagem gerada com sucesso: {output_file}")
        return True
        
    except Exception as e:
        print(f"Erro ao gerar a imagem: {e}")
        return False

if __name__ == "__main__":
    puml_file = sys.argv[1] if len(sys.argv) > 1 else "storage/uml/domain-models.puml"
    output_file = "storage/uml/domain-models.png"
    
    # Cria o diretório de saída se não existir
    os.makedirs(os.path.dirname(output_file), exist_ok=True)
    
    generate_uml_image(puml_file, output_file)
